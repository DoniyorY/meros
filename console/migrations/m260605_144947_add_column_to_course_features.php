<?php

use yii\db\Migration;

class m260605_144947_add_column_to_course_features extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('course_features', 'syllabus_file', $this->string());
      $this->addColumn('course_features', 'flyer_file', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('course_features', 'syllabus_file');
        $this->dropColumn('course_features', 'flyer_file');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260605_144947_add_column_to_course_features cannot be reverted.\n";

        return false;
    }
    */
}
