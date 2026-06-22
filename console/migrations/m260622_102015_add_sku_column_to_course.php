<?php

use common\models\Courses;
use yii\db\Migration;

class m260622_102015_add_sku_column_to_course extends Migration
{
   /**
    * {@inheritdoc}
    */
   public function safeUp()
   {
      $this->addColumn('courses', 'sku_id', $this->string());
      $courses = Courses::find()->where(['page_type' => 1])->orderBy(['id' => SORT_ASC])->all();
      $i = 001;
      foreach ($courses as $item) {
         $item->sku_id = sprintf('SKU-%03d', $i);
         $item->save(false);
         $i++;
      }
      
   }
   
   /**
    * {@inheritdoc}
    */
   public function safeDown()
   {
      $this->dropColumn('courses', 'skud_id');
   }
   
   /*
   // Use up()/down() to run migration code without a transaction.
   public function up()
   {

   }

   public function down()
   {
       echo "m260622_102015_add_sku_column_to_course cannot be reverted.\n";

       return false;
   }
   */
}
