<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\models;

use humhub\modules\legal\Module;
use Yii;
use yii\base\Exception;
use yii\base\Model;

class ConfigureForm extends Model
{

    public $enabledPages;
    public $defaultLanguage;
    public $showAgeCheck;
    public $defaultAge;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enabledPages'], 'in', 'range' => array_keys(Page::getPages())],
            [['defaultLanguage'], 'in', 'range' => array_keys(Yii::$app->i18n->getAllowedLanguages())],
            [['showAgeCheck'], 'boolean'],
            [['defaultAge'], 'string'],
            ['defaultAge', 'integer', 'min' => 0, 'max' => 99]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'enabledPages' => Yii::t('LegalModule.base', 'Enabled pages and features'),
            'defaultLanguage' => Yii::t('LegalModule.base', 'Default languge'),
            'showAgeCheck' => Yii::t('LegalModule.base', 'Show age verification {age}', ['age' => $this->defaultAge]),
            'defaultAge' => Yii::t('LegalModule.base', 'Default age'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'defaultLanguage' => Yii::t('LegalModule.base', 'Will be used as default, if the legal texts are not available in the users language.')
        ];
    }

    public function loadSettings()
    {
        $this->defaultLanguage = $this->getModule()->getDefaultLanguage();
        $this->enabledPages = $this->getModule()->getEnabledPages();
        $this->showAgeCheck = $this->getModule()->showAgeCheck();
        $this->defaultAge = $this->getModule()->getDefaultAge();
        return true;
    }

    /**
     * @return bool
     */
    public function saveSettings()
    {
        $settings = $this->getModule()->settings;

        if (!is_array($this->enabledPages)) {
            $this->enabledPages = [];
        }

        try {
            $settings->set('defaultLanguage', $this->defaultLanguage);
            $settings->set('enabledPages', implode(',', $this->enabledPages));
            $settings->set('showAgeCheck', $this->showAgeCheck);
            $settings->set('defaultAge', $this->defaultAge);
        } catch (Exception $e) {
            Yii::error($e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * @return Module
     */
    public function getModule()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('legal');
        return $module;
    }

}
