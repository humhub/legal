<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal;

use humhub\components\gates\GateInitEvent;
use humhub\helpers\ControllerHelper;
use humhub\modules\admin\controllers\UserController;
use humhub\modules\comment\models\Comment;
use humhub\modules\content\widgets\richtext\ProsemirrorRichText;
use humhub\modules\legal\components\LegalGate;
use humhub\modules\legal\models\Page;
use humhub\modules\legal\models\RegistrationChecks;
use humhub\modules\legal\widgets\Content;
use humhub\modules\legal\widgets\CookieNote;
use humhub\modules\post\models\Post;
use humhub\modules\ui\menu\MenuLink;
use humhub\modules\user\events\UserEvent;
use humhub\modules\user\models\forms\Registration;
use humhub\modules\user\widgets\AccountSettingsMenu;
use humhub\widgets\FooterMenu;
use humhub\widgets\LayoutAddons;
use Yii;
use yii\base\ActionEvent;
use yii\helpers\Url;

/**
 * @author luke
 */
class Events
{
    public const SESSION_KEY_LEGAL_AFTER_REGISTRATION = 'legalModuleAfterRegistration';

    public static function onFooterMenuInit($event)
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('legal');

        /* @var FooterMenu $menu */
        $menu = $event->sender;

        $sortOrder = 100;
        foreach (Page::getPages() as $pageKey => $title) {
            if (!$module->isPageEnabled($pageKey) || !in_array($pageKey, Page::getFooterMenuPages())) {
                // Cookie notice is not a navigation page
                continue;
            }

            $page = Page::getPage($pageKey);
            if ($page !== null) {
                $sortOrder += 10;
                $menu->addEntry(new MenuLink([
                    'label' => $page->title,
                    'url' => Url::to(['/legal/page/view', 'pageKey' => $pageKey], true),
                    'sortOrder' => $sortOrder,
                ]));
            }
        }

    }

    public static function onLayoutAddonInit($event)
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('legal');

        /** @var LayoutAddons $layoutAddons */
        $layoutAddons = $event->sender;

        if ($module->isPageEnabled(Page::PAGE_KEY_COOKIE_NOTICE)) {
            $layoutAddons->addWidget(CookieNote::class);
        }

    }

    /**
     * Registers the user gates of this module (see core docs/develop/user-gates.md).
     * The gate replaces the former request interception of this handler.
     *
     * @since 1.8
     */
    public static function onGateInit(GateInitEvent $event): void
    {
        $event->manager->register(new LegalGate());
    }

    /**
     * Presents the legal pages in the full screen login layout while checks are
     * still open (the interception itself is handled by the LegalGate).
     */
    public static function onBeforeControllerAction(ActionEvent $event)
    {
        if (Yii::$app->user->isGuest || Yii::$app->request->isAjax) {
            return;
        }

        $controller = $event->action->controller;
        if ($controller->module->id !== 'legal' || $controller->id !== 'page') {
            return;
        }

        $registrationCheck = new RegistrationChecks(['user' => Yii::$app->user->getIdentity()]);
        if ($registrationCheck->hasOpenCheck()) {
            $event->sender->layout = '@user/views/layouts/main';
            $event->sender->subLayout = '@legal/views/page/layout_login';
        }
    }

    public static function skipVerifying(): bool
    {
        // Do not use on console request
        if (Yii::$app->request->isConsoleRequest) {
            return true;
        }

        // If, for example, users are automatically registered with LDAP during login, no legal checks should take place.
        // Otherwise the auto registration would be broken.
        if (Yii::$app->controller instanceof \humhub\modules\user\controllers\AuthController) {
            return true;
        }

        // Don't ask admin on creating of a new user from back-office.
        // The AdminUserController already enforces the ManageUsers permission — an additional
        // isAdmin() check here would block non-system-admins with the ManageUsers permission.
        if (Yii::$app->controller instanceof UserController) {
            return true;
        }

        return false;
    }

    public static function onRegistrationFormInit($event)
    {
        if (static::skipVerifying()) {
            return;
        }

        /** @var Registration $hForm */
        $hForm = $event->sender;

        /** @var Module $module */
        $module = Yii::$app->getModule('legal');

        $model = new RegistrationChecks(['restrictToSettingKey' => $module->showPagesAfterRegistration() ? RegistrationChecks::SETTING_KEY_AGE : false]);
        $hForm->models['RegistrationChecks'] = $model;

        if ($module->showPagesAfterRegistration()) {
            Yii::$app->session->set(static::SESSION_KEY_LEGAL_AFTER_REGISTRATION, 'true');
        }

        $elements = [];

        if ($model->showTermsCheck()) {
            $elements['termsCheck'] = [
                'type' => 'checkbox',
                'class' => 'form-control',
            ];
        }

        if ($model->showPrivacyCheck()) {
            $elements['dataPrivacyCheck'] = [
                'type' => 'checkbox',
                'class' => 'form-control',
            ];
        }

        if ($module->showAgeCheck()) {
            $elements['ageCheck'] = [
                'type' => 'checkbox',
                'class' => 'form-control',
            ];
        }

        $hForm->definition['elements']['RegistrationChecks'] = [
            'type' => 'form',
            'elements' => $elements,
        ];
    }

    /**
     * @param UserEvent $event
     * @throws \yii\base\Exception
     */
    public static function onRegistrationAfterRegistration(UserEvent $event)
    {
        if (static::skipVerifying()) {
            return;
        }

        /** @var Module $module */
        $module = Yii::$app->getModule('legal');

        $model = new RegistrationChecks([
            'user' => $event->user,
            'restrictToSettingKey' => $module->showPagesAfterRegistration() ? RegistrationChecks::SETTING_KEY_AGE : false,
        ]);
        $model->load(Yii::$app->request->post());
        $model->save();
    }

    public static function onAfterRunRichText($event)
    {
        /* @var ProsemirrorRichText $richText */
        $richText = $event->sender;

        if (!isset($richText->record) || empty($event->result)) {
            return;
        }

        if ($richText->record instanceof Post || $richText->record instanceof Comment) {
            $event->result = Content::widget(['content' => $event->result, 'richtext' => false]);
        }
    }

    public static function onAccountSettingsMenuInit($event)
    {
        /* @var AccountSettingsMenu $menu */
        $menu = $event->sender;

        /* @var Module $module */
        $module = Yii::$app->getModule('legal');

        if ($module->isEnabledExportUserData()) {
            $menu->addEntry(new MenuLink([
                'label' => Yii::t('LegalModule.base', 'Export personal data'),
                'url' => ['/legal/export'],
                'sortOrder' => 1000,
                'isActive' => ControllerHelper::isActivePath('legal', 'export'),
            ]));
        }
    }

    /**
     * Callback on daily cron job run
     */
    public static function onCronDailyRun()
    {
        Yii::$app->queue->push(new jobs\DeletePackages());
    }
}
