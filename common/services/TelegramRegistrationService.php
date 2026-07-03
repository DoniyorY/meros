<?php

declare(strict_types=1);

namespace common\services;

use common\models\TelegramRegistration;
use common\models\User;
use Throwable;
use Yii;
use yii\db\Expression;

final class TelegramRegistrationService
{
   private const DEFAULT_CODE_TTL = 600;
   private const DEFAULT_RESEND_INTERVAL = 60;
   private const DEFAULT_MAX_ATTEMPTS = 5;

   public static function start(
      string $chatId,
      string $telegramUserId,
      string $username,
      string $language
   ): TelegramRegistration {
      $registration = self::findByChatId($chatId)
         ?? new TelegramRegistration();

      $registration->telegram_chat_id = $chatId;
      $registration->telegram_user_id = $telegramUserId !== ''
         ? $telegramUserId
         : null;
      $registration->telegram_username = $username !== ''
         ? mb_substr($username, 0, 255)
         : null;
      $registration->telegram_language = PurchaseMessageBuilder::normalizeLanguage(
         $language
      );
      $registration->user_id = null;
      $registration->status = TelegramRegistration::STATUS_WAIT_EMAIL;
      $registration->code_hash = null;
      $registration->attempts = 0;
      $registration->code_expires_at = null;
      $registration->last_code_sent_at = null;

      if (!$registration->save(false)) {
         throw new \RuntimeException('Unable to create Telegram registration state.');
      }

      return $registration;
   }

   public static function findByChatId(string $chatId): ?TelegramRegistration
   {
      return TelegramRegistration::findOne([
         'telegram_chat_id' => trim($chatId),
      ]);
   }

   public static function submitEmail(
      TelegramRegistration $registration,
      string $email
   ): array {
      $email = mb_strtolower(trim($email));

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         return ['ok' => false, 'error' => 'invalid_email'];
      }

      $user = self::findUserByEmail($email);
      if ($user === null) {
         return ['ok' => false, 'error' => 'user_not_found'];
      }

      $currentChatId = trim((string)TelegramUserService::attribute(
         $user,
         'telegram_chat_id',
         ''
      ));

      if (
         $currentChatId !== ''
         && $currentChatId !== (string)$registration->telegram_chat_id
      ) {
         return ['ok' => false, 'error' => 'user_account_already_bound'];
      }

      if (self::isCooldownActive($registration)) {
         return [
            'ok' => false,
            'error' => 'resend_cooldown',
            'seconds' => self::cooldownSeconds($registration),
         ];
      }

