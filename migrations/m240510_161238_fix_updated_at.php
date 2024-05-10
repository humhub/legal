<?php

use yii\db\Migration;

/**
 * Class m240510_161238_fix_updated_at
 */
class m240510_161238_fix_updated_at extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->renameColumn('legal_page', 'last_update', 'updated_at');
        $this->alterColumn('legal_page', 'updated_at', 'datetime');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->renameColumn('legal_page', 'updated_at', 'last_update');
        $this->alterColumn('legal_page', 'last_update', 'int');
    }
}
