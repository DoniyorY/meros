<?php

use yii\db\Migration;

class m260521_111058_create_table_user_course_progress extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable("{{%user_course_progress}}", [
            'id'=>$this->primaryKey(),
            'user_id'=>$this->integer()->notNull(),
            'lesson_id'=>$this->integer()->notNull(),
            'is_completed'=>$this->tinyInteger(1)->defaultValue(0),
            'watched_seconds'=>$this->integer()->defaultValue(0),
            'updated_at'=>$this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable("{{%user_course_progress}}");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260521_111058_create_table_user_course_progress cannot be reverted.\n";

        return false;
    }
    */
}
