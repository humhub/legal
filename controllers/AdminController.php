<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\controllers;

use humhub\modules\admin\components\Controller;
use humhub\modules\content\models\ContentContainerSetting;
use humhub\modules\legal\models\ConfigureForm;
use humhub\modules\legal\models\Page;
use humhub\modules\legal\models\RegistrationChecks;
use humhub\modules\legal\Module;
use Yii;
use yii\web\HttpException;


/**
 * Class AdminController
 *
 * @property Module $module
 * @package humhub\modules\legal\controllers
 */
class AdminController extends Controller
{

    public function actionIndex()
    {
        $model = new ConfigureForm();
        $model->loadSettings();

        if ($model->load(Yii::$app->request->post()) && $model->saveSettings()) {
            $this->view->saved();
            return $this->redirect(['index']);
        }

        return $this->render('index', ['model' => $model, 'module' => $this->module]);
    }

    public function actionPage($pageKey)
    {
        $pages = $this->getPages($pageKey);

        $pageFiles = Yii::$app->request->post('PageFiles');
        if (!is_array($pageFiles)) {
            $pageFiles = [];
        }

        $saved = false;
        foreach ($pages as $page) {
            /** @var Page $page */
            if ($page->load(Yii::$app->request->post('Page'), $page->language)) {
                if (!$page->save()) {
                    print "not saved";
                }
                if (isset($pageFiles[$page->language])) {
                    $page->fileManager->attach($pageFiles[$page->language]);
                }
                $saved = true;
            }
        }

        if ($saved) {
            $this->view->saved();
            return $this->redirect(['page', 'pageKey' => $page->page_key, 'language' => $page->language]);
        }

        $languages = [];
        foreach (Yii::$app->i18n->getAllowedLanguages() as $lKey => $lTitle) {
            $languages[$lKey] = (($pages[$lKey]->isNewRecord) ? '' : '*') . $lTitle . ' (' . $lKey . ')';
        }

        $view = 'page';
        if ($pageKey == Page::PAGE_KEY_COOKIE_NOTICE) {
            $view = 'page_cookies';
        }


        return $this->render($view, [
            'pages' => $pages,
            'languages' => $languages,
            'defaultLanguage' => $this->module->getDefaultLanguage(),
            'pageKey' => $pageKey
        ]);
    }


    /**
     * @param $key
     * @return $this|void|\yii\web\Response
     * @throws HttpException
     */
    public function actionReset($key)
    {

        if (!in_array($key, [RegistrationChecks::SETTING_KEY_PRIVACY, RegistrationChecks::SETTING_KEY_TERMS])) {
            throw new HttpException(500, 'Invalid key!');
        }

        /** @var Module $module */
        $module = $this->module;
        $module->settings->delete($key);

        ContentContainerSetting::deleteAll(['module_id' => 'legal', 'name' => $key]);
        ContentContainerSetting::deleteAll(['module_id' => 'legal', 'name' => $key . 'Time']);

        $this->view->success(Yii::t('LegalModule.base', 'Reset successful!'));
        return $this->redirect(['index']);
    }


    /**
     * @param $pageKey
     * @return Page[]
     */
    protected function getPages($pageKey)
    {
        $pages = [];

        foreach (Yii::$app->i18n->getAllowedLanguages() as $langKey => $title) {
            $pages[$langKey] = Page::findOne(['page_key' => $pageKey, 'language' => $langKey]);
            if ($pages[$langKey] === null) {
                $pages[$langKey] = new Page(['page_key' => $pageKey, 'language' => $langKey]);
            }
        }

        return $pages;
    }

}