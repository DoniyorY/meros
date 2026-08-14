<?php

use yii\db\Migration;

class m260812_110337_add_ga_settings_to_billing extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('billing','ga_client_id',$this->string(100));
      $this->addColumn('billing','ga_session_id',$this->string(100));
      $this->addColumn('billing','ga_purchase_sent_at',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('billing','ga_client_id');
        $this->dropColumn('billing','ga_session_id');
        $this->dropColumn('billing','ga_purchase_sent_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260812_110337_add_ga_settings_to_billing cannot be reverted.\n";

        return false;
    }
    */
}
