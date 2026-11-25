<?php

use humhub\modules\legal\widgets\AdminMenu;

?>
<div class="card content-card">
    <div class="card-header">
        <div class="card-title"><?= Yii::t('LegalModule.base', '<strong>Legal</strong> module - administration'); ?></div>
    </div>
    <div class="card-tabs"><?= AdminMenu::widget(); ?></div>
    
    <?= $content; ?>
</div>
