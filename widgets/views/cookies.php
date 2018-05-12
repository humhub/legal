<?php

use humhub\modules\legal\assets\Assets;
use humhub\widgets\MarkdownView;

Assets::register($this);

?>

<div id="ccMessageText" style="display:none">
    <?= MarkdownView::widget(['markdown' => $page->content]); ?>
</div>


<script>
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
