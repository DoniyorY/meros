<?php

declare(strict_types=1);

use yii\db\Migration;

final class m260619_120000_create_payme_tables extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%payme_transaction}}', [
            'id' => $this->bigPrimaryKey(),
            'payme_id' => $this->string(24)->notNull(),
            'billing_id' => $this->integer()->notNull(),
            'payme_time' => $this->bigInteger()->notNull(),
            'amount' => $this->bigInteger()->notNull(),
            'account' => $this->text()->notNull(),
            'create_time' => $this->bigInteger()->notNull(),
            'perform_time' => $this->bigInteger()
                ->notNull()
                ->defaultValue(0),
            'cancel_time' => $this->bigInteger()
                ->notNull()
                ->defaultValue(0),
            'state' => $this->smallInteger()->notNull(),
            'reason' => $this->smallInteger()->null(),
            'fiscal_perform' => $this->text()->null(),
            'fiscal_cancel' => $this->text()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'uq-payme_transaction-payme_id',
            '{{%payme_transaction}}',
            'payme_id',
            true
        );

        $this->createIndex(
            'idx-payme_transaction-billing_state',
            '{{%payme_transaction}}',
            ['billing_id', 'state']
        );

        $this->createIndex(
            'idx-payme_transaction-payme_time',
            '{{%payme_transaction}}',
            'payme_time'
        );

        $this->addForeignKey(
            'fk-payme_transaction-billing_id',
            '{{%payme_transaction}}',
            'billing_id',
            '{{%billing}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createTable('{{%payme_log}}', [
            'id' => $this->bigPrimaryKey(),
            'rpc_id' => $this->bigInteger()->null(),
            'method' => $this->string(64)->null(),
            'request_body' => $this->text()->notNull(),
            'response_body' => $this->text()->notNull(),
            'authorization_ok' => $this->boolean()
                ->notNull()
                ->defaultValue(false),
            'ip' => $this->string(45)->null(),
            'duration_ms' => $this->integer()
                ->notNull()
                ->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-payme_log-method_created',
            '{{%payme_log}}',
            ['method', 'created_at']
        );

        $this->createIndex(
            'idx-payme_log-rpc_id',
            '{{%payme_log}}',
            'rpc_id'
        );

        // В billing_token должен быть UNIQUE INDEX.
        // Если его ещё нет, добавьте отдельной миграцией:
        //
        // $this->createIndex(
        //     'uq-billing-billing_token',
        //     '{{%billing}}',
        //     'billing_token',
        //     true
        // );
    }

    public function safeDown(): void
    {
        $this->dropForeignKey(
            'fk-payme_transaction-billing_id',
            '{{%payme_transaction}}'
        );

        $this->dropTable('{{%payme_log}}');
        $this->dropTable('{{%payme_transaction}}');
    }
}
