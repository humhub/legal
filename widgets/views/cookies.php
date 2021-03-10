<?php

use humhub\libs\Html;
use humhub\modules\legal\assets\Assets;
use humhub\modules\content\widgets\richtext\RichText;

Assets::register($this);

/* @var $page \humhub\modules\legal\models\Page */
?>

<div id="ccMessageText" style="display:none">
    <div style="max-height:300px;overflow:auto">
        <?= RichText::output($page->content); ?>
    </div>

</div>


<script <?= Html::nonce() ?>>
    window.addEventListener("load", function () {
        window.cookieconsent.initialise({
            "palette": {
                "popup": {
                    "background": "<?= $this->theme->variable('primary'); ?>",
                    "text": "<?= $this->theme->variable('text-color-contrast') ?>"
                },
                "button": {
                    "background": "<?= $this->theme->variable('success'); ?>",
                    "text": "<?= $this->theme->variable('text-color-contrast') ?>"
                }
            },
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
