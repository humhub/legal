<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\controllers;

use humhub\components\Controller;
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
#            $this->layout = '@user/views/layouts/main';
            if (Yii::$app->user->isGuest) {
                $this->layout = '@legal/views/page/layout_login';
            } else {
                $this->layout = '@legal/views/page/layout_standard';
            }
            return true;
        }

        return false;
    }


    public function actionView()
    {
        return $this->render('view');
    }

}