      return self::issueCode($registration, $user);
   }

   public static function resendCode(TelegramRegistration $registration): array
   {
      if (
         (int)$registration->status !== TelegramRegistration::STATUS_WAIT_CODE
         || empty($registration->user_id)
      ) {
         return ['ok' => false, 'error' => 'email_required'];
      }

      if (self::isCooldownActive($registration)) {
         return [
            'ok' => false,
            'error' => 'resend_cooldown',
            'seconds' => self::cooldownSeconds($registration),
         ];
      }

      $user = User::findOne((int)$registration->user_id);
      if ($user === null) {
         self::cancel((string)$registration->telegram_chat_id);
         return ['ok' => false, 'error' => 'user_not_found'];
      }

      return self::issueCode($registration, $user);
   }

   public static function verifyCode(
      TelegramRegistration $registration,
      string $code
   ): array {
      $code = trim($code);

      if (!preg_match('/^\d{6}$/', $code)) {
         return ['ok' => false, 'error' => 'invalid_code_format'];
      }

      if (
         (int)$registration->status !== TelegramRegistration::STATUS_WAIT_CODE
         || empty($registration->user_id)
         || empty($registration->code_hash)
      ) {
         return ['ok' => false, 'error' => 'email_required'];
      }

      if ((int)$registration->code_expires_at < time()) {
         return ['ok' => false, 'error' => 'code_expired'];
      }

      $maxAttempts = self::maxAttempts();
      if ((int)$registration->attempts >= $maxAttempts) {
         self::cancel((string)$registration->telegram_chat_id);
         return ['ok' => false, 'error' => 'too_many_attempts'];
      }

      if (!Yii::$app->security->validatePassword(
         $code,
         (string)$registration->code_hash
      )) {
         $registration->attempts = (int)$registration->attempts + 1;
         $registration->save(false, ['attempts', 'updated_at']);

         $remaining = max(0, $maxAttempts - (int)$registration->attempts);
         if ($remaining === 0) {
            self::cancel((string)$registration->telegram_chat_id);
            return ['ok' => false, 'error' => 'too_many_attempts'];
         }

         return [
            'ok' => false,
            'error' => 'invalid_code',
            'remaining' => $remaining,
         ];
      }

      $transaction = Yii::$app->db->beginTransaction();

      try {
         $user = User::findOne((int)$registration->user_id);
         if ($user === null) {
            throw new \RuntimeException('User not found during Telegram binding.');
         }

         $result = TelegramUserService::attachTelegram(
            $user,
            (string)$registration->telegram_chat_id,
            (string)$registration->telegram_user_id,
            (string)$registration->telegram_username,
            (string)$registration->telegram_language
         );

         if (($result['ok'] ?? false) !== true) {
            $transaction->rollBack();
            return $result;
         }

         $registration->delete();
         $transaction->commit();

         return [
            'ok' => true,
            'user' => $result['user'],
         ];
      } catch (Throwable $exception) {
         if ($transaction->isActive) {
            $transaction->rollBack();
         }

         Yii::error([
            'message' => 'Telegram registration verification failed.',
            'registration_id' => (int)$registration->id,
            'exception' => $exception->getMessage(),
         ], 'telegram');

         return ['ok' => false, 'error' => 'bind_save_failed'];
      }
   }

   public static function cancel(string $chatId): void
   {
      TelegramRegistration::deleteAll([
         'telegram_chat_id' => trim($chatId),
      ]);
   }

   public static function maskEmail(string $email): string
   {
      $email = trim($email);
      if (!str_contains($email, '@')) {
         return $email;
      }

      [$local, $domain] = explode('@', $email, 2);
      $length = mb_strlen($local);

      if ($length <= 2) {
         $maskedLocal = mb_substr($local, 0, 1) . '***';
      } else {
         $maskedLocal = mb_substr($local, 0, 2)
            . str_repeat('*', min(5, max(3, $length - 2)));
      }

      return $maskedLocal . '@' . $domain;
   }

   private static function issueCode(
      TelegramRegistration $registration,
      User $user
   ): array {
      $email = trim((string)TelegramUserService::attribute($user, 'email', ''));
      if ($email === '') {
         return ['ok' => false, 'error' => 'email_missing'];
      }

      $code = (string)random_int(100000, 999999);
      $codeHash = Yii::$app->security->generatePasswordHash($code);
      $expiresAt = time() + self::codeTtl();

      $snapshot = [
         'user_id' => $registration->user_id,
         'status' => $registration->status,
         'code_hash' => $registration->code_hash,
         'attempts' => $registration->attempts,
         'code_expires_at' => $registration->code_expires_at,
         'last_code_sent_at' => $registration->last_code_sent_at,
      ];

      $registration->user_id = (int)$user->id;
      $registration->status = TelegramRegistration::STATUS_WAIT_CODE;
      $registration->code_hash = $codeHash;
      $registration->attempts = 0;
      $registration->code_expires_at = $expiresAt;
      $registration->last_code_sent_at = time();

      if (!$registration->save(false)) {
         return ['ok' => false, 'error' => 'registration_save_failed'];
      }
      
      try {
         $sent = self::sendVerificationEmail(
            $user,
            $code,
            (string)$registration->telegram_language,
            self::codeTtl()
         );
         
         if (!$sent) {
            Yii::error([
               'message' => 'Telegram verification email send() returned false.',
               'user_id' => (int)$user->id,
               'email' => (string)$user->email,
               'registration_id' => (int)$registration->id,
               'mailer_class' => get_class(Yii::$app->mailer),
            ], 'telegram');
         }
      } catch (Throwable $exception) {
         Yii::error([
            'message' => 'Telegram verification email threw an exception.',
            'user_id' => (int)$user->id,
            'email' => (string)$user->email,
            'registration_id' => (int)$registration->id,
            'exception_class' => get_class($exception),
            'exception' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
         ], 'telegram');
         
         $sent = false;
      }

      if (!$sent) {
         foreach ($snapshot as $attribute => $value) {
            $registration->setAttribute($attribute, $value);
         }
         $registration->save(false);

         return ['ok' => false, 'error' => 'mail_failed'];
      }

      return [
         'ok' => true,
         'masked_email' => self::maskEmail($email),
         'expires_in' => self::codeTtl(),
      ];
   }

   private static function sendVerificationEmail(
      User $user,
      string $code,
      string $language,
      int $ttl
   ): bool {
      $language = PurchaseMessageBuilder::normalizeLanguage($language);
      $subjects = [
         'ru' => 'Код подключения Telegram',
         'uz' => 'Telegram ulash kodi',
         'en' => 'Telegram connection code',
      ];

      return Yii::$app->mailer
         ->compose(
            [
               'html' => 'telegram-verification-html',
               'text' => 'telegram-verification-text',
            ],
            [
               'user' => $user,
               'code' => $code,
               'language' => $language,
               'expiresMinutes' => (int)ceil($ttl / 60),
            ]
         )
         ->setTo((string)$user->email)
         ->setSubject($subjects[$language])
         ->send();
   }

   private static function findUserByEmail(string $email): ?User
   {
      $user = User::findOne(['email' => $email]);
      if ($user !== null) {
         return $user;
      }

      return User::find()
         ->where(new Expression(
            'LOWER([[email]]) = :email',
            [':email' => mb_strtolower($email)]
         ))
         ->one();
   }

   private static function isCooldownActive(
      TelegramRegistration $registration
   ): bool {
      return (int)$registration->last_code_sent_at > 0
         && (int)$registration->last_code_sent_at + self::resendInterval() > time();
   }

   private static function cooldownSeconds(
      TelegramRegistration $registration
   ): int {
      return max(
         1,
         (int)$registration->last_code_sent_at
            + self::resendInterval()
            - time()
      );
   }

   private static function codeTtl(): int
   {
      return max(60, (int)(
         Yii::$app->params['telegramRegistrationCodeTtl']
         ?? self::DEFAULT_CODE_TTL
      ));
   }

   private static function resendInterval(): int
   {
      return max(10, (int)(
         Yii::$app->params['telegramRegistrationResendInterval']
         ?? self::DEFAULT_RESEND_INTERVAL
      ));
   }

   private static function maxAttempts(): int
   {
      return max(1, (int)(
         Yii::$app->params['telegramRegistrationMaxAttempts']
         ?? self::DEFAULT_MAX_ATTEMPTS
      ));
   }
}
