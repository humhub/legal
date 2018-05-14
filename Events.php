<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal;

use humhub\modules\legal\models\Page;
use humhub\modules\legal\models\RegistrationChecks;
use humhub\modules\legal\widgets\CookieNote;
use humhub\modules\user\models\forms\Registration;
use humhub\modules\user\models\User;
use humhub\widgets\LayoutAddons;
use Yii;
use yii\base\ActionEvent;
use yii\helpers\Url;
use yii\web\UserEvent;


/**
 * @author luke
 */
class Events
{

    const SESSION_KEY_LEGAL_CHECK = 'legalModuleChecked';

    public function onFooterMenuInit($event)
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('legal');

        $sortOrder = 100;
        foreach (Page::getPages() as $pageKey => $title) {
            if (!$module->isPageEnabled($pageKey) || !in_array($pageKey, Page::getFooterMenuPages())) {
                // Cookie notice is not a navigation page
                continue;
            }

            $page = Page::getPage($pageKey);
            if ($page !== null) {
                $sortOrder += 10;
                $event->sender->addItem(array(
                    'label' => $page->title,
                    'url' => Url::to(['/legal/page/view', 'pageKey' => $pageKey], true),
                    'sortOrder' => $sortOrder,
                ));
            }
        }

    }

    public function onLayoutAddonInit($event)
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('legal');

        /** @var LayoutAddons $layoutAddons */
        $layoutAddons = $event->sender;

        if ($module->isPageEnabled(Page::PAGE_KEY_COOKIE_NOTICE)) {
            $layoutAddons->addWidget(CookieNote::class);
        }

    }


    public function onBeforeControllerAction(ActionEvent $event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }

        // Legal already checked
        if (!empty(Yii::$app->session->get(static::SESSION_KEY_LEGAL_CHECK))) {
            return;
        }

        /** @var Module $module */
        $module = Yii::$app->getModule('legal');

        // Legal update enabled?
        if (!$module->isPageEnabled(Page::PAGE_KEY_LEGAL_UPDATE) || Page::getPage(Page::PAGE_KEY_LEGAL_UPDATE) === null) {
            return;
        }

        // Allow user delete action
        if ($event->action->controller->module->id === 'user' && $event->action->controller->id === 'account' && $event->action->id === 'delete') {
            return;
        }
        if ($event->action->controller->module->id === 'user' && $event->action->controller->id === 'auth') {
            return;
        }
        if ($event->action->controller->module->id === 'mail' && $event->action->controller->id === 'mail') {
            return;
        }
        if ($event->action->controller->id === 'poll') {
            return;
        }

        $registrationCheck = new RegistrationChecks(['user' => Yii::$app->user->getIdentity()]);
        if (!$registrationCheck->hasOpenCheck()) {
            Yii::$app->session->set(static::SESSION_KEY_LEGAL_CHECK, 'true');
            return;
        }

        // Allow legal module usage
        if ($event->action->controller->module->id === 'legal') {
            $event->sender->layout = '@user/views/layouts/main';
            $event->sender->subLayout = '@legal/views/page/layout_login';
            return;
        }

        $event->isValid = false;
        $event->result = Yii::$app->response->redirect(['/legal/page/update']);
    }


    public function onRegistrationFormInit($event)
    {
        /** @var Registration $hForm */
        $hForm = $event->sender;

        $hForm->models['RegistrationChecks'] = new RegistrationChecks();
    }

    public function onRegistrationFormRender($event)
    {
        /** @var Registration $hForm */
        $hForm = $event->sender;

        /** @var RegistrationChecks $model */
        $model = $hForm->models['RegistrationChecks'];

        /** @var Module $module */
        $module = Yii::$app->getModule('legal');

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
            'elements' => $elements
        ];
    }

    /**
     * @param UserEvent $event
     * @throws \yii\base\Exception
     */
    public static function onRegistrationAfterRegistration(UserEvent $event)
    {
        /** @var User $user */
        $user = $event->identity;

        $model = new RegistrationChecks(['user' => $user]);
        $model->load(Yii::$app->request->post());
        $model->save();
    }
}
