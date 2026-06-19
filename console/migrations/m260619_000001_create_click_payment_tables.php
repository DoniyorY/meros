<?php

declare(strict_types=1);

use yii\db\Migration;

final class m260619_000001_create_click_payment_tables extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%click_payments}}', [
            'id' => $this->bigPrimaryKey(),
            'subscription_id' => $this->integer()->notNull(),
            'click_trans_id' => $this->string(64)->notNull(),
            'click_paydoc_id' => $this->string(64)->null(),
            'service_id' => $this->integer()->notNull(),
            'merchant_trans_id' => $this->string(64)->notNull(),
            'amount' => $this->decimal(14, 2)->notNull(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'click_error' => $this->integer()->null(),
            'click_error_note' => $this->string(255)->null(),
            'sign_time' => $this->string(32)->null(),
            'prepared_at' => $this->integer()->null(),
            'paid_at' => $this->integer()->null(),
            'cancelled_at' => $this->integer()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'ux-click_payments-click_trans_id',
            '{{%click_payments}}',
            'click_trans_id',
            true
        );
        $this->createIndex(
            'ix-click_payments-subscription_id',
            '{{%click_payments}}',
            'subscription_id'
        );
        $this->createIndex(
            'ix-click_payments-merchant_trans_id',
            '{{%click_payments}}',
            'merchant_trans_id'
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

        $this->createTable('{{%click_webhook_logs}}', [
            'id' => $this->bigPrimaryKey(),
            'click_payment_id' => $this->bigInteger()->null(),
            'click_trans_id' => $this->string(64)->null(),
            'merchant_trans_id' => $this->string(64)->null(),
            'action' => $this->tinyInteger()->null(),
            'request_payload' => $this->text()->notNull(),
            'response_payload' => $this->text()->null(),
            'response_error' => $this->integer()->null(),
            'remote_ip' => $this->string(45)->null(),
            'created_at' => $this->integer()->notNull(),
            'processed_at' => $this->integer()->null(),
        ]);

        $this->createIndex(
            'ix-click_webhook_logs-click_payment_id',
            '{{%click_webhook_logs}}',
            'click_payment_id'
        );
        $this->createIndex(
            'ix-click_webhook_logs-click_trans_id',
            '{{%click_webhook_logs}}',
            'click_trans_id'
        );

        $this->addForeignKey(
            'fk-click_webhook_logs-click_payment_id',
            '{{%click_webhook_logs}}',
            'click_payment_id',
            '{{%click_payments}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown(): void
    {
        $this->dropForeignKey('fk-click_webhook_logs-click_payment_id', '{{%click_webhook_logs}}');
        $this->dropTable('{{%click_webhook_logs}}');

        $this->dropForeignKey('fk-click_payments-subscription_id', '{{%click_payments}}');
        $this->dropTable('{{%click_payments}}');
    }
}
