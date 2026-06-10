<?php

use yii\db\Migration;

class m260610_114455_add_column_to_courses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('courses','course_icons',$this->string());
      $this->addColumn('courses','title_ru',$this->string());
      $this->addColumn('courses','title_en',$this->string());
      $this->addColumn('courses','title_uz',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropColumn('courses','course_icons');
       $this->addColumn('courses','title_ru',$this->string());
       $this->addColumn('courses','title_en',$this->string());
       $this->addColumn('courses','title_uz',$this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260610_114455_add_column_to_courses cannot be reverted.\n";

        return false;
    }
    */
}
