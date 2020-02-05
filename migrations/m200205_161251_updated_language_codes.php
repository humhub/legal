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

        $this->update('setting', ['defaultLanguage' => 'en-US'], ['defaultLanguage' => 'en', 'module_id' => 'legal']);
        $this->update('setting', ['defaultLanguage' => 'en-GB'], ['defaultLanguage' => 'en_gb', 'module_id' => 'legal']);
        $this->update('setting', ['defaultLanguage' => 'fa-IR'], ['defaultLanguage' => 'fa_ir', 'module_id' => 'legal']);
        $this->update('setting', ['defaultLanguage' => 'nb-NO'], ['defaultLanguage' => 'nb_no', 'module_id' => 'legal']);
        $this->update('setting', ['defaultLanguage' => 'nn-NO'], ['defaultLanguage' => 'nn_no', 'module_id' => 'legal']);
        $this->update('setting', ['defaultLanguage' => 'pt-BR'], ['defaultLanguage' => 'pt_br', 'module_id' => 'legal']);
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
