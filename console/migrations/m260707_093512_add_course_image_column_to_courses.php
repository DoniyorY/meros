<?php

use yii\db\Migration;

class m260707_093512_add_course_image_column_to_courses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->addColumn('courses','course_image',$this->string());
         
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('courses','course_image');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260707_093512_add_course_image_column_to_courses cannot be reverted.\n";

        return false;
    }
    */
}
