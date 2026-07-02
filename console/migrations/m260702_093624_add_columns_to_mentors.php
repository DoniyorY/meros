<?php

use yii\db\Migration;

class m260702_093624_add_columns_to_mentors extends Migration
{
   /**
    * {@inheritdoc}
    */
   public function safeUp()
   {
      $this->addColumn('mentors','position_ru',$this->string());
      $this->addColumn('mentors','position_en',$this->string());
      $this->addColumn('mentors','position_uz',$this->string());
      $this->addColumn('mentors', 'desc_ru', $this->text());
      $this->addColumn('mentors', 'desc_en', $this->text());
      $this->addColumn('mentors', 'desc_uz', $this->text());
      $this->addColumn('mentors', 'avatar', $this->string());
   }
   
   /**
    * {@inheritdoc}
    */
   public function safeDown()
   {
      $this->dropColumn('mentors','position_ru');
      $this->dropColumn('mentors','position_en');
      $this->dropColumn('mentors','position_uz');
      $this->dropColumn('mentors', 'desc_ru');
      $this->dropColumn('mentors', 'desc_en');
      $this->dropColumn('mentors', 'desc_uz');
      $this->dropColumn('mentors', 'avatar');
   }
   
   /*
   // Use up()/down() to run migration code without a transaction.
   public function up()
   {

   }

   public function down()
   {
       echo "m260702_093624_add_columns_to_mentors cannot be reverted.\n";

       return false;
   }
   */
}
