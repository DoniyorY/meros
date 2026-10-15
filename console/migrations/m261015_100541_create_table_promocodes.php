<?php

use yii\db\Migration;

class m261015_100541_create_table_promocodes extends Migration
{
   /**
    * {@inheritdoc}
    */
   public function safeUp()
   {
      $this->createTable('promocodes', [
         'id' => $this->primaryKey(),
         'code' => $this->string(50)->notNull()->unique(),
         'created_at' => $this->integer()->notNull(),
         'updated_at' => $this->integer()->notNull(),
         'status' => $this->integer()->notNull(),
         'user_id' => $this->integer(),
         'discount' => $this->integer(),
      ]);
      $this->addColumn('billing', 'promocode_id', $this->integer());
      $this->addColumn('billing','no_discount_amount',$this->integer());
      
   }
   
   /**
    * {@inheritdoc}
    */
   public function safeDown()
   {
      $this->dropColumn('billing','no_discount_amount');
      $this->dropColumn('billing', 'promocode_id');
      $this->dropTable('promocodes');
   }
   
   /*
   // Use up()/down() to run migration code without a transaction.
   public function up()
   {

   }

   public function down()
   {
       echo "m261015_100541_create_table_promocodes cannot be reverted.\n";

       return false;
   }
   */
}
