<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use yii\db\Migration;

class uninstall extends Migration
{
    public function up()
    {
        $this->dropTable('legal_page');
    }

    public function down()
    {
        echo "uninstall does not support migration down.\n";
        return false;
    }

}
