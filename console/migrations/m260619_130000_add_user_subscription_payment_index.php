<?php

declare(strict_types=1);

use yii\db\Migration;

/**
 * Ускоряет идемпотентный поиск подписки по платежу.
 *
 * Индекс не UNIQUE, чтобы миграция не упала на старых данных.
 * После проверки и очистки дублей его можно заменить на UNIQUE.
 */
final class m260619_130000_add_user_subscription_payment_index
    extends Migration
{
    public function safeUp(): void
    {
        $this->createIndex(
            'idx-user_subscriptions-provider-transaction',
            '{{%user_subscriptions}}',
            [
                'payment_provider',
                'payment_transaction_id',
            ]
        );
    }

    public function safeDown(): void
    {
        $this->dropIndex(
            'idx-user_subscriptions-provider-transaction',
            '{{%user_subscriptions}}'
        );
    }
}
