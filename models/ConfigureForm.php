<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\models;

use humhub\modules\legal\Module;
use Yii;
use yii\base\Model;

class ConfigureForm extends Model
{
    public $enabledPages;
    public $showCookiePopup;
    public $defaultLanguage;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
#            [['activatedPages', 'supportDescription'], 'string'],
            #           ['menuLocation', 'in', 'range' => array_keys(static::getMenuLocations())],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'defaultLanguage' => Yii::t('LegalModule.base', 'Default languge'),
            'enabledPages' => Yii::t('LegalModule.base', 'Enabled pages'),
            'showCookiePopup' => Yii::t('LegalModule.base', 'Show cookie popup'),
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
        /*
        $settings = $this->getModule()->settings;

        $this->supportTitle = $this->getModule()->getSupportTitle();
        $this->supportDescription = $settings->get('supportDescription');
        $this->menuLocation = $this->getModule()->getMenuLocation();
        */
        return true;
    }

    public function saveSettings()
    {
        /*
        $settings = $this->getModule()->settings;

        try {
            $settings->set('supportTitle', $this->supportTitle);
            $settings->set('supportDescription', $this->supportDescription);
            $settings->set('menuLocation', $this->menuLocation);
        } catch (Exception $e) {
            Yii::error($e->getMessage());
            return false;
        }
        */

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
