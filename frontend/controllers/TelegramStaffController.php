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
         $labels = $this->labels($language);
         
         $connectedUser = TelegramStaffUserService::findByStaffChatId($chatId)
            ?? TelegramStaffUserService::findByStaffTelegramId($telegramUserId);
         
         if ($connectedUser !== null) {
            if (!TelegramStaffUserService::hasAllowedRole($connectedUser)) {
               TelegramStaffUserService::disconnect($connectedUser);
               Yii::$app->telegramStaffBot->sendMessage(
                  $chatId,
                  $labels['access_revoked'],
                  ['remove_keyboard' => true]
               );
               
               return ['ok' => true, 'status' => 'access_revoked'];
            }
            
            $result = TelegramStaffUserService::attachStaffTelegram(
               $connectedUser,
               $chatId,
               $telegramUserId,
               $username
            );
            
            if (($result['ok'] ?? false) !== true) {
               $error = (string)($result['error'] ?? 'bind_save_failed');
               Yii::$app->telegramStaffBot->sendMessage(
                  $chatId,
                  $labels[$error] ?? $labels['bind_save_failed'],
                  ['remove_keyboard' => true]
               );
               
               return ['ok' => true, 'status' => $error];
            }
            
            Yii::$app->telegramStaffBot->sendMessage(
               $chatId,
               $labels['already_active'],
               ['remove_keyboard' => true]
            );
            
            return ['ok' => true, 'status' => 'already_active'];
         }
         
         $startToken = $this->extractStartToken($text);
         if ($startToken === false) {
            Yii::$app->telegramStaffBot->sendMessage(
               $chatId,
               $labels['start_required'],
               ['remove_keyboard' => true]
            );
            
            return ['ok' => true, 'status' => 'start_required'];
         }
         
         if ($startToken === null) {
            Yii::$app->telegramStaffBot->sendMessage(
               $chatId,
               $labels['connect_link_required'],
               ['remove_keyboard' => true]
            );
            
            return ['ok' => true, 'status' => 'connect_link_required'];
         }
         
         $result = TelegramStaffUserService::attachByToken(
            $startToken,
            $chatId,
            $telegramUserId,
            $username
         );
         
         if (($result['ok'] ?? false) === true) {
            Yii::$app->telegramStaffBot->sendMessage(
               $chatId,
               $labels['connected'],
               ['remove_keyboard' => true]
            );
            
            return ['ok' => true, 'status' => 'connected'];
         }
         
         $error = (string)($result['error'] ?? 'access_denied');
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
   
   /**
    * false: сообщение не является /start
    * null: обычный /start без токена
    * string: /start TOKEN
    */
   private function extractStartToken(string $text): string|false|null
   {
      if (!preg_match(
         '/^\/start(?:@\w+)?(?:\s+([A-Za-z0-9_-]{16,64}))?$/',
         $text,
         $matches
      )) {
         return false;
      }
      
      return isset($matches[1]) && $matches[1] !== ''
         ? $matches[1]
         : null;
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
            'connect_link_required' => "Для первого подключения откройте персональную ссылку из своей учётной записи сотрудника.\n\nОбычный /start не привязывает новый аккаунт.",
            'invalid_or_expired_token' => 'Ссылка подключения недействительна или устарела. Создайте новую ссылку в учётной записи сотрудника.',
            'staff_account_already_bound' => 'Этот аккаунт сотрудника уже подключён к другому Telegram.',
            'telegram_account_already_bound' => 'Этот Telegram уже подключён к другому аккаунту сотрудника.',
            'bind_save_failed' => 'Не удалось сохранить подключение. Попробуйте позже.',
            'invalid_payload' => 'Telegram передал некорректные данные пользователя.',
            'access_revoked' => 'Служебные уведомления отключены: роль admin/techsupport больше не назначена.',
            'start_required' => 'Откройте персональную ссылку подключения или отправьте /start.',
         ],
         'uz' => [
            'connected' => "✅ Xodimlar boti ulandi.\n\nEndi yangi to‘langan obunalar haqida xabarlar shu yerga keladi.",
            'already_active' => '✅ Xizmat bildirishnomalari allaqachon faol.',
            'access_denied' => 'Kirish taqiqlangan. Bot faqat admin yoki techsupport rolidagi xodimlar uchun.',
            'connect_link_required' => "Birinchi ulanish uchun xodim akkauntingizdagi shaxsiy havolani oching.\n\nOddiy /start yangi akkauntni ulamaydi.",
            'invalid_or_expired_token' => 'Ulanish havolasi yaroqsiz yoki eskirgan. Xodim akkauntingizda yangi havola yarating.',
            'staff_account_already_bound' => 'Bu xodim akkaunti boshqa Telegram’ga ulangan.',
            'telegram_account_already_bound' => 'Bu Telegram boshqa xodim akkauntiga ulangan.',
            'bind_save_failed' => 'Ulanishni saqlab bo‘lmadi. Keyinroq urinib ko‘ring.',
            'invalid_payload' => 'Telegram foydalanuvchi ma’lumotlarini noto‘g‘ri yubordi.',
            'access_revoked' => 'admin/techsupport roli olib tashlangani uchun bildirishnomalar o‘chirildi.',
            'start_required' => 'Shaxsiy ulanish havolasini oching yoki /start yuboring.',
         ],
         'en' => [
            'connected' => "✅ Staff bot connected.\n\nNew paid-subscription notifications will be delivered here.",
            'already_active' => '✅ Staff notifications are already active.',
            'access_denied' => 'Access denied. This bot is only for employees with the admin or techsupport role.',
            'connect_link_required' => "For the first connection, open the personal link from your employee account.\n\nA plain /start does not connect a new account.",
            'invalid_or_expired_token' => 'The connection link is invalid or expired. Generate a new link in your employee account.',
            'staff_account_already_bound' => 'This employee account is already connected to another Telegram account.',
            'telegram_account_already_bound' => 'This Telegram account is already connected to another employee account.',
            'bind_save_failed' => 'Could not save the connection. Try again later.',
            'invalid_payload' => 'Telegram supplied invalid user data.',
            'access_revoked' => 'Staff notifications were disabled because the admin/techsupport role was removed.',
            'start_required' => 'Open your personal connection link or send /start.',
         ],
      ];
      
      return $labels[$language] ?? $labels['ru'];
   }
}
