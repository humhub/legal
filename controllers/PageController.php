<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\controllers;

use humhub\components\Controller;
use humhub\modules\legal\models\Page;
use humhub\modules\legal\Module;
use Yii;


/**
 * Class PageController
 *
 * @property Module $module
 * @package humhub\modules\legal\controllers
 */
class PageController extends Controller
{

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (Yii::$app->user->isGuest) {
                $this->layout = '@user/views/layouts/main';
                $this->subLayout = '@legal/views/page/layout_login';
            } else {
                $this->subLayout = '@legal/views/page/layout_standard';
            }
            return true;
        }

        return false;
    }


    /**
     * @param $pageKey
     * @return string
     * @throws \HttpException
     */
    public function actionView($pageKey)
    {
        $page = Page::getPage($pageKey);
        if ($page === null) {
            throw new \HttpException('404', 'Could not find page!');
        }

        return $this->render('view', [
            'page' => $page,
            'canManagePages' => $this->canManagePages()
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