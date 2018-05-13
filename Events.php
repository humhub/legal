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
use yii\helpers\Url;
use yii\web\UserEvent;


/**
 * @author luke
 */
class Events
{

    public function onFooterMenuInit($event)
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('legal');

        $sortOrder = 100;
        foreach (Page::getPages() as $pageKey => $title) {
            if (!$module->isPageEnabled($pageKey) || $pageKey === Page::PAGE_KEY_COOKIE_NOTICE) {
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

        if (!$module->isPageEnabled(Page::PAGE_KEY_COOKIE_NOTICE)) {
            return;
        }

        /** @var LayoutAddons $layoutAddons */
        $layoutAddons = $event->sender;
        $layoutAddons->addWidget(CookieNote::class);
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

    public static function onRegistrationAfterRegistration(UserEvent $event)
    {
        /** @var User $user */
        $user = $event->identity;

        $model = new RegistrationChecks(['user' => $user]);
        $model->load(Yii::$app->request->post());
        $model->save();
    }
}
