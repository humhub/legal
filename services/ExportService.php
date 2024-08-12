<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\services;

use humhub\modules\file\libs\FileHelper;
use humhub\modules\legal\jobs\GeneratePackage;
use humhub\modules\legal\Module;
use humhub\modules\queue\helpers\QueueHelper;
use humhub\modules\rest\definitions\UserDefinitions;
use humhub\modules\user\models\User;
use Yii;
use yii\base\Exception;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use ZipArchive;

class ExportService
{
    public const PACKAGE_TIME = 'legal.packageTime';
    public const PACKAGE_ALIAS = '@runtime/legal';

    private ?User $user = null;

    /**
     * @param User|int|null $user
     */
    public function __construct($user = null)
    {
        if (is_int($user)) {
            $this->user = User::findOne(['id' => $user]);
        } elseif ($user instanceof User) {
            $this->user = $user;
        }

        if (!$this->user instanceof User) {
            if (Yii::$app->user->isGuest) {
                throw new ForbiddenHttpException('User must be provided for Legal module export data!');
            }
            $this->user = Yii::$app->user->getIdentity();
        }
    }

    public function requestPackage(): bool
    {
        return Yii::$app->queue->push(new GeneratePackage(['user_id' => $this->user->id])) !== null;
    }

    public function generatePackage(): bool
    {
        $exportPath = Yii::getAlias(self::PACKAGE_ALIAS);
        if (!is_dir($exportPath)) {
            try {
                if (!FileHelper::createDirectory($exportPath)) {
                    return false;
                }
            } catch (Exception $e) {
                Yii::error('Cannot create a folder for legal module user export data! ' . $e->getMessage(), 'legal');
                return false;
            }
        }

        try {
            if (file_exists($this->getPackagePath())) {
                unlink($this->getPackagePath());
            }

            $archive = new ZipArchive();
            if (!$archive->open($this->getPackagePath(), ZipArchive::CREATE)) {
                throw new Exception('Error on creating of ZIP archive!');
            }

            foreach ($this->getDataFiles() as $name => $fileContent) {
                $archive->addFromString('files/' . $name . '.json', json_encode($fileContent));
            }

            $archive->close();
        } catch (Exception $e) {
            Yii::error('Cannot generate ZIP archive for legal module user export data! ' . $e->getMessage(), 'legal');
            return false;
        }

        $this->user->settings->set(self::PACKAGE_TIME, time());

        return true;
    }

    public function downloadPackage(): ?Response
    {
        return file_exists($this->getPackagePath())
            ? Yii::$app->response->sendContentAsFile(file_get_contents($this->getPackagePath()), 'legal-user-data.zip')
            : null;
    }

    public function deletePackage(): bool
    {
        $this->user->settings->delete(self::PACKAGE_TIME);

        if (file_exists($this->getPackagePath())) {
            unlink($this->getPackagePath());
        }

        return true;
    }

    public function isExporting(): bool
    {
        return QueueHelper::isQueued(new GeneratePackage(['user_id' => $this->user->id]));
    }

    public function hasPackage(): bool
    {
        return $this->user->settings->get(self::PACKAGE_TIME) > 0 &&
            file_exists($this->getPackagePath());
    }

    public function getModule(): Module
    {
        return Yii::$app->getModule('legal');
    }

    public function getPackagePath(): string
    {
        return Yii::getAlias(self::PACKAGE_ALIAS . DIRECTORY_SEPARATOR . $this->user->id . '.zip');
    }

    private function getDataFiles(): array
    {
        return $this->getModule()->isEnabledExportUserData()
            ? UserDefinitions::getAllUserData($this->user)
            : [];
    }
}
