<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\components\View;
use humhub\widgets\bootstrap\Button;
use humhub\widgets\SiteLogo;

/* @var $this View */
/* @var $content string */
?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="text-center">
                <?= SiteLogo::widget(['place' => 'login']); ?>
                <br>
            </div>

            <?php if ($this->context->action->id != 'update'): ?>
                <?= Button::light(Yii::t('LegalModule.base', 'Go back'))
                    ->icon('arrow-left')
                    ->link(['/'])
                    ->pjax(false)
                    ->right() ?>
            <?php endif; ?>

            <br/><br/><br/>
            <?= $content; ?>
        </div>
    </div>
</div>
