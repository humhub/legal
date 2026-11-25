<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\legal\widgets\Content;
use humhub\widgets\bootstrap\Button;

/* @var $this \humhub\components\View */
/* @var $page \humhub\modules\legal\models\Page */
/* @var $canManagePages boolean */
?>

<div class="card content-card">
    <div class="card-header">
        <div class="card-title"><?= $page->title; ?></div>
        <?php if ($canManagePages): ?>
            <div class="card-header-actions">
                <?= Button::light(Yii::t('LegalModule.base', 'Edit this page'))
                    ->link(['/legal/admin/page', 'pageKey' => $page->page_key])
                    ->pjax(false) ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?= Content::widget(['content' => $page->content]) ?>
    </div>
</div>
