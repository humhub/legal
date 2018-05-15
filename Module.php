<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal;

use Yii;
use yii\helpers\Url;

class Module extends \humhub\components\Module
{

    /**
     * @inheritdoc
     */
    public function getConfigUrl()
    {
        return Url::to([
            '/legal/admin'
        ]);
    }

    /**
     * @return array of page keys
     */
    public function getEnabledPages()
    {
        if (empty($this->settings->get('enabledPages'))) {
            return [];
        }

        return explode(',', $this->settings->get('enabledPages'));
    }


    /**
     * @param $pageKey
     * @return bool
     */
    public function isPageEnabled($pageKey)
    {
        if (in_array($pageKey, $this->getEnabledPages())) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getDefaultLanguage()
    {
        if (empty($this->settings->get('defaultLanguage'))) {
            return Yii::$app->language;
        }

        return $this->settings->get('defaultLanguage');
    }

    /**
     * @return bool
     */
    public function showAgeCheck()
    {
        return (boolean)$this->settings->get('showAgeCheck', false);
    }

    public function getName()
    {
        return Yii::t('LegalModule.base', 'Legal Tools');
    }

    public function getDescription()
    {
        return Yii::t('LegalModule.base', 'Adds several editable legal options, like an imprint and a privacy policy.');
    }

}
