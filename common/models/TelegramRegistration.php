<?php

declare(strict_types=1);

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

final class TelegramRegistration extends ActiveRecord
{
   public const STATUS_WAIT_EMAIL = 1;
   public const STATUS_WAIT_CODE = 2;

   public static function tableName(): string
   {
      return '{{%telegram_registration}}';
   }

   public function behaviors(): array
   {
      return [
         TimestampBehavior::class,
      ];
   }

   public function rules(): array
   {
      return [
         [['telegram_chat_id', 'status'], 'required'],
         [['user_id', 'status', 'attempts', 'code_expires_at', 'last_code_sent_at', 'created_at', 'updated_at'], 'integer'],
         [['telegram_chat_id', 'telegram_user_id'], 'string', 'max' => 32],
         [['telegram_username'], 'string', 'max' => 255],
         [['telegram_language'], 'string', 'max' => 5],
         [['code_hash'], 'string', 'max' => 255],
      ];
   }
}
