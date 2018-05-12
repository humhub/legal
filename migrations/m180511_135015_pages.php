<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use yii\db\Migration;

class m180511_135015_pages extends Migration
{
    public function safeUp()
    {
        $this->createTable('legal_page', [
            'id' => $this->primaryKey(),
            'page_key' => $this->string(15)->notNull(),
            'language' => $this->string(10)->notNull(),
            'title' => $this->string()->notNull(),
            'content' => $this->text()->notNull(),
            'last_update' => $this->integer(),
        ]);

        $this->createIndex('legal_page_uni', 'legal_page', ['page_key', 'language'], true);
    }

    public function safeDown()
    {
        echo "m180511_135015_pages cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180511_135015_pages cannot be reverted.\n";

        return false;
    }
    */
}
