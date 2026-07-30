<?php

use yii\db\Migration;

class m260730_105845_add_is_bot_column_to_site_visit extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('{{%site_visit}}', 'is_bot', $this->boolean()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%site_visit}}', 'is_bot');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260730_105845_add_is_bot_column_to_site_visit cannot be reverted.\n";

        return false;
    }
    */
}
