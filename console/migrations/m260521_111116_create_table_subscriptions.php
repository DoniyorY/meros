<?php

use yii\db\Migration;

class m260521_111116_create_table_subscriptions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%subscription_plans}}',[
            'id'=>$this->primaryKey(),
            'name_ru'=>$this->string()->notNull(),
            'name_en'=>$this->string()->notNull(),
            'name_uz'=>$this->string()->notNull(),
            'price'=>$this->float()->notNull(),
            'duration_days'=>$this->integer()->notNull(),
            'status'=>$this->tinyInteger()->notNull()->defaultValue(0),
            'created_at'=>$this->integer()->notNull(),
            'updated_at'=>$this->integer()->notNull(),
        ]);
        $this->createTable('{{%subscription_plan_items}}',[
            'id'=>$this->primaryKey(),
            'plan_id'=>$this->integer()->notNull(),
            'name_ru'=>$this->string()->notNull(),
            'name_en'=>$this->string()->notNull(),
            'name_uz'=>$this->string()->notNull(),
            'desc_ru'=>$this->string()->notNull(),
            'desc_en'=>$this->string()->notNull(),
            'desc_uz'=>$this->string()->notNull(),
        ]);
        $this->createTable('{{%subscription_plan_packs}}',[
            'id'=>$this->primaryKey(),
            'plan_id'=>$this->integer()->notNull(),
            'pack_id'=>$this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%subscription_plan_packs}}');
        $this->dropTable('{{%subscription_plan_items}}');
        $this->dropTable('{{%subscription_plans}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260521_111116_create_table_subscriptions cannot be reverted.\n";

        return false;
    }
    */
}
