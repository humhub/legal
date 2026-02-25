<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\assets;

use humhub\components\assets\AssetBundle;

class Assets extends AssetBundle
{
    public $forceCopy = false;

    public $sourcePath = '@legal/resources';
    public $css = [
        'cookieconsent.min.css',
    ];
    public $js = [
        'cookieconsent.min.js',
    ];

}
