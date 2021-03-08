<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\libs\Html;
use humhub\modules\legal\models\Page;
use humhub\modules\ui\form\widgets\ActiveForm;
use humhub\modules\content\widgets\richtext\RichText;

/* @var $this \humhub\modules\ui\view\components\View */
/* @var $page \humhub\modules\legal\models\Page */
/* @var $model \humhub\modules\legal\models\RegistrationChecks */
/* @var $module \humhub\modules\legal\Module */
?>

<div class="panel">
    <div class="panel-heading">
        <?= $page->title; ?>
    </div>
    <div class="panel-body">
        <?= RichText::output($page->content); ?>

        <br/>
        <?php $form = ActiveForm::begin(['id' => 'configure-form', 'enableClientValidation' => false, 'enableClientScript' => false]); ?>

        <?php if ($page->page_key === Page::PAGE_KEY_TERMS): ?>
            <?= $form->field($model, 'termsCheck')->checkbox()->hint(false); ?>
        <?php endif; ?>

        <?php if ($page->page_key === Page::PAGE_KEY_PRIVACY_PROTECTION): ?>
            <?= $form->field($model, 'dataPrivacyCheck')->checkbox()->hint(false); ?>
        <?php endif; ?>

        <br/>
        <div class="form-group">
            <?= Html::submitButton('<i class="fa fa-check"></i>&nbsp;&nbsp;' . Yii::t('LegalModule.base', 'Accept'), ['class' => 'btn btn-success', 'data-ui-loader' => '']) ?>
            <?= Html::a('<i class="fa fa-sign-out"></i>&nbsp;&nbsp;' . Yii::t('LegalModule.base', 'Logout'), ['/user/auth/logout'], ['class' => 'btn btn-danger pull-right', 'data-ui-loader' => '']) ?>
        </div>

        <div class="pull-right">
            <?php if (Yii::$app->user->canDeleteAccount()): ?>
                <?= Html::a(Yii::t('LegalModule.base', 'Delete my account including my personal data'), ['/user/account/delete'], ['class' => 'pull-right', 'data-pjax-prevent' => '']) ?>
            <?php endif; ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

