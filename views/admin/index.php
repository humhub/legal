<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

/* @var $this \humhub\components\View */
/* @var $module \humhub\modules\legal\Module */
?>
<?php

use humhub\modules\legal\models\Page;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$enabledPages = [];
foreach (Page::getPages() as $key => $title) {
    $disabledWarning = (Page::getPage($key, $module->getDefaultLanguage()) === null) ? Yii::t('LegalModule.base', '(Disabled - please add content in default language!)') : '';
    $enabledPages[$key] = $title . ' ' . $disabledWarning;
}

?>


<?php $this->beginContent('@legal/views/admin/layout.php') ?>
<div class="panel-body">


    <?php $form = ActiveForm::begin(['id' => 'configure-form', 'enableClientValidation' => false, 'enableClientScript' => false]); ?>

    <?= $form->field($model, 'enabledPages')->checkboxList($enabledPages); ?>
    <?= $form->field($model, 'defaultLanguage')->dropDownList(Yii::$app->i18n->getAllowedLanguages(), ['data-ui-select2' => '']); ?>

    <?= $form->field($model, 'showAgeCheck')->checkbox(); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('base', 'Save'), ['class' => 'btn btn-primary', 'data-ui-loader' => '']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>
<?php $this->endContent() ?>

