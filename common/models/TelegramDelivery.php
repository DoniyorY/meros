<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveRecord;

final class TelegramDelivery extends ActiveRecord
{
   public const STATUS_PENDING = 0;
   public const STATUS_SENT = 1;
   public const STATUS_FAILED = 2;

   public static function tableName(): string
   {
      return '{{%telegram_delivery}}';
   }

   public function rules(): array
   {
      return [
         [['event_id', 'chat_id'], 'required'],
         [['status', 'attempts', 'created_at', 'updated_at'], 'integer'],
         [['response', 'error'], 'string'],
         [['event_id'], 'string', 'max' => 191],
         [['chat_id'], 'string', 'max' => 32],
         [['event_id'], 'unique'],
      ];
   }
}
