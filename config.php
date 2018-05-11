<?php /** @noinspection MissedFieldInspection */

use humhub\widgets\FooterMenu;

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

return [
    'id' => 'legal',
    'class' => 'humhub\modules\legal\Module',
    'namespace' => 'humhub\modules\legal',
    'events' => [
        ['class' => FooterMenu::className(), 'event' => FooterMenu::EVENT_INIT, 'callback' => ['humhub\modules\legal\Events', 'onFooterMenuInit']],
    ]
];
?>