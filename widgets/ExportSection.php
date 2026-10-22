<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\widgets;

use humhub\modules\legal\Module;
use humhub\modules\legal\services\ExportService;
use humhub\modules\user\widgets\security\SecuritySection;
use Yii;

/**
 * Personal data export in the "My Data" tab of the security settings
 */
class ExportSection extends SecuritySection
{
    public function getTitle(): string
    {
        return Yii::t('LegalModule.base', 'Export personal data');
    }

    public function isVisible(): bool
    {
        /* @var Module $module */
        $module = Yii::$app->getModule('legal');

        return $module->isEnabledExportUserData();
    }

    protected function renderContent(): string
    {
        return $this->render('exportSection', [
            'service' => ExportService::instance(),
        ]);
    }
}
