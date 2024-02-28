<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\libs\Html;
use humhub\modules\legal\widgets\Content;
use humhub\modules\ui\form\widgets\ActiveForm;

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
        <?= Content::widget(['content' => $page->content]) ?>

        <br/>
        <?php $form = ActiveForm::begin(['id' => 'configure-form', 'enableClientValidation' => false, 'enableClientScript' => false]); ?>

        <?php if ($model->showAgeCheck()): ?>
            <?= $form->field($model, 'ageCheck')->checkbox(); ?>
        <?php endif; ?>

        <?php if ($model->showPrivacyCheck()): ?>
            <?= $form->field($model, 'dataPrivacyCheck')->checkbox(); ?>
        <?php endif; ?>

        <?php if ($model->showTermsCheck()): ?>
            <?= $form->field($model, 'termsCheck')->checkbox(); ?>
        <?php endif; ?>

        <br/>
        <div class="form-group">
            <?= Html::submitButton('<i class="fa fa-check"></i>&nbsp;&nbsp;' . Yii::t('LegalModule.base', 'Accept'), ['class' => 'btn btn-success', 'data-ui-loader' => '']) ?>
            <?= Html::a('<i class="fa fa-sign-out"></i>&nbsp;&nbsp;' . Yii::t('LegalModule.base', 'Logout'), ['/user/auth/logout'], ['data-method' => 'POST', 'class' => 'btn btn-danger pull-right', 'data-ui-loader' => '']) ?>
        </div>

        <div class="pull-right">
            <?php if (Yii::$app->user->getAuthClientUserService()->canDeleteAccount()): ?>
                <?= Html::a(Yii::t('LegalModule.base', 'Delete my account including my personal data'), ['/user/account/delete'], ['class' => 'pull-right', 'data-pjax-prevent' => '']) ?>
            <?php endif; ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

