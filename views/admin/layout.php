<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('LegalModule.base', '<strong>Legal</strong> module - administration'); ?>
    </div>
    <?= humhub\modules\legal\widgets\AdminMenu::widget(); ?>
    
    <?= $content; ?>
</div>
