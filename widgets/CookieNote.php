<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\widgets;

use humhub\components\Widget;
use humhub\modules\legal\models\Page;
use yii\web\HttpException;

/**
 * Class CookieNote
 * @package humhub\modules\legal\widgets
 */
class CookieNote extends Widget
{
    public function run()
    {
        $page = Page::getPage(Page::PAGE_KEY_COOKIE_NOTICE);
        if ($page === null) {
            return "";
        }

        return $this->render('cookies', ['page' => $page]);
    }
}
