<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\widgets\FooterMenu;

/* @var $this \humhub\components\View */

?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <?= $content; ?>
        </div>
    </div>
    <?= FooterMenu::widget(); ?>
</div>
