<?php

use humhub\libs\Html;
use humhub\modules\legal\assets\Assets;
use humhub\modules\legal\widgets\Content;

Assets::register($this);

/* @var $page \humhub\modules\legal\models\Page */
?>

<div id="ccMessageText" style="display:none">
    <div style="max-height:300px;overflow:auto">
        <?= Content::widget(['content' => $page->content]) ?>
    </div>
</div>


<script <?= Html::nonce() ?>>
    window.addEventListener("load", function () {
        window.cookieconsent.initialise({
            "showLink": false,
            "theme": "classic",
            "position": "bottom-right",
            "content": {
                "message": $('#ccMessageText').html(),
                "dismiss": "<?= $page->title; ?>"
            }
        })
    });
</script>
