<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal;

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

        $event->sender->addItem(array(
            'label' => Yii::t('base', 'Data Protection'),
            'url' => Url::toRoute('/legal/page/view'),
            'sortOrder' => 100,
        ));
        $event->sender->addItem(array(
            'label' => Yii::t('base', 'Terms and Conditions'),
            'url' => Url::toRoute('/legal/page/view'),
            'sortOrder' => 100,
        ));
        $event->sender->addItem(array(
            'label' => Yii::t('base', 'Imprint'),
            'url' => Url::toRoute('/legal/page/view'),
            'sortOrder' => 200,
        ));
        $event->sender->addItem(array(
            'label' => Yii::t('base', 'Cookies'),
            'url' => Url::toRoute('/legal/page/view'),
            'sortOrder' => 300,
        ));

    }


}
