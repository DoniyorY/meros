<?php

use yii\db\Migration;

class m260625_104045_change_click_payments_subs_id_to_billing_id extends Migration
{
    /**
     * {@inheritdoc}
     */
   public function safeUp(): void
   {
      $this->dropForeignKey(
         'fk-click_payments-subscription_id',
         '{{%click_payments}}'
      );
      $this->dropIndex(
         'ix-click_payments-subscription_id',
         '{{%click_payments}}'
      );
      
      $this->renameColumn(
         '{{%click_payments}}',
         'subscription_id',
         'billing_id'
      );
      
      $this->createIndex(
         'ix-click_payments-billing_id',
         '{{%click_payments}}',
         'billing_id'
      );
      $this->addForeignKey(
         'fk-click_payments-billing_id',
         '{{%click_payments}}',
         'billing_id',
         '{{%billing}}',
         'id',
         'RESTRICT',
         'CASCADE'
      );
   }
   
   public function safeDown(): void
   {
      $this->dropForeignKey(
         'fk-click_payments-billing_id',
         '{{%click_payments}}'
      );
      $this->dropIndex(
         'ix-click_payments-billing_id',
         '{{%click_payments}}'
      );
      
      $this->renameColumn(
         '{{%click_payments}}',
         'billing_id',
         'subscription_id'
      );
      
      $this->createIndex(
         'ix-click_payments-subscription_id',
         '{{%click_payments}}',
         'subscription_id'
      );
      $this->addForeignKey(
         'fk-click_payments-subscription_id',
         '{{%click_payments}}',
         'subscription_id',
         '{{%user_subscriptions}}',
         'id',
         'RESTRICT',
         'CASCADE'
      );
   }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260625_104045_change_click_payments_subs_id_to_billing_id cannot be reverted.\n";

        return false;
    }
    */
}
