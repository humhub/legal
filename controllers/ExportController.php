<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\controllers;

use humhub\modules\legal\Module;
use humhub\modules\legal\services\ExportService;
use humhub\modules\user\components\BaseAccountController;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/* @property Module $module */
class ExportController extends BaseAccountController
{
    /**
     * @inheritdoc
     */
    protected function getAccessRules()
    {
        return array_merge(parent::getAccessRules(), [
            ['checkEnabledExportUserData'],
        ]);
    }

    public function checkEnabledExportUserData($rule, $access)
    {
        return $this->module->isEnabledExportUserData();
    }

    public function actionIndex()
    {
        return $this->render('index', [
            'service' => ExportService::instance(),
        ]);
    }

    public function actionRequest()
    {
        if (ExportService::instance()->requestPackage()) {
            $this->view->success(Yii::t('LegalModule.base', 'The exporting of your data has been started, please wait some time.'));
        } else {
            $this->view->error('Cannot start the exporting of your data, please try again.');
        }

        return $this->redirect(['index']);
    }

    public function actionDownload()
    {
        $package = ExportService::instance()->downloadPackage();

        if ($package === null) {
            throw new NotFoundHttpException();
        }

        return $package;
    }

    public function actionDelete()
    {
        if (ExportService::instance()->deletePackage()) {
            $this->view->success(Yii::t('LegalModule.base', 'The package has been deleted.'));
        } else {
            $this->view->error('Cannot delete the package, please try again.');
        }

        return $this->redirect(['index']);
    }
}
