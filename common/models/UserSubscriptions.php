<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_subscriptions".
 *
 * @property int $id
 * @property int $plan_id
 * @property int $user_id
 * @property int $status
 * @property string $subscription_key
 * @property int $start_date
 * @property int $expires_date
 * @property int $created_at
 * @property int $updated_at
 * @property int $amount
 * @property int $currency_code
 * @property string|null $payment_transaction_id
 * @property string|null $payment_provider
 */
class UserSubscriptions extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_subscriptions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_transaction_id', 'payment_provider'], 'default', 'value' => null],
            [['plan_id', 'user_id', 'status', 'subscription_key', 'start_date', 'expires_date', 'created_at', 'updated_at', 'amount', 'currency_code'], 'required'],
            [['plan_id', 'user_id', 'status', 'start_date', 'expires_date', 'created_at', 'updated_at', 'amount', 'currency_code'], 'integer'],
            [['subscription_key', 'payment_transaction_id', 'payment_provider'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'plan_id' => 'Plan ID',
            'user_id' => 'User ID',
            'status' => 'Status',
            'subscription_key' => 'Subscription Key',
            'start_date' => 'Start Date',
            'expires_date' => 'Expires Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'amount' => 'Amount',
            'currency_code' => 'Currency Code',
            'payment_transaction_id' => 'Payment Transaction ID',
            'payment_provider' => 'Payment Provider',
        ];
    }

    public function getPlan()
    {
        return $this->hasOne(SubscriptionPlans::class, ['id' => 'plan_id']);
    }

}
