<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\TelegramRegistration;
use common\services\PurchaseMessageBuilder;
use common\services\TelegramRegistrationService;
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
      $telegramUserId = (string)($message['from']['id'] ?? '');
      $text = trim((string)($message['text'] ?? ''));
      $username = trim((string)($message['from']['username'] ?? ''));
      $telegramLanguage = PurchaseMessageBuilder::normalizeLanguage(
         (string)($message['from']['language_code'] ?? 'ru')
      );
      
      try {
         if (preg_match('/^\/start(?:@\w+)?(?:\s+([A-Za-z0-9_-]{1,64}))?$/', $text, $matches)) {
            $this->handleStart(
               $chatId,
               $telegramUserId,
               $username,
               $telegramLanguage,
               (string)($matches[1] ?? '')
            );
            
            return ['ok' => true];
         }
         
         $connectedUser = TelegramUserService::findByChatId($chatId);
         if ($connectedUser === null) {
            $this->handleRegistrationMessage(
               $chatId,
               $telegramUserId,
               $username,
               $telegramLanguage,
               $text
            );
            
            return ['ok' => true];
         }
         
         $language = TelegramUserService::resolveLanguage($connectedUser);
         
         if ($text === '/menu' || $this->isButton($text, 'start')) {
            $this->showStartMenu($chatId, $language);
         } elseif ($text === '/subscriptions' || $this->isButton($text, 'subscriptions')) {
            $this->showSubscriptions($chatId, $language);
         } elseif ($text === '/info' || $this->isButton($text, 'info')) {
            $this->showProfile($chatId, $language);
         } elseif ($text === '/cancel' || $this->isButton($text, 'cancel')) {
            TelegramRegistrationService::cancel($chatId);
            $this->showStartMenu($chatId, $language);
         } else {
            Yii::$app->telegramBot->sendMessage(
               $chatId,
               $this->translations()[$language]['unknown'],
               $this->mainKeyboard($language)
            );
         }
      } catch (Throwable $exception) {
         Yii::error([
            'message' => 'Telegram webhook handling failed.',
            'chat_id' => $chatId,
            'exception' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'update' => $update,
         ], 'telegram');
         
         // Always return HTTP 200 so Telegram does not retry one broken update forever.
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
      string $telegramUserId,
      string $username,
      string $telegramLanguage,
      string $token
   ): void {
      if ($token !== '') {
         $result = TelegramUserService::bind(
            $token,
            $chatId,
            $username,
            $telegramLanguage,
            $telegramUserId
         );
         
         if (($result['ok'] ?? false) === true) {
            TelegramRegistrationService::cancel($chatId);
            $user = $result['user'];
            $language = TelegramUserService::resolveLanguage($user);
            
            Yii::$app->telegramBot->sendMessage(
               $chatId,
               $this->translations()[$language]['connected'],
               $this->mainKeyboard($language)
            );
            
            return;
         }
         
         $error = (string)($result['error'] ?? 'bind_failed');
         $key = in_array($error, [
            'telegram_account_already_bound',
            'user_account_already_bound',
         ], true)
            ? 'already_connected'
            : 'invalid_link';
         
         TelegramRegistrationService::start(
            $chatId,
            $telegramUserId,
            $username,
            $telegramLanguage
         );
         
         Yii::$app->telegramBot->sendMessage(
            $chatId,
            $this->translations()[$telegramLanguage][$key],
            $this->registrationEmailKeyboard($telegramLanguage)
         );
         
         return;
      }
      
      $user = TelegramUserService::findByChatId($chatId);
      if ($user !== null) {
         $language = TelegramUserService::resolveLanguage($user);
         $this->showStartMenu($chatId, $language);
         return;
      }
      
      TelegramRegistrationService::start(
         $chatId,
         $telegramUserId,
         $username,
         $telegramLanguage
      );
      
      Yii::$app->telegramBot->sendMessage(
         $chatId,
         $this->translations()[$telegramLanguage]['registration_welcome'],
         $this->registrationEmailKeyboard($telegramLanguage)
      );
   }
   
   private function handleRegistrationMessage(
      string $chatId,
      string $telegramUserId,
      string $username,
      string $language,
      string $text
   ): void {
      $labels = $this->translations()[$language];
      
      if ($text === '/cancel' || $this->isButton($text, 'cancel')) {
         TelegramRegistrationService::cancel($chatId);
         Yii::$app->telegramBot->sendMessage(
            $chatId,
            $labels['registration_cancelled'],
            ['remove_keyboard' => true]
         );
         return;
      }
      
      $registration = TelegramRegistrationService::findByChatId($chatId);
      if ($registration === null) {
         $registration = TelegramRegistrationService::start(
            $chatId,
            $telegramUserId,
            $username,
            $language
         );
         
         Yii::$app->telegramBot->sendMessage(
            $chatId,
            $labels['registration_welcome'],
            $this->registrationEmailKeyboard($language)
         );
         return;
      }
      
      // Refresh Telegram metadata while the registration is in progress.
      $registration->telegram_user_id = $telegramUserId !== ''
         ? $telegramUserId
         : $registration->telegram_user_id;
      $registration->telegram_username = $username !== ''
         ? mb_substr($username, 0, 255)
         : $registration->telegram_username;
      $registration->telegram_language = $language;
      $registration->save(false);
      
      if ($text === '/resend' || $this->isButton($text, 'resend')) {
         $result = TelegramRegistrationService::resendCode($registration);
         $this->sendRegistrationResult($chatId, $language, $result);
         return;
      }
      
      if ((int)$registration->status === TelegramRegistration::STATUS_WAIT_EMAIL) {
         $result = TelegramRegistrationService::submitEmail(
            $registration,
            $text
         );
         $this->sendRegistrationResult($chatId, $language, $result);
         return;
      }
      
      if ((int)$registration->status === TelegramRegistration::STATUS_WAIT_CODE) {
         $result = TelegramRegistrationService::verifyCode(
            $registration,
            $text
         );
         $this->sendRegistrationResult($chatId, $language, $result);
         return;
      }
      
      TelegramRegistrationService::start(
         $chatId,
         $telegramUserId,
         $username,
         $language
      );
      
      Yii::$app->telegramBot->sendMessage(
         $chatId,
         $labels['registration_welcome'],
         $this->registrationEmailKeyboard($language)
      );
   }
   
   private function sendRegistrationResult(
      string $chatId,
      string $language,
      array $result
   ): void {
      $labels = $this->translations()[$language];
      
      if (($result['ok'] ?? false) === true) {
         if (isset($result['user'])) {
            $userLanguage = TelegramUserService::resolveLanguage($result['user']);
            Yii::$app->telegramBot->sendMessage(
               $chatId,
               $this->translations()[$userLanguage]['connected'],
               $this->mainKeyboard($userLanguage)
            );
            return;
         }
         
         $maskedEmail = Html::encode((string)($result['masked_email'] ?? ''));
         Yii::$app->telegramBot->sendMessage(
            $chatId,
            str_replace('{email}', $maskedEmail, $labels['code_sent']),
            $this->registrationCodeKeyboard($language)
         );
         return;
      }
      
      $error = (string)($result['error'] ?? 'registration_failed');
      
      if ($error === 'invalid_email') {
         $message = $labels['invalid_email'];
         $keyboard = $this->registrationEmailKeyboard($language);
      } elseif ($error === 'user_not_found') {
         $message = $labels['user_not_found'];
         $keyboard = $this->registrationEmailKeyboard($language);
      } elseif ($error === 'user_account_already_bound' || $error === 'telegram_account_already_bound') {
         $message = $labels['already_connected'];
         $keyboard = ['remove_keyboard' => true];
      } elseif ($error === 'resend_cooldown') {
         $message = str_replace(
            '{seconds}',
            (string)($result['seconds'] ?? 60),
            $labels['resend_cooldown']
         );
         $keyboard = $this->registrationCodeKeyboard($language);
      } elseif ($error === 'invalid_code_format') {
         $message = $labels['invalid_code_format'];
         $keyboard = $this->registrationCodeKeyboard($language);
      } elseif ($error === 'invalid_code') {
         $message = str_replace(
            '{remaining}',
            (string)($result['remaining'] ?? 0),
            $labels['invalid_code']
         );
         $keyboard = $this->registrationCodeKeyboard($language);
      } elseif ($error === 'code_expired') {
         $message = $labels['code_expired'];
         $keyboard = $this->registrationCodeKeyboard($language);
      } elseif ($error === 'too_many_attempts') {
         $message = $labels['too_many_attempts'];
         $keyboard = ['remove_keyboard' => true];
      } elseif ($error === 'email_required') {
         $message = $labels['email_required'];
         $keyboard = $this->registrationEmailKeyboard($language);
      } elseif ($error === 'mail_failed') {
         $message = $labels['mail_failed'];
         $keyboard = $this->registrationEmailKeyboard($language);
      } else {
         $message = $labels['registration_failed'];
         $keyboard = $this->registrationEmailKeyboard($language);
      }
      
      Yii::$app->telegramBot->sendMessage(
         $chatId,
         $message,
         $keyboard
      );
   }
   
   private function showStartMenu(string $chatId, string $language): void
   {
      Yii::$app->telegramBot->sendMessage(
         $chatId,
         $this->translations()[$language]['start_connected'],
         $this->mainKeyboard($language)
      );
   }
   
   private function showProfile(string $chatId, string $language): void
   {
      $user = TelegramUserService::findByChatId($chatId);
      if ($user === null) {
         $this->restartRegistration($chatId, $language);
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
         $this->mainKeyboard($language)
      );
   }
   
   private function showSubscriptions(string $chatId, string $language): void
   {
      $user = TelegramUserService::findByChatId($chatId);
      if ($user === null) {
         $this->restartRegistration($chatId, $language);
         return;
      }
      
      $subscriptions = TelegramUserService::subscriptions($user, $language);
      $labels = $this->translations()[$language];
      
      if ($subscriptions === []) {
         Yii::$app->telegramBot->sendMessage(
            $chatId,
            $labels['no_subscriptions'],
            $this->mainKeyboard($language)
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
         $this->mainKeyboard($language)
      );
   }
   
   private function restartRegistration(string $chatId, string $language): void
   {
      TelegramRegistrationService::start($chatId, '', '', $language);
      Yii::$app->telegramBot->sendMessage(
         $chatId,
         $this->translations()[$language]['registration_welcome'],
         $this->registrationEmailKeyboard($language)
      );
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
   
   private function mainKeyboard(string $language): array
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
   
   private function registrationEmailKeyboard(string $language): array
   {
      $buttons = $this->translations()[$language]['buttons'];
      
      return [
         'keyboard' => [
            [
               ['text' => $buttons['cancel']],
            ],
         ],
         'resize_keyboard' => true,
         'one_time_keyboard' => false,
      ];
   }
   
   private function registrationCodeKeyboard(string $language): array
   {
      $buttons = $this->translations()[$language]['buttons'];
      
      return [
         'keyboard' => [
            [
               ['text' => $buttons['resend']],
            ],
            [
               ['text' => $buttons['cancel']],
            ],
         ],
         'resize_keyboard' => true,
         'one_time_keyboard' => false,
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
               'resend' => '🔄 Отправить код повторно',
               'cancel' => '❌ Отменить подключение',
            ],
            'start_connected' => 'Добро пожаловать в бот Meros International Institute. Выберите нужный раздел.',
            'registration_welcome' => "Добро пожаловать в Meros International Institute!\n\nЧтобы подключить Telegram к вашему аккаунту, отправьте email, который использовали при регистрации на сайте.",
            'connected' => '✅ Telegram успешно подключён к вашему аккаунту. Теперь доступны профиль, подписки и уведомления о покупках.',
            'already_connected' => 'Этот аккаунт уже подключён к другому Telegram. Для смены привязки отключите старый аккаунт в личном кабинете или обратитесь в поддержку.',
            'invalid_link' => 'Ссылка подключения недействительна или срок её действия истёк. Отправьте /start и подключитесь через email.',
            'invalid_email' => 'Введите корректный email, например: name@example.com',
            'user_not_found' => 'Пользователь с таким email не найден. Проверьте адрес и попробуйте ещё раз.',
            'code_sent' => "Мы отправили шестизначный код на адрес <b>{email}</b>.\n\nВведите код в течение 10 минут.",
            'resend_cooldown' => 'Новый код можно запросить через {seconds} сек.',
            'invalid_code_format' => 'Код должен состоять из 6 цифр.',
            'invalid_code' => 'Неверный код. Осталось попыток: {remaining}.',
            'code_expired' => 'Срок действия кода истёк. Нажмите «Отправить код повторно».',
            'too_many_attempts' => 'Превышено количество попыток. Отправьте /start и начните подключение заново.',
            'email_required' => 'Сначала отправьте email, который использовали при регистрации.',
            'mail_failed' => 'Не удалось отправить письмо с кодом. Попробуйте ещё раз позже.',
            'registration_failed' => 'Не удалось завершить подключение. Отправьте /start и попробуйте снова.',
            'registration_cancelled' => 'Подключение отменено. Чтобы начать заново, отправьте /start.',
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
               'resend' => '🔄 Kodni qayta yuborish',
               'cancel' => '❌ Ulanishni bekor qilish',
            ],
            'start_connected' => 'Meros International Institute botiga xush kelibsiz. Kerakli bo‘limni tanlang.',
            'registration_welcome' => "Meros International Institute botiga xush kelibsiz!\n\nTelegram’ni akkauntingizga ulash uchun saytda ro‘yxatdan o‘tishda ishlatgan emailingizni yuboring.",
            'connected' => '✅ Telegram akkauntingizga muvaffaqiyatli ulandi. Endi profil, obunalar va xarid bildirishnomalari mavjud.',
            'already_connected' => 'Bu akkaunt boshqa Telegram’ga ulangan. Eski ulanishni shaxsiy kabinetda uzing yoki yordam xizmatiga murojaat qiling.',
            'invalid_link' => 'Ulanish havolasi noto‘g‘ri yoki muddati tugagan. /start yuboring va email orqali ulang.',
            'invalid_email' => 'To‘g‘ri email kiriting, masalan: name@example.com',
            'user_not_found' => 'Bu email bilan foydalanuvchi topilmadi. Manzilni tekshirib, qayta urinib ko‘ring.',
            'code_sent' => "Olti xonali kod <b>{email}</b> manziliga yuborildi.\n\nKodni 10 daqiqa ichida kiriting.",
            'resend_cooldown' => 'Yangi kodni {seconds} soniyadan keyin so‘rashingiz mumkin.',
            'invalid_code_format' => 'Kod 6 ta raqamdan iborat bo‘lishi kerak.',
            'invalid_code' => 'Kod noto‘g‘ri. Qolgan urinishlar: {remaining}.',
            'code_expired' => 'Kodning amal qilish muddati tugadi. «Kodni qayta yuborish» tugmasini bosing.',
            'too_many_attempts' => 'Urinishlar soni oshib ketdi. /start yuborib qaytadan boshlang.',
            'email_required' => 'Avval ro‘yxatdan o‘tishda ishlatgan emailingizni yuboring.',
            'mail_failed' => 'Kodli xatni yuborib bo‘lmadi. Keyinroq qayta urinib ko‘ring.',
            'registration_failed' => 'Ulanishni yakunlab bo‘lmadi. /start yuborib qayta urinib ko‘ring.',
            'registration_cancelled' => 'Ulanish bekor qilindi. Qayta boshlash uchun /start yuboring.',
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
               'resend' => '🔄 Resend code',
               'cancel' => '❌ Cancel connection',
            ],
            'start_connected' => 'Welcome to the Meros International Institute bot. Choose a section.',
            'registration_welcome' => "Welcome to Meros International Institute!\n\nTo connect Telegram to your account, send the email you used to register on the website.",
            'connected' => '✅ Telegram has been connected to your account. Your profile, subscriptions and purchase notifications are now available.',
            'already_connected' => 'This account is already connected to another Telegram. Disconnect the old account in your profile or contact support.',
            'invalid_link' => 'The connection link is invalid or expired. Send /start and connect using your email.',
            'invalid_email' => 'Enter a valid email, for example: name@example.com',
            'user_not_found' => 'No user was found with that email. Check the address and try again.',
            'code_sent' => "We sent a six-digit code to <b>{email}</b>.\n\nEnter it within 10 minutes.",
            'resend_cooldown' => 'You can request a new code in {seconds} seconds.',
            'invalid_code_format' => 'The code must contain 6 digits.',
            'invalid_code' => 'Incorrect code. Attempts remaining: {remaining}.',
            'code_expired' => 'The code has expired. Press “Resend code”.',
            'too_many_attempts' => 'Too many attempts. Send /start and begin the connection again.',
            'email_required' => 'First send the email you used to register.',
            'mail_failed' => 'The verification email could not be sent. Try again later.',
            'registration_failed' => 'The connection could not be completed. Send /start and try again.',
            'registration_cancelled' => 'Connection cancelled. Send /start to begin again.',
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
