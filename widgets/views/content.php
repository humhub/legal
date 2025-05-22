<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2022 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\content\widgets\richtext\RichText;
use humhub\modules\legal\assets\ContentAssets;
use humhub\components\View;
use yii\helpers\Html;

/* @var View $this */
/* @var string $content */
/* @var bool $richtext */
/* @var array $options */

ContentAssets::register($this);
?>
<?= Html::beginTag('div', $options) ?>
    <?= $richtext ? RichText::output($content) : $content ?>
<?= Html::endTag('div') ?>
