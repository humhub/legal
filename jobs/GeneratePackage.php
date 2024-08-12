<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\jobs;

use humhub\modules\legal\services\ExportService;
use humhub\modules\queue\ActiveJob;
use humhub\modules\queue\interfaces\ExclusiveJobInterface;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\ForbiddenHttpException;

class GeneratePackage extends ActiveJob implements ExclusiveJobInterface
{
    public $user_id;

    /**
     * @inhertidoc
     */
    public function getExclusiveJobId()
    {
        if (empty($this->user_id)) {
            throw new InvalidArgumentException('User id cannot be empty!');
        }

        return 'legal.generatePackage.' . $this->user_id;
    }

    /**
     * @inhertidoc
     */
    public function run()
    {
        try {
            if (!(new ExportService($this->user_id))->generatePackage()) {
                Yii::error('Cannot generate data package for user #' . $this->user_id . '!', 'legal');
            }
        } catch (ForbiddenHttpException $e) {
            Yii::error('Cannot generate data package for user #' . $this->user_id . '! ' . $e->getMessage(), 'legal');
        }
    }
}
