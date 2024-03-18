<?php

use humhub\modules\ui\form\widgets\ActiveForm;
use humhub\widgets\ModalButton;
use humhub\widgets\ModalDialog;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?php ModalDialog::begin(['header' => \Yii::t('LegalModule.base', '<strong>Export</strong> My Data')]); ?>
    <?php $form = ActiveForm::begin(); ?>
        <div class="modal-body">
            <div class="alert alert-info">
                <?= Yii::t('LegalModule.base', 'Below you can download your user-data as a JSON file.'); ?>
            </div>
            <?= Html::a(Yii::t('LegalModule.base', 'Download User Data'), ['download'], ['class' => 'btn btn-primary']); ?>
        </div>
        <div class="modal-footer">
            <?= ModalButton::cancel() ?>
        </div>
    <?php ActiveForm::end();?>
<?php ModalDialog::end(); ?>
