<?php

use yii\db\Migration;

class m260609_093619_add_column_to_courses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('courses','syllabus_file',$this->string());
      $this->addColumn('courses','flyer_file',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('courses','syllabus_file');
        $this->dropColumn('courses','flyer_file');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260609_093619_add_column_to_courses cannot be reverted.\n";

        return false;
    }
    */
}
