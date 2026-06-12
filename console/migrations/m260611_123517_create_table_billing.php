<?php

use yii\db\Migration;

class m260611_123517_create_table_billing extends Migration
{
   /**
    * {@inheritdoc}
    */
   public function safeUp()
   {
      $this->createTable('billing', [
         'id' => $this->primaryKey(),
         'billing_token' => $this->string()->notNull(),
         'user_id' => $this->integer(),
         'subscription_id' => $this->integer()->notNull(),
         'start_date' => $this->integer(),
         'expires_date' => $this->integer(),
         'created_at' => $this->integer()->notNull(),
         'updated_at' => $this->integer()->notNull(),
         'payment_transaction_id' => $this->string(),
         'payment_provider' => $this->integer(),
         'payment_status' => $this->integer(),
         'amount' => $this->integer(),
         'status' => $this->smallInteger()->notNull()->defaultValue(0),
      ]);
   }
   
   /**
    * {@inheritdoc}
    */
   public function safeDown()
   {
      $this->dropTable('billing');
   }
   
   /*
   // Use up()/down() to run migration code without a transaction.
   public function up()
   {

   }

   public function down()
   {
       echo "m260611_123517_create_table_billing cannot be reverted.\n";

       return false;
   }
   */
}
