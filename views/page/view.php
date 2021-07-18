<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\content\widgets\richtext\RichText;
use yii\bootstrap\Html;

/* @var $this \humhub\modules\ui\view\components\View */
/* @var $page \humhub\modules\legal\models\Page */
/* @var $canManagePages boolean */
?>

<div class="panel">
    <div class="panel-heading">
        <?php if ($canManagePages): ?>
            <?= Html::a('Edit this page', ['/legal/admin/page', 'pageKey' => $page->page_key], ['class' => 'btn btn-default pull-right', 'data-ui-loader' => '']); ?>
        <?php endif; ?>
        <h2><?= $page->title; ?></h2>
    </div>
    <div class="panel-body">
        <?= RichText::output($page->content); ?>
    </div>
</div>

