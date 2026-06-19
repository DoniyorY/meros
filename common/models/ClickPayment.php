<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $subscription_id
 * @property string $click_trans_id
 * @property string|null $click_paydoc_id
 * @property int $service_id
 * @property string $merchant_trans_id
 * @property string $amount
 * @property int $status
 * @property int|null $click_error
 * @property string|null $click_error_note
 * @property string|null $sign_time
 * @property int|null $prepared_at
 * @property int|null $paid_at
 * @property int|null $cancelled_at
 * @property int $created_at
 * @property int $updated_at
 */
final class ClickPayment extends ActiveRecord
{
    public const STATUS_NEW = 0;
    public const STATUS_PREPARED = 1;
    public const STATUS_PAID = 2;
    public const STATUS_CANCELLED = 3;
    public const STATUS_FAILED = 4;

    public static function tableName(): string
    {
        return '{{%click_payments}}';
    }
}
