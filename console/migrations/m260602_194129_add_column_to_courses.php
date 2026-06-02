<?php

use yii\db\Migration;

class m260602_194129_add_column_to_courses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('courses','image',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('courses','image');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260602_194129_add_column_to_courses cannot be reverted.\n";

        return false;
    }
    */
}
