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
use humhub\widgets\MarkdownField;
use yii\bootstrap\ActiveForm;

?>


<?php $this->beginContent('@legal/views/admin/layout.php') ?>
<div class="panel-body">
    <?php $form = ActiveForm::begin(['id' => 'configure-form', 'enableClientValidation' => false, 'enableClientScript' => false]); ?>

    <br/>
    <p><?= Yii::t('LegalModule.base', 'Adds an overlay which informs the users about the use of cookies. You can add a different text for every available language.'); ?></p>
    <br/>

    <div class="pull-right">
        <strong><?= Yii::t('LegalModule.base', 'Box language:'); ?></strong>
        <?= Html::dropDownList('lang', $defaultLanguage, $languages, ['class' => 'form-input', 'data-ui-select2' => '', 'id' => 'pageLangSelector']); ?>
    </div>

    <br/>
    <br/>
    <br/>
    <?php foreach ($languages as $languageKey => $languageTitle): ?>
        <div id="page_<?= $languageKey ?>" class="page_language" style="display:none">
            <?= $form->field($pages[$languageKey], '[' . $languageKey . ']content')->widget(MarkdownField::class, ['filesInputName' => 'PageFiles[' . $languageKey . ']', 'rows' => 5])->label(Yii::t('LegalModule.base', 'Box content')); ?>
            <?= $form->field($pages[$languageKey], '[' . $languageKey . ']title')->textInput()->label(Yii::t('LegalModule.base', 'Accept button label')); ?>
        </div>
    <?php endforeach; ?>

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

