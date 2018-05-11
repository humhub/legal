<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\widgets\FooterMenu;

/* @var $this \humhub\components\View */

?>

<?php $this->beginContent('@humhub/views/layouts/main.php') ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?= $content; ?>
            </div>
        </div>
        <?= FooterMenu::widget(); ?>
    </div>
<?php $this->endContent(); ?>