<?php

use yii\db\Migration;

class m260521_111048_create_table_user_subscriptions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable("{{%user_subscriptions}}", [
            'id' => $this->primaryKey(),
            'plan_id'=>$this->integer()->notNull(),
            'user_id'=>$this->integer()->notNull(),
            'status'=>$this->integer()->notNull(),
            'subscription_key'=>$this->string()->notNull(),
            'start_date'=>$this->integer()->notNull(),
            'expires_date'=>$this->integer()->notNull(),
            'created_at'=>$this->integer()->notNull(),
            'updated_at'=>$this->integer()->notNull(),
            'amount'=>$this->integer()->notNull(),
            'currency_code'=>$this->integer()->notNull(),
            'payment_transaction_id'=>$this->string(),
            'payment_provider'=>$this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable("{{%user_subscriptions}}");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260521_111048_create_table_user_subscriptions cannot be reverted.\n";

        return false;
    }
    */
}
