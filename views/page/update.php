<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\legal\widgets\Content;
use humhub\modules\user\services\UserSourceService;
use humhub\widgets\bootstrap\Button;
use humhub\widgets\bootstrap\Link;
use humhub\widgets\form\ActiveForm;

/* @var $this \humhub\components\View */
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
        <div class="mb-3">
            <?= Button::success(Yii::t('LegalModule.base', 'Accept'))
                ->icon('check')
                ->submit() ?>
            <?= Link::danger(Yii::t('LegalModule.base', 'Logout'))
                ->icon('sign-out')
                ->post(['/user/auth/logout'])
                ->right() ?>
        </div>

        <div class="clearfix">
            <div class="float-end">
                <?php if (UserSourceService::getForUser()->canDeleteAccount()): ?>
                    <?= Link::to(Yii::t('LegalModule.base', 'Delete my account including my personal data'), ['/user/account/delete'], false)->right() ?>
                <?php endif; ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
