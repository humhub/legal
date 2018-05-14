<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

/* @var $this \humhub\components\View */
/* @var $pages Page[] */
/* @var $languages array */
/* @var $defaultLanguage string */

?>
<?php

use humhub\libs\Html;
use humhub\modules\legal\models\Page;
use humhub\modules\legal\models\RegistrationChecks;
use humhub\widgets\MarkdownField;
use yii\bootstrap\ActiveForm;

?>


<?php $this->beginContent('@legal/views/admin/layout.php') ?>
<div class="panel-body">
    <?php $form = ActiveForm::begin(['id' => 'configure-form', 'enableClientValidation' => false, 'enableClientScript' => false]); ?>
    <br/>
    <?php if ($pageKey === Page::PAGE_KEY_LEGAL_UPDATE): ?>
        <p><?= Yii::t('LegalModule.base', 'This box is shown when an existing user has to (re-) accept changes to Terms &amp; Conditions and/or the Privacy protection.'); ?></p>
    <?php elseif ($pageKey === Page::PAGE_KEY_PRIVACY_PROTECTION): ?>
        <p><?= Yii::t('LegalModule.base', 'This page is added to the footer navigation and should shows your privacy protection statement.'); ?></p>
    <?php elseif ($pageKey === Page::PAGE_KEY_TERMS): ?>
        <p><?= Yii::t('LegalModule.base', 'This page is added to the footer navigation and should contains your terms and conditions.'); ?></p>
    <?php elseif ($pageKey === Page::PAGE_KEY_IMPRINT): ?>
        <p><?= Yii::t('LegalModule.base', 'This page is added to the footer navigation and shows your imprint.'); ?></p>
    <?php endif; ?>
    <br/>

    <div class="pull-right">
        <strong><?= Yii::t('LegalModule.base', 'Page language:'); ?></strong>
        <?= Html::dropDownList('lang', $defaultLanguage, $languages, ['class' => 'form-input', 'data-ui-select2' => '', 'id' => 'pageLangSelector']); ?>
    </div>

    <br/>
    <br/>
    <br/>

    <?php foreach ($languages as $languageKey => $languageTitle): ?>
        <div id="page_<?= $languageKey ?>" class="page_language" style="display:none">
            <?= $form->field($pages[$languageKey], '[' . $languageKey . ']title')->textInput(); ?>
            <?= $form->field($pages[$languageKey], '[' . $languageKey . ']content')->widget(MarkdownField::class, ['filesInputName' => 'PageFiles[' . $languageKey . ']', 'rows' => 10]); ?>
        </div>
    <?php endforeach; ?>

    <?php if ($pageKey === Page::PAGE_KEY_PRIVACY_PROTECTION): ?>
        <?= Html::a(Yii::t('LegalModule.base', 'Reset already accepted'), ['/legal/admin/reset', 'key' => RegistrationChecks::SETTING_KEY_PRIVACY], ['class' => 'btn btn-danger btn-sm pull-right', 'data-confirm' => Yii::t('LegalModule.base', 'Are you really sure? Please save changes before proceed!')]); ?>
    <?php elseif ($pageKey === Page::PAGE_KEY_TERMS): ?>
        <?= Html::a(Yii::t('LegalModule.base', 'Reset already accepted'), ['/legal/admin/reset', 'key' => RegistrationChecks::SETTING_KEY_TERMS], ['class' => 'btn btn-danger btn-sm pull-right', 'data-confirm' => Yii::t('LegalModule.base', 'Are you really sure? Please save changes before proceed!')]); ?>
    <?php endif; ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('base', 'Save'), ['class' => 'btn btn-primary', 'data-ui-loader' => '']) ?>
    </div>

    <?php ActiveForm::end(); ?>


    <script>
        showLanguage();
        $("#pageLangSelector").select2().on("select2:select", function (e) {
            showLanguage();
        });

        function showLanguage() {
            curLang = $('#pageLangSelector').val();
            $('.page_language').hide();
            $('#page_' + curLang).show();
        }
    </script>

</div>
<?php $this->endContent() ?>

