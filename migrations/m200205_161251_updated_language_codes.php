<?php

use yii\db\Migration;

/**
 * Class m200205_161251_updated_language_codes
 */
class m200205_161251_updated_language_codes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->update('legal_page', ['language' => 'en-US'], ['language' => 'en']);
        $this->update('legal_page', ['language' => 'en-GB'], ['language' => 'en_gb']);
        $this->update('legal_page', ['language' => 'fa-IR'], ['language' => 'fa_ir']);
        $this->update('legal_page', ['language' => 'nb-NO'], ['language' => 'nb_no']);
        $this->update('legal_page', ['language' => 'nn-NO'], ['language' => 'nn_no']);
        $this->update('legal_page', ['language' => 'pt-BR'], ['language' => 'pt_br']);

        $this->update('setting', ['value' => 'en-US'], ['name' => 'defaultLanguage', 'value' => 'en', 'module_id' => 'legal']);
        $this->update('setting', ['value' => 'en-GB'], ['name' => 'defaultLanguage', 'value' => 'en_gb', 'module_id' => 'legal']);
        $this->update('setting', ['value' => 'fa-IR'], ['name' => 'defaultLanguage', 'value' => 'fa_ir', 'module_id' => 'legal']);
        $this->update('setting', ['value' => 'nb-NO'], ['name' => 'defaultLanguage', 'value' => 'nb_no', 'module_id' => 'legal']);
        $this->update('setting', ['value' => 'nn-NO'], ['name' => 'defaultLanguage', 'value' => 'nn_no', 'module_id' => 'legal']);
        $this->update('setting', ['value' => 'pt-BR'], ['name' => 'defaultLanguage', 'value' => 'pt_br', 'module_id' => 'legal']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200205_161251_updated_language_codes cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200205_161251_updated_language_codes cannot be reverted.\n";

        return false;
    }
    */
}
