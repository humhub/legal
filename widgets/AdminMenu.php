<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\widgets;

use humhub\helpers\ControllerHelper;
use humhub\modules\legal\models\Page;
use humhub\modules\ui\menu\MenuLink;
use humhub\modules\ui\menu\widgets\TabMenu;
use Yii;

class AdminMenu extends TabMenu
{
    public function init()
    {
        $this->addEntry(new MenuLink([
            'label' => Yii::t('LegalModule.base', 'Configuration'),
            'url' => ['/legal/admin'],
            'sortOrder' => 50,
            'isActive' => ControllerHelper::isActivePath('legal', 'admin', 'index'),
        ]));

        foreach (Page::getPages() as $key => $pageTitle) {
            $this->addEntry(new MenuLink([
                'label' => $pageTitle,
                'url' => ['/legal/admin/page', 'pageKey' => $key],
                'sortOrder' => 100,
                'isActive' => ControllerHelper::isActivePath('legal', 'admin', 'page', ['pageKey' => $key]),
            ]));
        }

        parent::init();
    }

}
