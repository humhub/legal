<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\models;

use humhub\libs\Html;
use humhub\modules\legal\Module;
use humhub\modules\user\models\User;
use Yii;
use yii\base\Exception;
use yii\base\Model;

/* @var $this \humhub\components\View */

/**
 * Class RegistrationChecks
 * @package humhub\modules\legal\models
 */
class RegistrationChecks extends Model
{

    public $ageCheck;
    public $termsCheck;
    public $dataPrivacyCheck;

    /**
     * @var User
     */
    public $user;

    public function rules()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('legal');
        $rules = [];

        if ($module->showAgeCheck()) {
            $rules[] = [['ageCheck'], 'required', 'requiredValue' => 1, 'message' => ''];
        }

        if ($this->showPrivacyCheck()) {
            $rules[] = [['dataPrivacyCheck'], 'required', 'requiredValue' => 1, 'message' => ''];
        }

        if ($this->showTermsCheck()) {
            $rules[] = [['termsCheck'], 'required', 'requiredValue' => 1, 'message' => ''];
        }

        return $rules;
    }

    public function attributeLabels()
    {
        return [
            'ageCheck' => Yii::t('LegalModule.base', 'I am older than 16 years'),
            'termsCheck' => Yii::t('LegalModule.base', 'I accept the terms and conditions'),
            'dataPrivacyCheck' => Yii::t('LegalModule.base', 'I understand and accept the data privacy conditions')
        ];
    }

    public function attributeHints()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('legal');

        $spacing = '&nbsp;&nbsp;&nbsp;&nbsp;';
        $hints = [];

        $privacyPage = Page::getPage(Page::PAGE_KEY_PRIVACY_PROTECTION);
        if ($privacyPage !== null && $module->isPageEnabled(Page::PAGE_KEY_PRIVACY_PROTECTION)) {
            $link = Html::a($privacyPage->title, ['/legal/page/view', 'pageKey' => Page::PAGE_KEY_PRIVACY_PROTECTION]);
            $hints['dataPrivacyCheck'] = $spacing . Yii::t('LegalModule.base', 'More information: {link}', ['link' => $link]);
        }

        $termsPage = Page::getPage(Page::PAGE_KEY_TERMS);
        if ($termsPage !== null && $module->isPageEnabled(Page::PAGE_KEY_TERMS)) {
            $link = Html::a($termsPage->title, ['/legal/page/view', 'pageKey' => Page::PAGE_KEY_TERMS]);
            $hints['termsCheck'] = $spacing . Yii::t('LegalModule.base', 'More information: {link}', ['link' => $link]);
        }

        return $hints;
    }

    public function showPrivacyCheck()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('legal');
        return (Page::getPage(Page::PAGE_KEY_PRIVACY_PROTECTION) && $module->isPageEnabled(Page::PAGE_KEY_PRIVACY_PROTECTION));
    }

    public function showTermsCheck()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('legal');
        return (Page::getPage(Page::PAGE_KEY_TERMS) && $module->isPageEnabled(Page::PAGE_KEY_TERMS));
    }


    /**
     * @return bool
     * @throws Exception
     */
    public function save()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('legal');

        if ($this->user === null) {
            if (Yii::$app->user->isGuest) {
                throw new Exception('Could not save with valid user object!');
            }
            $this->user = Yii::$app->user->getIdentity();
        }

        if (!$this->validate()) {
            return false;
        }

        if ($this->showTermsCheck()) {
            $module->settings->user($this->user)->set('acceptedTerms', true);
            $module->settings->user($this->user)->set('acceptedTermsTime', time());
        }

        if ($this->showPrivacyCheck()) {
            $module->settings->user($this->user)->set('acceptedPrivacy', true);
            $module->settings->user($this->user)->set('acceptedPrivacyTime', time());
        }

        if ($module->showAgeCheck()) {
            $module->settings->user($this->user)->set('acceptedAge', true);
            $module->settings->user($this->user)->set('acceptedAgeTime', time());
        }

        return true;
    }
}