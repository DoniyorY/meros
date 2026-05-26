<?php

use yii\db\Migration;

class m260524_213017_change_column_mentor_id_courses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('courses','mentor_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260524_213017_change_column_mentor_id_courses cannot be reverted.\n";

        return false;
    }
    */
}
