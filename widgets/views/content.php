<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2022 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\content\widgets\richtext\RichText;
use humhub\modules\legal\assets\ContentAssets;
use humhub\modules\ui\icon\widgets\Icon;
use yii\helpers\Html;

/* @var \humhub\modules\ui\view\components\View $this */
/* @var string $content */
/* @var array $options */

ContentAssets::register($this);
$this->registerJsConfig('legal', [
    'externalLink' => [
        'prefix' => Icon::get('external-link')->asString() . ' ',
        'confirmTitle' => Yii::t('LegalModule.base', '<strong>External</strong> redirect'),
        'confirmText' => Yii::t('LegalModule.base', 'You are leaving this page and will be redirected to an external page'),
        'confirmButton' => Yii::t('LegalModule.base', 'Redirect'),
        'redirectAfter' => 5
    ]
]);
?>
<?= Html::beginTag('div', $options) ?>
    <?= RichText::output($content) ?>
<?= Html::endTag('div') ?>