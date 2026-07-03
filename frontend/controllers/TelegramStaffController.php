<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\services\PurchaseMessageBuilder;
use common\services\TelegramStaffUserService;
use JsonException;
use Throwable;
use Yii;
use yii\web\Controller;
use yii\web\Response;

final class TelegramStaffController extends Controller
{
   public $enableCsrfValidation = false;

   public function actionWebhook(): array
   {
      Yii::$app->response->format = Response::FORMAT_JSON;

      if (!$this->isWebhookAuthorized()) {
         Yii::$app->response->statusCode = 401;
         return ['ok' => false, 'error' => 'invalid_webhook_secret'];
      }

      try {
         $update = json_decode(
            (string)Yii::$app->request->rawBody,
            true,
            512,
            JSON_THROW_ON_ERROR
         );
      } catch (JsonException) {
         Yii::$app->response->statusCode = 400;
         return ['ok' => false, 'error' => 'invalid_json'];
      }

      $message = is_array($update['message'] ?? null)
         ? $update['message']
         : null;

      if ($message === null || !isset($message['chat']['id'])) {
         return ['ok' => true, 'status' => 'ignored'];
      }

      if (($message['chat']['type'] ?? '') !== 'private') {
         return ['ok' => true, 'status' => 'private_chats_only'];
      }

      $chatId = (string)$message['chat']['id'];
      $telegramUserId = (string)($message['from']['id'] ?? '');
      $username = trim((string)($message['from']['username'] ?? ''));
      $language = PurchaseMessageBuilder::normalizeLanguage(
         (string)($message['from']['language_code'] ?? 'ru')
      );
      $text = trim((string)($message['text'] ?? ''));

      try {
         $connectedUser = TelegramStaffUserService::findByStaffChatId($chatId);

         if ($connectedUser !== null) {
            if (!TelegramStaffUserService::hasAllowedRole($connectedUser)) {
               TelegramStaffUserService::disconnect($connectedUser);
               Yii::$app->telegramStaffBot->sendMessage(
                  $chatId,
                  $this->labels($language)['access_revoked'],
                  ['remove_keyboard' => true]
               );

               return ['ok' => true, 'status' => 'access_revoked'];
            }

            Yii::$app->telegramStaffBot->sendMessage(
               $chatId,
               $this->labels($language)['already_active'],
               ['remove_keyboard' => true]
            );

            return ['ok' => true, 'status' => 'already_active'];
         }

         if (!preg_match('/^\/start(?:@\w+)?$/', $text)) {
            Yii::$app->telegramStaffBot->sendMessage(
               $chatId,
               $this->labels($language)['start_required'],
               ['remove_keyboard' => true]
            );

            return ['ok' => true, 'status' => 'start_required'];
         }

         $result = TelegramStaffUserService::attachByEmployeeTelegramId(
            $chatId,
            $telegramUserId,
            $username
         );

         if (($result['ok'] ?? false) === true) {
            Yii::$app->telegramStaffBot->sendMessage(
               $chatId,
               $this->labels($language)['connected'],
               ['remove_keyboard' => true]
            );

            return ['ok' => true, 'status' => 'connected'];
         }

         $error = (string)($result['error'] ?? 'access_denied');
         $labels = $this->labels($language);

         Yii::$app->telegramStaffBot->sendMessage(
            $chatId,
            $labels[$error] ?? $labels['access_denied'],
            ['remove_keyboard' => true]
         );

         return ['ok' => true, 'status' => $error];
      } catch (Throwable $exception) {
         Yii::error([
            'message' => 'Staff Telegram webhook handling failed.',
            'chat_id' => $chatId,
            'telegram_user_id' => $telegramUserId,
            'exception' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'update' => $update,
         ], 'telegram-staff');

         return ['ok' => false, 'error' => 'webhook_processing_failed'];
      }
   }

