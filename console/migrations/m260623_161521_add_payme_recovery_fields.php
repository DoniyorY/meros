<?php

use yii\db\Migration;

class m260623_161521_add_payme_recovery_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
   public function safeUp(): void
   {
      $this->addColumn(
         '{{%payme_transaction}}',
         'is_recovered',
         $this->boolean()
            ->notNull()
            ->defaultValue(false)
            ->after('reason')
      );
      
      $this->createIndex(
         'idx-billing-payment_transaction_id',
         '{{%billing}}',
         'payment_transaction_id'
      );
   }
   
   public function safeDown(): void
   {
      $this->dropIndex(
         'idx-billing-payment_transaction_id',
         '{{%billing}}'
      );
      
      $this->dropColumn(
         '{{%payme_transaction}}',
         'is_recovered'
      );
   }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260623_161521_add_payme_recovery_fields cannot be reverted.\n";

        return false;
    }
    */
}
