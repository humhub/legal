<?php

use yii\db\Migration;

/**
 * Class m220223_130241_alter_column_content_to_longtext
 */
class m220223_130241_alter_column_content_to_longtext extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->alterColumn('legal_page', 'content', $this->getDb()->getSchema()->createColumnSchemaBuilder('longtext'));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->alterColumn('legal_page', 'content', $this->text());
    }
}
