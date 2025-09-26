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
/* @var $pageKey string */

?>
<?php

use humhub\helpers\Html;
use humhub\modules\content\widgets\richtext\RichTextField;
use humhub\modules\legal\models\Page;
use humhub\widgets\form\ActiveForm;

?>


<?php $this->beginContent('@legal/views/admin/layout.php') ?>
<div class="panel-body">
    <?php $form = ActiveForm::begin(['id' => 'configure-form', 'enableClientValidation' => false, 'enableClientScript' => false]); ?>

    <br/>
    <p><?= Yii::t('LegalModule.base', 'Adds an overlay which informs the users about the use of cookies. You can add a different text for every available language.'); ?></p>
    <br/>

    <div class="float-end">
        <strong><?= Yii::t('LegalModule.base', 'Box language:'); ?></strong>
        <?= Html::dropDownList('lang', $defaultLanguage, $languages, ['class' => 'form-input', 'data-ui-select2' => '', 'id' => 'pageLangSelector']); ?>
    </div>

    <br/>
    <br/>
    <br/>
    <?php foreach ($languages as $languageKey => $languageTitle): ?>
        <div id="page_<?= $languageKey ?>" class="page_language d-none">
            <?= $form->field($pages[$languageKey], '[' . $languageKey . ']content')->widget(RichTextField::class, ['layout' => RichTextField::LAYOUT_BLOCK, 'pluginOptions' => ['maxHeight' => '200px']])->label(Yii::t('LegalModule.base', 'Box content')); ?>
            <?= $form->field($pages[$languageKey], '[' . $languageKey . ']title')->textInput()->label(Yii::t('LegalModule.base', 'Accept button label')); ?>
        </div>
    <?php endforeach; ?>

    <div class="mb-3">
        <?= Html::submitButton(Yii::t('base', 'Save'), ['class' => 'btn btn-primary', 'data-ui-loader' => '']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <script <?= Html::nonce() ?>>
        showLanguage();
        $("#pageLangSelector").select2().on("select2:select", function (e) {
            showLanguage();
        });

        function showLanguage() {
            const curLang = $('#pageLangSelector').val();
            $('.page_language').addClass('d-none');
            $('#page_' + curLang).removeClass('d-none');
        }
    </script>

</div>
<?php $this->endContent() ?>
