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

final class TelegramStaffNotificationService
{
   public static function sendNewSubscriptionNotification(Billing $billing): bool
   {
      $recipients = TelegramStaffUserService::recipients();

      if ($recipients === []) {
         Yii::info([
            'message' => 'Staff Telegram notification skipped: no connected recipients.',
            'billing_id' => (int)$billing->id,
            'allowed_roles' => TelegramStaffUserService::allowedRoles(),
         ], 'telegram-staff');

         return false;
      }

      $message = TelegramStaffMessageBuilder::build($billing);
      $allSent = true;
      $sentCount = 0;

      foreach ($recipients as $staffUser) {
         if (!self::sendToRecipient($billing, $staffUser, $message)) {
            $allSent = false;
            continue;
         }

         $sentCount++;
      }

      Yii::info([
         'message' => 'Staff Telegram notification batch completed.',
         'billing_id' => (int)$billing->id,
         'recipients' => count($recipients),
         'sent' => $sentCount,
      ], 'telegram-staff');

      return $allSent && $sentCount > 0;
   }

   private static function sendToRecipient(
      Billing $billing,
      User $staffUser,
      string $message
   ): bool {
      $chatId = trim((string)TelegramStaffUserService::attribute(
         $staffUser,
         'staff_telegram_chat_id',
         ''
      ));

      if ($chatId === '') {
         return false;
      }

      $eventId = sprintf(
         'staff-billing-paid:%d:%d',
         (int)$billing->id,
         (int)$staffUser->id
      );

      $delivery = TelegramDelivery::findOne(['event_id' => $eventId]);
      if (
         $delivery !== null
         && (int)$delivery->status === TelegramDelivery::STATUS_SENT
      ) {
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
               throw new IntegrityException(
                  'Unable to resolve staff Telegram delivery race.'
               );
            }

            if ((int)$delivery->status === TelegramDelivery::STATUS_SENT) {
               return true;
            }
         }
      }

      $delivery->attempts = (int)$delivery->attempts + 1;
      $delivery->updated_at = time();
      $delivery->save(false, ['attempts', 'updated_at']);

      try {
         $replyMarkup = self::billingButton((int)$billing->id);
         $response = Yii::$app->telegramStaffBot->sendMessage(
            $chatId,
            $message,
            $replyMarkup
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

         return true;
      } catch (Throwable $exception) {
         $delivery->status = TelegramDelivery::STATUS_FAILED;
         $delivery->error = mb_substr($exception->getMessage(), 0, 4000);
         $delivery->updated_at = time();
         $delivery->save(false, ['status', 'error', 'updated_at']);

         Yii::error([
            'message' => 'Staff Telegram purchase notification failed.',
            'billing_id' => (int)$billing->id,
            'staff_user_id' => (int)$staffUser->id,
            'chat_id' => $chatId,
            'exception' => $exception->getMessage(),
         ], 'telegram-staff');

         return false;
      }
   }

   private static function billingButton(int $billingId): ?array
   {
      $template = trim((string)(
         Yii::$app->params['telegramStaffBillingUrlTemplate'] ?? ''
      ));

      if ($template === '') {
         return null;
      }

      return [
         'inline_keyboard' => [
            [
               [
                  'text' => 'Открыть заказ',
                  'url' => str_replace('{id}', (string)$billingId, $template),
               ],
            ],
         ],
      ];
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
