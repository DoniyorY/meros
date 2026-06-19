<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int|null $click_payment_id
 * @property string|null $click_trans_id
 * @property string|null $merchant_trans_id
 * @property int|null $action
 * @property string $request_payload
 * @property string|null $response_payload
 * @property int|null $response_error
 * @property string|null $remote_ip
 * @property int $created_at
 * @property int|null $processed_at
 */
final class ClickWebhookLog extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%click_webhook_logs}}';
    }
}
