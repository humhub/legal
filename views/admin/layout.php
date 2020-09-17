<?php

use humhub\modules\legal\widgets\AdminMenu;

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('LegalModule.base', '<strong>Legal</strong> module - administration'); ?>
    </div>
    <?= AdminMenu::widget(); ?>
    
    <?= $content; ?>
</div>
