<?php

use yii\db\Migration;

class m260525_104600_add_columns_to_course_lessons extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('course_lessons', 'video_link', $this->string()->notNull());
      $this->addColumn('course_lessons', 'duration', $this->string());
      $this->addColumn('course_lessons','video_sources', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropColumn('course_lessons', 'video_link');
       $this->dropColumn('course_lessons', 'duration');
       $this->dropColumn('course_lessons','video_sources');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260525_104600_add_columns_to_course_lessons cannot be reverted.\n";

        return false;
    }
    */
}
