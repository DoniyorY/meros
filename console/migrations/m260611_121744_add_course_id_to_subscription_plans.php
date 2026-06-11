<?php

use yii\db\Migration;

class m260611_121744_add_course_id_to_subscription_plans extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
       $this->addColumn('subscription_plans','course_id',$this->integer());
       
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('subscription_plans','course_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260611_121744_add_course_id_to_subscription_plans cannot be reverted.\n";

        return false;
    }
    */
}
