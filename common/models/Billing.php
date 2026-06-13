<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "billing".
 *
 * @property int $id
 * @property string $billing_token
 * @property int $user_id
 * @property int $subscription_id
 * @property int|null $start_date
 * @property int|null $expires_date
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $payment_transaction_id
 * @property int|null $payment_provider
 * @property int|null $payment_status
 * @property int|null $amount
 * @property int $status
 */
class Billing extends \yii\db\ActiveRecord
{
   
   
   /**
    * {@inheritdoc}
    */
   public static function tableName()
   {
      return 'billing';
   }
   
   /**
    * {@inheritdoc}
    */
   public function rules()
   {
      return [
         [['start_date', 'expires_date', 'payment_transaction_id', 'payment_provider', 'payment_status', 'amount'], 'default', 'value' => null],
         [['status'], 'default', 'value' => 0],
         [['billing_token', 'user_id', 'subscription_id', 'created_at', 'updated_at'], 'required'],
         [['user_id', 'subscription_id', 'start_date', 'expires_date', 'created_at', 'updated_at', 'payment_provider', 'payment_status', 'amount', 'status'], 'integer'],
         [['billing_token', 'payment_transaction_id'], 'string', 'max' => 255],
      ];
   }
   
   /**
    * {@inheritdoc}
    */
   public function attributeLabels()
   {
      return [
         'id' => 'ID',
         'billing_token' => 'Billing Token',
         'user_id' => 'User ID',
         'subscription_id' => 'Subscription ID',
         'start_date' => 'Start Date',
         'expires_date' => 'Expires Date',
         'created_at' => 'Created At',
         'updated_at' => 'Updated At',
         'payment_transaction_id' => 'Payment Transaction ID',
         'payment_provider' => 'Payment Provider',
         'payment_status' => 'Payment Status',
         'amount' => 'Amount',
         'status' => 'Status',
      ];
   }
   
   public function getSubscription()
   {
      return $this->hasOne(SubscriptionPlans::class, ['id' => 'subscription_id']);
   }
   
   public function getUser()
   {
      return $this->hasOne(User::class,['id'=>'user_id']);
   }
   
}
