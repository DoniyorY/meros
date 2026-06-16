<?php

use yii\db\Migration;

class m260616_123934_alter_column_desc_en_string_subscription_plan_items extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('subscription_plan_items', 'desc_en', $this->text());
      $this->alterColumn('subscription_plan_items', 'desc_ru', $this->text());
      $this->alterColumn('subscription_plan_items', 'desc_uz', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->alterColumn('subscription_plan_items', 'desc_en', $this->string());
       $this->alterColumn('subscription_plan_items', 'desc_ru', $this->string());
       $this->alterColumn('subscription_plan_items', 'desc_uz', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260616_123934_alter_column_desc_en_string_subscription_plan_items cannot be reverted.\n";

        return false;
    }
    */
}
