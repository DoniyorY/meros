<?php

declare(strict_types=1);

namespace common\models;

use JsonException;
use yii\db\ActiveRecord;

/**
 * ActiveRecord for table "payme_transaction".
 *
 * @property int $id
 * @property string $payme_id
 * @property int|string $billing_id
 * @property int $payme_time
 * @property int $amount
 * @property string $account
 * @property int $create_time
 * @property int $perform_time
 * @property int $cancel_time
 * @property int $state
 * @property int|null $reason
 * @property int $is_recovered
 * @property string|null $fiscal_perform
 * @property string|null $fiscal_cancel
 * @property int $created_at
 * @property int $updated_at
 */
final class PaymeTransaction extends ActiveRecord
{
    public const STATE_CREATED = 1;
    public const STATE_PERFORMED = 2;
    public const STATE_CANCELLED = -1;
    public const STATE_CANCELLED_AFTER_PERFORM = -2;

    public const CANCEL_REASON_TIMEOUT = 4;
    public const CANCEL_REASON_SYSTEM_ERROR = 3;

    public static function tableName(): string
    {
        return '{{%payme_transaction}}';
    }

    public function rules(): array
    {
        return [
            [['payme_id', 'billing_id', 'payme_time', 'amount', 'account'], 'required'],
            [['billing_id', 'payme_time', 'amount', 'create_time', 'perform_time', 'cancel_time', 'state', 'reason', 'is_recovered', 'created_at', 'updated_at'], 'integer'],
            [['account', 'fiscal_perform', 'fiscal_cancel'], 'string'],
            [['payme_id'], 'string', 'max' => 24],
            [['payme_id'], 'unique'],
        ];
    }

    public static function findByPaymeId(string $paymeId): ?self
    {
        /** @var self|null $model */
        $model = self::find()
            ->where(['payme_id' => $paymeId])
            ->one();

        return $model;
    }

    public function setAccountData(array $account): void
    {
        $this->account = json_encode(
            $account,
            JSON_UNESCAPED_UNICODE
            | JSON_UNESCAPED_SLASHES
            | JSON_THROW_ON_ERROR
        );
    }

    public function getAccountData(): array
    {
        if ($this->account === '') {
            return [];
        }

        try {
            $account = json_decode(
                $this->account,
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            return is_array($account) ? $account : [];
        } catch (JsonException) {
            return [];
        }
    }
}
