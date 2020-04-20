<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\assets;

use yii\web\AssetBundle;

class Assets extends AssetBundle
{

    public $defer = true;

    public $publishOptions = [
        'forceCopy' => false
    ];

    public $sourcePath = '@legal/resources';
    public $css = [
        'cookieconsent.min.css'
    ];
    public $js = [
        'cookieconsent.min.js'
    ];

}
