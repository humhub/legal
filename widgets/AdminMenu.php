<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\widgets;

use humhub\helpers\ControllerHelper;
use humhub\modules\legal\models\Page;
use humhub\widgets\menu\MenuLink;
use humhub\widgets\menu\PillTabs;
use Yii;

class AdminMenu extends PillTabs
{
    /**
     * @inheritdoc
     */
    public string $variant = self::VARIANT_SECONDARY;

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
