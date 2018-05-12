<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal;

use humhub\modules\legal\models\Page;
use Yii;
use yii\helpers\Url;


/**
 * Description of WikiEvents
 *
 * @author luke
 */
class Events
{

    public function onFooterMenuInit($event)
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('legal');

        $sortOrder = 100;
        foreach (Page::getPages() as $pageKey => $title) {
            $page = Page::getPage($pageKey);
            if ($page !== null) {
                $sortOrder += 10;
                $event->sender->addItem(array(
                    'label' => $page->title,
                    'url' => Url::to(['/legal/page/view', 'pageKey' => $pageKey]),
                    'sortOrder' => $sortOrder,
                ));
            }
        }

    }


}
