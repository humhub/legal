<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\events;

use humhub\modules\file\libs\FileHelper;
use humhub\modules\legal\services\ExportService;
use humhub\modules\user\events\UserEvent;
use Yii;
use yii\base\Exception;
use ZipArchive;

class UserDataCollectionEvent extends UserEvent
{
    /**
     * Array with export data:
     *  - Key is a category name which will be used to name JSON file in ZIP archive
     *  - Value is an array with any data
     * @var array $exportData
     */
    public array $exportData = [];

    /**
     * Array with export files:
     *  - Key is a file name which will be used in ZIP archive
     *  - Value is a file path where it is really located on the server disk
     * @var array $exportFiles
     */
    public array $exportFiles = [];

    /**
     * Add data to export
     *
     * @param string $category Category name which will be used to name JSON file in ZIP archive
     * @param array $data Array with any data
     */
    public function addExportData(string $category, array $data)
    {
        $index = $this->getUniqueArrayIndex($this->exportData, $category);
        $this->exportData[$index] = $data;
    }

    /**
     * Add a file to export
     *
     * @param string $fileName File name which will be used in ZIP archive
     * @param string $sourceFilePath File path where it is really located on the server disk
     */
    public function addExportFile(string $fileName, string $sourceFilePath)
    {
        $index = $this->getUniqueArrayIndex($this->exportFiles, $fileName);
        $this->exportFiles[$index] = $sourceFilePath;
    }

    private function getUniqueArrayIndex(array $array, string $index): string
    {
        $origIndex = $index;
        $i = 1;
        while (isset($array[$index])) {
            $index = preg_replace('/(^.+?)(\.[a-z0-9]+)?$/i', '$1-' . (++$i) . '$2', $origIndex);
        }

        return $index;
    }

    /**
     * @inheritdoc
     * @throws Exception
     * @throws \Throwable
     */
    public static function trigger($class, $name, $event = null)
    {
        parent::trigger($class, $name, $event);

        if ($event instanceof self) {
            $event->createPackage();
        }
    }

    /**
     * Create a package with data json files and with uploaded files
     *
     * @throws Exception
     * @throws \Throwable
     * @throws \yii\web\ForbiddenHttpException
     */
    private function createPackage()
    {
        if (!Yii::$app->getModule('legal')->isEnabledExportUserData()) {
            return;
        }

        $packagePath = ExportService::instance($this->user)->getPackagePath();

        $exportDirPath = dirname($packagePath);
        if (!is_dir($exportDirPath)) {
            try {
                if (!FileHelper::createDirectory($exportDirPath)) {
                    return;
                }
            } catch (Exception $e) {
                Yii::error('Cannot create a folder for legal module user export data! ' . $e->getMessage(), 'legal');
                return;
            }
        }

        if (file_exists($packagePath)) {
            unlink($packagePath);
        }

        $archive = new ZipArchive();
        if (!$archive->open($packagePath, ZipArchive::CREATE)) {
            throw new Exception('Error on creating of ZIP archive!');
        }

        foreach ($this->exportData as $category => $data) {
            $archive->addFromString('files/' . $category . '.json', json_encode($data));
        }

        foreach ($this->exportFiles as $fileName => $sourceFilePath) {
            $archive->addFile($sourceFilePath, 'uploads/' . $fileName);
        }

        $archive->close();
    }
}
