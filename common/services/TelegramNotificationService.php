<?php

declare(strict_types=1);

namespace common\services;

use common\models\Billing;
use common\models\TelegramDelivery;
use common\models\User;
use JsonException;
use Throwable;
use Yii;
use yii\db\IntegrityException;

final class TelegramNotificationService
{
   public static function sendPurchaseNotification(Billing $billing): bool
   {
      $user = $billing->user instanceof User
         ? $billing->user
         : User::findOne((int)$billing->user_id);

      if ($user === null) {
         Yii::error([
            'message' => 'Telegram purchase notification: user not found.',
            'billing_id' => (int)$billing->id,
         ], 'telegram');

         return false;
      }

      $chatId = trim((string)TelegramUserService::attribute(
         $user,
         'telegram_chat_id',
         ''
      ));

      if ($chatId === '') {
         Yii::info([
            'message' => 'Telegram purchase notification skipped: account is not connected.',
            'billing_id' => (int)$billing->id,
            'user_id' => (int)$user->id,
         ], 'telegram');

         return false;
      }

      $eventId = 'billing-paid:' . (int)$billing->id;
      $delivery = TelegramDelivery::findOne(['event_id' => $eventId]);

      if ($delivery !== null && (int)$delivery->status === TelegramDelivery::STATUS_SENT) {
         return true;
      }

      if ($delivery === null) {
         $delivery = new TelegramDelivery([
            'event_id' => $eventId,
            'chat_id' => $chatId,
            'status' => TelegramDelivery::STATUS_PENDING,
            'attempts' => 0,
            'created_at' => time(),
            'updated_at' => time(),
         ]);

         try {
            $delivery->save(false);
         } catch (IntegrityException) {
            $delivery = TelegramDelivery::findOne(['event_id' => $eventId]);
            if ($delivery === null) {
               throw new IntegrityException('Unable to resolve Telegram delivery race.');
            }

            if ((int)$delivery->status === TelegramDelivery::STATUS_SENT) {
               return true;
            }
         }
      }

      $language = TelegramUserService::resolveLanguage($user);
      $name = (string)TelegramUserService::attribute($user, 'fullname', '');
      $platformUrl = trim((string)(Yii::$app->params['learningPlatformUrl'] ?? ''));

      if ($platformUrl === '') {
         Yii::error([
            'message' => 'learningPlatformUrl is not configured.',
            'billing_id' => (int)$billing->id,
         ], 'telegram');

         return false;
      }

      $delivery->attempts = (int)$delivery->attempts + 1;
      $delivery->updated_at = time();
      $delivery->save(false, ['attempts', 'updated_at']);

      try {
         $response = Yii::$app->telegramBot->sendMessage(
            $chatId,
            PurchaseMessageBuilder::build($language, $name),
            [
               'inline_keyboard' => [
                  [
                     [
                        'text' => PurchaseMessageBuilder::courseButton($language),
                        'url' => $platformUrl,
                     ],
                  ],
               ],
            ]
         );

         $delivery->status = TelegramDelivery::STATUS_SENT;
         $delivery->response = self::encode($response);
         $delivery->error = null;
         $delivery->updated_at = time();
         $delivery->save(false, [
            'status',
            'response',
            'error',
            'updated_at',
         ]);

         Yii::info([
            'message' => 'Telegram purchase notification sent.',
            'billing_id' => (int)$billing->id,
            'user_id' => (int)$user->id,
            'chat_id' => $chatId,
         ], 'telegram');

         return true;
      } catch (Throwable $exception) {
         $delivery->status = TelegramDelivery::STATUS_FAILED;
         $delivery->error = mb_substr($exception->getMessage(), 0, 4000);
         $delivery->updated_at = time();
         $delivery->save(false, ['status', 'error', 'updated_at']);

         Yii::error([
            'message' => 'Telegram purchase notification failed.',
            'billing_id' => (int)$billing->id,
            'user_id' => (int)$user->id,
            'chat_id' => $chatId,
            'exception' => $exception->getMessage(),
         ], 'telegram');

         return false;
      }
   }

   private static function encode(array $value): string
   {
      try {
         return json_encode(
            $value,
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
         );
      } catch (JsonException) {
         return '{}';
      }
   }
}
