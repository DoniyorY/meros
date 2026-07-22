<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $user_id
 * @property string $token_hash
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $device_type
 * @property string|null $device_name
 * @property string|null $browser
 * @property string|null $operating_system
 * @property string|null $country
 * @property string|null $city
 * @property int $logged_in_at
 * @property int $last_seen_at
 * @property int $expires_at
 * @property int|null $logged_out_at
 * @property int|null $revoked_at
 */
class UserLoginSession extends ActiveRecord
{
   public static function tableName(): string
   {
      return '{{%user_login_session}}';
   }
   
   public function rules(): array
   {
      return [
         [
            [
               'user_id',
               'logged_in_at',
               'last_seen_at',
               'expires_at',
            ],
            'required',
         ],
         
         [
            [
               'user_id',
               'logged_in_at',
               'last_seen_at',
               'expires_at',
               'logged_out_at',
               'revoked_at',
            ],
            'integer',
         ],
         
         [['token_hash'], 'string', 'max' => 64],
         [['token_hash'], 'unique'],
         
         [['ip_address'], 'string', 'max' => 45],
         [['device_type'], 'string', 'max' => 30],
         
         [
            [
               'device_name',
               'browser',
               'operating_system',
            ],
            'string',
            'max' => 150,
         ],
         
         [['country', 'city'], 'string', 'max' => 100],
         [['user_agent'], 'string'],
      ];
   }
   
   public function getIsActive(): bool
   {
      return $this->logged_out_at === null
         && $this->revoked_at === null
         && $this->expires_at > time();
   }
   
   public function getUser(): ActiveQuery
   {
      return $this->hasOne(User::class, ['id' => 'user_id']);
   }
}