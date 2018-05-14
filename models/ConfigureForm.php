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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enabledPages'], 'in', 'range' => array_keys(Page::getPages())],
            [['defaultLanguage'], 'in', 'range' => array_keys(Yii::$app->i18n->getAllowedLanguages())],
            [['showAgeCheck'], 'boolean']
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
            'showAgeCheck' => Yii::t('LegalModule.base', 'Show age check (16+)'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'defaultLanguage' => Yii::t('LegalModule.base', 'Used page language when requested language is not available.')
        ];
    }

    public function loadSettings()
    {
        $this->defaultLanguage = $this->getModule()->getDefaultLanguage();
        $this->enabledPages = $this->getModule()->getEnabledPages();
        $this->showAgeCheck = $this->getModule()->showAgeCheck();
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
            $settings->set('showAgeCheck', (boolean)$this->showAgeCheck);
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
