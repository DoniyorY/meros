<?php

use yii\db\Migration;

class m260630_113148_add_lvl_column_to_courses extends Migration
{
   /**
    * {@inheritdoc}
    */
   public function safeUp()
   {
      $this->addColumn('courses', 'lvl', $this->string());
   }
   
   /**
    * {@inheritdoc}
    */
   public function safeDown()
   {
      $this->dropColumn('courses', 'lvl');
   }
   
   /*
   // Use up()/down() to run migration code without a transaction.
   public function up()
   {

   }

   public function down()
   {
       echo "m260630_113148_add_lvl_column_to_courses cannot be reverted.\n";

       return false;
   }
   */
}
