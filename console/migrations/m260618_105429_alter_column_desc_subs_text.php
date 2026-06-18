<?php

use yii\db\Migration;

class m260618_105429_alter_column_desc_subs_text extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('subscription_plan_items', 'desc_ru', $this->text());
      $this->alterColumn('subscription_plan_items', 'desc_en', $this->text());
      $this->alterColumn('subscription_plan_items', 'desc_uz', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('subscription_plan_items', 'desc_ru', $this->string());
        $this->alterColumn('subscription_plan_items', 'desc_en', $this->string());
        $this->alterColumn('subscription_plan_items', 'desc_uz', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260618_105429_alter_column_desc_subs_text cannot be reverted.\n";

        return false;
    }
    */
}
