<?php

use yii\db\Migration;

class m260624_150829_add_sku_code_to_subscriptions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
       $this->addColumn('subscription_plans','sku_id',$this->string());
       $subs = \common\models\SubscriptionPlans::find()
          ->with('course')
          ->orderBy(['course_id' => SORT_ASC, 'id' => SORT_ASC])
          ->all();
       
       $counters = [];
       
       foreach ($subs as $sub) {
          $courseSku = $sub->course?->sku_id;
          
          if (empty($courseSku)) {
             continue;
          }
          
          if (!isset($counters[$courseSku])) {
             $counters[$courseSku] = 1;
          }
          
          $sub->sku_id = $courseSku . '-' . $counters[$courseSku];
          $sub->save(false);
          
          $counters[$courseSku]++;
       }
      
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('subscription_plans','sku_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260624_150829_add_sku_code_to_subscriptions cannot be reverted.\n";

        return false;
    }
    */
}
