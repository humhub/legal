<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2022 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\assets;

use humhub\components\assets\AssetBundle;
use humhub\modules\legal\Module;
use humhub\modules\ui\icon\widgets\Icon;
use Yii;

class ContentAssets extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@legal/resources';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/humhub.legal.js',
    ];

    /**
     * @inheritdoc
     */
    public static function register($view)
    {
        /* @var Module $module */
        $module = Yii::$app->getModule('legal');

        $config = [];

        if ($module->getExternalLinksConfig('icon')) {
            $config['prefix'] = Icon::get('external-link')->asString() . ' ';
        }

        if ($module->getExternalLinksConfig('modal')) {
            $config['confirmTitle'] = Yii::t('LegalModule.base', '<strong>External</strong> Link');
            $config['confirmText'] = Yii::t('LegalModule.base', 'This link leads to an external website. Would you like to proceed?');
            $config['confirmButton'] = Yii::t('LegalModule.base', 'Proceed');
            $config['redirectAfter'] = 5;
        }

        $view->registerJsConfig('legal', ['externalLink' => $config]);

        return parent::register($view);
    }
}
