<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal;

use humhub\modules\legal\models\Page;
use humhub\modules\legal\widgets\CookieNote;
use humhub\widgets\LayoutAddons;
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
            if ($pageKey === Page::PAGE_KEY_COOKIE_NOTICE) {
                // Cookie notice is not a navigation page
                continue;
            }

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

    public function onLayoutAddonInit($event)
    {
        /** @var LayoutAddons $layoutAddons */
        $layoutAddons = $event->sender;
        $layoutAddons->addWidget(CookieNote::class);
    }
}
