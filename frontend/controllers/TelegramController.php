<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\services\PurchaseMessageBuilder;
use common\services\TelegramUserService;
use JsonException;
use Throwable;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Response;

final class TelegramController extends Controller
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
      $text = trim((string)($message['text'] ?? ''));
      $username = trim((string)($message['from']['username'] ?? ''));
      $telegramLanguage = PurchaseMessageBuilder::normalizeLanguage(
         (string)($message['from']['language_code'] ?? 'ru')
      );

      try {
         if (preg_match('/^\/start(?:@\w+)?(?:\s+([A-Za-z0-9_-]{1,64}))?$/', $text, $matches)) {
            $this->handleStart(
               $chatId,
               $username,
               $telegramLanguage,
               (string)($matches[1] ?? '')
            );

            return ['ok' => true];
         }

         $language = $this->languageForChat($chatId, $telegramLanguage);

         if ($text === '/menu' || $this->isButton($text, 'start')) {
            $this->showStartMenu($chatId, $language);
         } elseif ($text === '/subscriptions' || $this->isButton($text, 'subscriptions')) {
            $this->showSubscriptions($chatId, $language);
         } elseif ($text === '/info' || $this->isButton($text, 'info')) {
            $this->showProfile($chatId, $language);
         } else {
            Yii::$app->telegramBot->sendMessage(
               $chatId,
               $this->translations()[$language]['unknown'],
               $this->replyKeyboard($language)
            );
         }
      } catch (Throwable $exception) {
         Yii::error([
            'message' => 'Telegram webhook handling failed.',
            'chat_id' => $chatId,
            'exception' => $exception->getMessage(),
            'update' => $update,
         ], 'telegram');

         // Return 200 so Telegram does not retry a broken update forever.
         return ['ok' => false, 'error' => 'webhook_processing_failed'];
      }

      return ['ok' => true];
   }

   public function actionHealth(): array
   {
      Yii::$app->response->format = Response::FORMAT_JSON;

      return [
         'ok' => true,
         'service' => 'meros-telegram-paas',
         'time' => time(),
      ];
   }

   private function handleStart(
      string $chatId,
      string $username,
      string $telegramLanguage,
      string $token
   ): void {
      if ($token !== '') {
         $result = TelegramUserService::bind(
            $token,
            $chatId,
            $username,
            $telegramLanguage
         );

         if (($result['ok'] ?? false) === true) {
            $user = $result['user'];
            $language = TelegramUserService::resolveLanguage($user);

            Yii::$app->telegramBot->sendMessage(
               $chatId,
               $this->translations()[$language]['connected'],
               $this->replyKeyboard($language)
            );

            return;
         }

         $error = (string)($result['error'] ?? 'bind_failed');
         $key = $error === 'telegram_account_already_bound'
            ? 'already_connected'
            : 'invalid_link';

         Yii::$app->telegramBot->sendMessage(
            $chatId,
            $this->translations()[$telegramLanguage][$key],
            $this->replyKeyboard($telegramLanguage)
         );

         return;
      }

      $this->showStartMenu($chatId, $telegramLanguage);
   }

   private function showStartMenu(string $chatId, string $language): void
   {
      $user = TelegramUserService::findByChatId($chatId);
      $key = $user === null ? 'start_unconnected' : 'start_connected';

      Yii::$app->telegramBot->sendMessage(
         $chatId,
         $this->translations()[$language][$key],
         $this->replyKeyboard($language)
      );
   }

   private function showProfile(string $chatId, string $language): void
   {
      $user = TelegramUserService::findByChatId($chatId);
      if ($user === null) {
         $this->sendNotConnected($chatId, $language);
         return;
      }

      $profile = TelegramUserService::profile($user);
      $labels = $this->translations()[$language];

      $text = '<b>' . $labels['profile_title'] . "</b>\n\n"
         . '<b>' . $labels['name'] . ':</b> '
         . Html::encode($profile['fullname'] !== '' ? $profile['fullname'] : '—') . "\n"
         . '<b>Email:</b> '
         . Html::encode($profile['email'] !== '' ? $profile['email'] : '—') . "\n"
         . '<b>' . $labels['phone'] . ':</b> '
         . Html::encode($profile['phone'] !== '' ? $profile['phone'] : '—');

      Yii::$app->telegramBot->sendMessage(
         $chatId,
         $text,
         $this->replyKeyboard($language)
      );
   }

   private function showSubscriptions(string $chatId, string $language): void
   {
      $user = TelegramUserService::findByChatId($chatId);
      if ($user === null) {
         $this->sendNotConnected($chatId, $language);
         return;
      }

      $subscriptions = TelegramUserService::subscriptions($user, $language);
      $labels = $this->translations()[$language];

      if ($subscriptions === []) {
         Yii::$app->telegramBot->sendMessage(
            $chatId,
            $labels['no_subscriptions'],
            $this->replyKeyboard($language)
         );
         return;
      }

      $parts = ['<b>' . $labels['subscriptions_title'] . '</b>'];

      foreach ($subscriptions as $index => $subscription) {
         $name = trim((string)($subscription['plan_name'] ?? ''));
         if ($name === '') {
            $name = $labels['subscription'] . ' #' . (int)$subscription['id'];
         }

         $parts[] = sprintf(
            "<b>%d. %s</b>\n%s: %s\n%s: %s",
            $index + 1,
            Html::encode($name),
            $labels['starts'],
            $this->formatDate($subscription['start_date'] ?? null),
            $labels['expires'],
            $this->formatDate($subscription['expires_date'] ?? null)
         );
      }

      Yii::$app->telegramBot->sendMessage(
         $chatId,
         implode("\n\n", $parts),
         $this->replyKeyboard($language)
      );
   }

   private function sendNotConnected(string $chatId, string $language): void
   {
      Yii::$app->telegramBot->sendMessage(
         $chatId,
         $this->translations()[$language]['not_connected'],
         $this->replyKeyboard($language)
      );
   }

   private function languageForChat(string $chatId, string $fallback): string
   {
      $user = TelegramUserService::findByChatId($chatId);

      return $user === null
         ? $fallback
         : TelegramUserService::resolveLanguage($user);
   }

   private function isWebhookAuthorized(): bool
   {
      $expectedSecret = trim((string)(
         Yii::$app->params['telegramWebhookSecret'] ?? ''
      ));
      $providedSecret = trim((string)Yii::$app->request->headers
         ->get('X-Telegram-Bot-Api-Secret-Token', ''));

      return $expectedSecret !== ''
         && $providedSecret !== ''
         && hash_equals($expectedSecret, $providedSecret);
   }

   private function replyKeyboard(string $language): array
   {
      $buttons = $this->translations()[$language]['buttons'];

      return [
         'keyboard' => [
            [
               ['text' => $buttons['start']],
            ],
            [
               ['text' => $buttons['subscriptions']],
               ['text' => $buttons['info']],
            ],
         ],
         'resize_keyboard' => true,
         'is_persistent' => true,
      ];
   }

   private function isButton(string $text, string $button): bool
   {
      foreach ($this->translations() as $translation) {
         if (($translation['buttons'][$button] ?? null) === $text) {
            return true;
         }
      }

      return false;
   }

   private function formatDate(mixed $value): string
   {
      if ($value === null || $value === '') {
         return '—';
      }

      if (is_numeric($value)) {
         $timestamp = (int)$value;
         if ($timestamp > 0) {
            return Yii::$app->formatter->asDate($timestamp, 'php:d.m.Y');
         }
      }

      try {
         return Yii::$app->formatter->asDate($value, 'php:d.m.Y');
      } catch (Throwable) {
         return Html::encode((string)$value);
      }
   }

   private function translations(): array
   {
      return [
         'ru' => [
            'buttons' => [
               'start' => '🚀 Старт',
               'subscriptions' => '📚 Мои подписки',
               'info' => '👤 Моя информация',
            ],
            'start_connected' => 'Добро пожаловать в бот Meros International Institute. Выберите нужный раздел.',
            'start_unconnected' => 'Добро пожаловать! Чтобы увидеть профиль и подписки, подключите Telegram в личном кабинете сайта.',
            'connected' => '✅ Telegram успешно подключён к вашему аккаунту.',
            'already_connected' => 'Этот Telegram-аккаунт уже привязан к другому пользователю.',
            'invalid_link' => 'Ссылка подключения недействительна или срок её действия истёк. Создайте новую ссылку в личном кабинете.',
            'not_connected' => 'Telegram ещё не подключён к аккаунту. Откройте личный кабинет на сайте и нажмите «Подключить Telegram».',
            'unknown' => 'Не понял команду. Используйте кнопки меню.',
            'profile_title' => 'Моя информация',
            'name' => 'Имя',
            'phone' => 'Телефон',
            'subscriptions_title' => 'Мои подписки',
            'no_subscriptions' => 'Активных подписок пока нет.',
            'subscription' => 'Подписка',
            'starts' => 'Начало',
            'expires' => 'Действует до',
         ],
         'uz' => [
            'buttons' => [
               'start' => '🚀 Boshlash',
               'subscriptions' => '📚 Obunalarim',
               'info' => '👤 Ma’lumotlarim',
            ],
            'start_connected' => 'Meros International Institute botiga xush kelibsiz. Kerakli bo‘limni tanlang.',
            'start_unconnected' => 'Xush kelibsiz! Profil va obunalarni ko‘rish uchun saytdagi shaxsiy kabinet orqali Telegram’ni ulang.',
            'connected' => '✅ Telegram akkauntingizga muvaffaqiyatli ulandi.',
            'already_connected' => 'Bu Telegram akkaunti boshqa foydalanuvchiga ulangan.',
            'invalid_link' => 'Ulanish havolasi noto‘g‘ri yoki muddati tugagan. Shaxsiy kabinetda yangi havola yarating.',
            'not_connected' => 'Telegram hali akkauntga ulanmagan. Saytdagi shaxsiy kabinetda «Telegram’ni ulash» tugmasini bosing.',
            'unknown' => 'Buyruq tushunilmadi. Menyu tugmalaridan foydalaning.',
            'profile_title' => 'Mening ma’lumotlarim',
            'name' => 'Ism',
            'phone' => 'Telefon',
            'subscriptions_title' => 'Mening obunalarim',
            'no_subscriptions' => 'Hozircha faol obunalar yo‘q.',
            'subscription' => 'Obuna',
            'starts' => 'Boshlanishi',
            'expires' => 'Amal qilish muddati',
         ],
         'en' => [
            'buttons' => [
               'start' => '🚀 Start',
               'subscriptions' => '📚 My subscriptions',
               'info' => '👤 My information',
            ],
            'start_connected' => 'Welcome to the Meros International Institute bot. Choose a section.',
            'start_unconnected' => 'Welcome! Connect Telegram from your website profile to view your information and subscriptions.',
            'connected' => '✅ Telegram has been connected to your account.',
            'already_connected' => 'This Telegram account is already linked to another user.',
            'invalid_link' => 'The connection link is invalid or has expired. Generate a new link in your profile.',
            'not_connected' => 'Telegram is not connected yet. Open your website profile and click “Connect Telegram”.',
            'unknown' => 'I did not understand that command. Please use the menu buttons.',
            'profile_title' => 'My information',
            'name' => 'Name',
            'phone' => 'Phone',
            'subscriptions_title' => 'My subscriptions',
            'no_subscriptions' => 'You have no active subscriptions yet.',
            'subscription' => 'Subscription',
            'starts' => 'Starts',
            'expires' => 'Expires',
         ],
      ];
   }
}
