<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\jobs;

use humhub\modules\content\models\ContentContainerSetting;
use humhub\modules\legal\Module;
use humhub\modules\legal\services\ExportService;
use humhub\modules\queue\LongRunningActiveJob;
use Yii;

class DeletePackages extends LongRunningActiveJob
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        /* @var Module $module */
        $module = Yii::$app->getModule('legal');

        $users = ContentContainerSetting::find()
            ->select('contentcontainer_id')
            ->where(['module_id' => 'user'])
            ->andWhere(['name' => ExportService::PACKAGE_TIME])
            ->andWhere(['<', 'value', time() - ($module->getExportUserDays() * 86400)])
            ->column();

        foreach ($users as $userId) {
            if (!ExportService::instance($userId)->deletePackage()) {
                Yii::error('Cannot delete data package for the user #' . $userId, 'legal');
            }
        }
    }
}
