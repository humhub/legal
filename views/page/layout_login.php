<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\libs\Html;

/* @var $this \humhub\components\View */

?>

<?php $this->beginContent('@user/views/layouts/main.php') ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?= Html::a('<i class="fa fa-arrow-left"></i> Go back to login', ['/'], ['class' => 'btn btn-default pull-right']); ?>
                <div class="text-center">
                    <?= humhub\widgets\SiteLogo::widget(['place' => 'login']); ?>
                    <br>
                </div>

                <br/><br/><br/>
                <?= $content; ?>
            </div>
        </div>
    </div>
<?php $this->endContent(); ?>