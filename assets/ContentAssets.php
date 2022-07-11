<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2022 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\assets;

use humhub\components\assets\AssetBundle;

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
        'js/humhub.legal.js'
    ];
}