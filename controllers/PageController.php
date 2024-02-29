<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\controllers;

use humhub\components\access\ControllerAccess;
use humhub\components\Controller;
use humhub\modules\legal\models\Page;
use humhub\modules\legal\models\RegistrationChecks;
use humhub\modules\legal\Module;
use Yii;
use yii\web\HttpException;

/**
 * Class PageController
 *
 * @property Module $module
 * @package humhub\modules\legal\controllers
 */
class PageController extends Controller
{
    /**
     * @inheritDoc
     */
    public $access = ControllerAccess::class;

    /**
     * @inheritDoc
     */
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            $this->layout = '@user/views/layouts/main';
            $this->subLayout = '@legal/views/page/layout_login';
        } else {
            $this->subLayout = '@legal/views/page/layout_standard';
        }

        return parent::beforeAction($action);

    }

    /**
     * @param $pageKey
     * @return string
     * @throws HttpException
     */
    public function actionView($pageKey)
    {
        $page = Page::getPage($pageKey);
        if ($page === null || !$this->module->isPageEnabled($pageKey)) {
            throw new HttpException('404', 'Could not find page!');
        }

        return $this->render('view', [
            'page' => $page,
            'canManagePages' => $this->canManagePages()
        ]);
    }

    /**
     * @return string
     * @throws HttpException
     */
    public function actionConfirm()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegistrationChecks(['user' => Yii::$app->user->getIdentity()]);
        if ($model->showTermsCheck()) {
            $model->restrictToSettingKey = RegistrationChecks::SETTING_KEY_TERMS;
            $page = Page::getPage(Page::PAGE_KEY_TERMS);
        }
        elseif ($model->showPrivacyCheck()) {
            $model->restrictToSettingKey = RegistrationChecks::SETTING_KEY_PRIVACY;
            $page = Page::getPage(Page::PAGE_KEY_PRIVACY_PROTECTION);
        }
        if (!isset($page) || $page === null) {
            throw new HttpException('404', 'Could not find page!');
        }

        $this->layout = '@user/views/layouts/main';
        $this->subLayout = '@legal/views/page/layout_login';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->user->getReturnUrl()) {
                return $this->redirect(Yii::$app->user->getReturnUrl());
            }
            return $this->goHome();
        }

        return $this->render('confirm', [
            'page' => $page,
            'model' => $model,
            'module' => $this->module
        ]);
    }

    /**
     * @return string
     * @throws HttpException
     */
    public function actionUpdate()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $page = Page::getPage(Page::PAGE_KEY_LEGAL_UPDATE);
        if ($page === null || !$this->module->isPageEnabled(Page::PAGE_KEY_LEGAL_UPDATE)) {
            throw new HttpException('404', 'Could not find page!');
        }

        $this->layout = '@user/views/layouts/main';
        $this->subLayout = '@legal/views/page/layout_login';

        $model = new RegistrationChecks(['user' => Yii::$app->user->getIdentity()]);

        if (!$model->hasOpenCheck()) {
            return $this->goHome();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->goHome();
        }

        return $this->render('update', [
            'page' => $page,
            'model' => $model,
            'module' => $this->module
        ]);
    }

    /**
     * @return bool can Manage pages
     */
    public function canManagePages()
    {
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isSystemAdmin()) {
            return true;
        }

        return false;
    }

}
