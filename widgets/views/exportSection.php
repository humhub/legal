<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\legal\services\ExportService;
use humhub\widgets\bootstrap\Button;

/* @var ExportService $service */
?>
<p class="text-body-secondary">
    <?php if ($service->hasPackage()) : ?>
        <?= Yii::t('LegalModule.base', 'Personal Data Package is available for download. The package will be deleted after {countDays} days.', [
            'countDays' => $service->getPackageDayLeft(),
        ]) ?>
    <?php elseif ($service->isExporting()) : ?>
        <?= Yii::t('LegalModule.base', 'Personal Data Package is being generated. Please check back later.') ?>
    <?php else : ?>
        <?= Yii::t('LegalModule.base', 'After clicking "Generate Package", the Personal Data Package will begin to generate. The package is in JSON format. Please note that collecting all of the data may take some time. While the package is being generated, you can continue using the app. Please check back later to see if the package is ready. The package will be deleted after {countDays} days.', [
            'countDays' => $service->getModule()->getExportUserDays(),
        ]) ?>
    <?php endif; ?>
</p>

<?php if ($service->hasPackage()) : ?>
    <?= Button::accent(Yii::t('LegalModule.base', 'Download Package'))
        ->icon('download')
        ->link(['/legal/export/download'])
        ->loader(false)
        ->sm() ?>
    <?= Button::danger(Yii::t('LegalModule.base', 'Delete Package'))
        ->icon('trash')
        ->link(['/legal/export/delete'])
        ->options(['data-method' => 'POST'])
        ->confirm()
        ->sm() ?>
<?php elseif ($service->isExporting()) : ?>
    <?= Button::light(Yii::t('LegalModule.base', 'Data Package is generating...'))
        ->icon('clock-o')
        ->options(['disabled' => true])
        ->sm() ?>
<?php else : ?>
    <?= Button::primary(Yii::t('LegalModule.base', 'Generate Package'))
        ->icon('arrow-down')
        ->link(['/legal/export/request'])
        ->options(['data-method' => 'POST'])
        ->sm() ?>
<?php endif; ?>