   public function actionHealth(): array
   {
      Yii::$app->response->format = Response::FORMAT_JSON;

      return [
         'ok' => true,
         'service' => 'meros-telegram-staff',
         'time' => time(),
      ];
   }

   private function isWebhookAuthorized(): bool
   {
      $expected = trim((string)(
         Yii::$app->params['telegramStaffWebhookSecret'] ?? ''
      ));
      $received = trim((string)Yii::$app->request->headers->get(
         'X-Telegram-Bot-Api-Secret-Token',
         ''
      ));

      return $expected !== ''
         && $received !== ''
         && hash_equals($expected, $received);
   }

   private function labels(string $language): array
   {
      $labels = [
         'ru' => [
            'connected' => "✅ Служебный бот подключён.\n\nТеперь сюда будут приходить уведомления о новых оплаченных подписках.",
            'already_active' => '✅ Служебные уведомления уже активны.',
            'access_denied' => 'Доступ запрещён. Бот предназначен только для сотрудников с ролью admin или techsupport.',
            'employee_not_found' => "Сотрудник не найден по вашему Telegram ID.\n\nСначала подключите этот Telegram-аккаунт к основному боту Meros, затем повторите /start.",
            'staff_account_already_bound' => 'Этот аккаунт сотрудника уже подключён к другому Telegram.',
            'telegram_account_already_bound' => 'Этот Telegram уже подключён к другому аккаунту сотрудника.',
            'bind_save_failed' => 'Не удалось сохранить подключение. Попробуйте позже.',
            'invalid_payload' => 'Telegram передал некорректные данные пользователя.',
            'access_revoked' => 'Служебные уведомления отключены: роль admin/techsupport больше не назначена.',
            'start_required' => 'Отправьте /start для проверки доступа сотрудника.',
         ],
         'uz' => [
            'connected' => "✅ Xodimlar boti ulandi.\n\nEndi yangi to‘langan obunalar haqida xabarlar shu yerga keladi.",
            'already_active' => '✅ Xizmat bildirishnomalari allaqachon faol.',
            'access_denied' => 'Kirish taqiqlangan. Bot faqat admin yoki techsupport rolidagi xodimlar uchun.',
            'employee_not_found' => "Telegram ID bo‘yicha xodim topilmadi.\n\nAvval shu Telegram akkauntini Meros asosiy botiga ulang, keyin /start ni qayta yuboring.",
            'staff_account_already_bound' => 'Bu xodim akkaunti boshqa Telegram’ga ulangan.',
            'telegram_account_already_bound' => 'Bu Telegram boshqa xodim akkauntiga ulangan.',
            'bind_save_failed' => 'Ulanishni saqlab bo‘lmadi. Keyinroq urinib ko‘ring.',
            'invalid_payload' => 'Telegram foydalanuvchi ma’lumotlarini noto‘g‘ri yubordi.',
            'access_revoked' => 'admin/techsupport roli olib tashlangani uchun bildirishnomalar o‘chirildi.',
            'start_required' => 'Xodim kirishini tekshirish uchun /start yuboring.',
         ],
         'en' => [
            'connected' => "✅ Staff bot connected.\n\nNew paid-subscription notifications will be delivered here.",
            'already_active' => '✅ Staff notifications are already active.',
            'access_denied' => 'Access denied. This bot is only for employees with the admin or techsupport role.',
            'employee_not_found' => "No employee was found for your Telegram ID.\n\nConnect this Telegram account to the main Meros bot first, then send /start again.",
            'staff_account_already_bound' => 'This employee account is already connected to another Telegram account.',
            'telegram_account_already_bound' => 'This Telegram account is already connected to another employee account.',
            'bind_save_failed' => 'Could not save the connection. Try again later.',
            'invalid_payload' => 'Telegram supplied invalid user data.',
            'access_revoked' => 'Staff notifications were disabled because the admin/techsupport role was removed.',
            'start_required' => 'Send /start to verify employee access.',
         ],
      ];

      return $labels[$language] ?? $labels['ru'];
   }
}
