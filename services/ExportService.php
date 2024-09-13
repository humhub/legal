<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\services;

use humhub\modules\content\components\ContentContainerSettingsManager;
use humhub\modules\legal\events\UserDataCollectionEvent;
use humhub\modules\legal\jobs\GeneratePackage;
use humhub\modules\legal\Module;
use humhub\modules\queue\helpers\QueueHelper;
use humhub\modules\user\models\User;
use Yii;
use yii\base\Exception;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class ExportService
{
    public const EVENT_COLLECT_USER_DATA = 'collectUserData';
    public const PACKAGE_TIME = 'packageTime';
    public const PACKAGE_ALIAS = '@runtime/legal';

    public ?User $user = null;

    /**
     * @param User|int|null $user
     * @throws ForbiddenHttpException|\Throwable
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

    /**
     * @param User|int|null $user
     * @return self
     * @throws ForbiddenHttpException|\Throwable
     */
    public static function instance($user = null): self
    {
        return new self($user);
    }

    public function requestPackage(): bool
    {
        return Yii::$app->queue->push(new GeneratePackage(['user_id' => $this->user->id])) !== null;
    }

    public function generatePackage(): bool
    {
        try {
            // Create ZIP archive by the event
            UserDataCollectionEvent::trigger(self::class, self::EVENT_COLLECT_USER_DATA, new UserDataCollectionEvent(['user' => $this->user]));
        } catch (Exception $e) {
            Yii::error('Cannot generate ZIP archive for legal module user export data! ' . $e->getMessage(), 'legal');
            return false;
        }

        $this->getSettings()->set(self::PACKAGE_TIME, time());

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
        $this->getSettings()->delete(self::PACKAGE_TIME);

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
        return $this->getSettings()->get(self::PACKAGE_TIME) > 0 &&
            file_exists($this->getPackagePath());
    }

    public function getModule(): Module
    {
        return Yii::$app->getModule('legal');
    }

    public function getSettings(): ContentContainerSettingsManager
    {
        return $this->getModule()->settings->user($this->user);
    }

    public function getPackagePath(): string
    {
        return Yii::getAlias(self::PACKAGE_ALIAS . DIRECTORY_SEPARATOR . $this->user->id . '.zip');
    }

    public function getPackageDayLeft(): int
    {
        if (!$this->hasPackage()) {
            return 0;
        }

        return ceil($this->getModule()->getExportUserDays() - ((time() - $this->getSettings()->get(self::PACKAGE_TIME)) / 86400));
    }
}
