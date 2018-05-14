<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\libs\Html;

/* @var $this \humhub\components\View */

?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <?= humhub\widgets\SiteLogo::widget(['place' => 'login']); ?>
                <br>
            </div>

            <?php if ($this->context->action->id != 'update'): ?>
                <?= Html::a('<i class="fa fa-arrow-left"></i>&nbsp;&nbsp;' . Yii::t('LegalModule.base', 'Go back'), ['/'], ['class' => 'btn btn-default pull-right']); ?>
            <?php endif; ?>

            <br/><br/><br/>
            <?= $content; ?>
        </div>
    </div>
</div>

