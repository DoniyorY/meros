<?php

declare(strict_types=1);

namespace common\services;

use common\models\User;
use RuntimeException;
use Yii;

final class TelegramStaffLinkService
{
   public static function createConnectLink(User $user): string
   {
      if (!TelegramStaffUserService::hasAllowedRole($user)) {
         throw new RuntimeException(
            'Only users with an allowed staff role can connect the staff bot.'
         );
      }

      $username = trim((string)(
         Yii::$app->params['telegramStaffBotUsername'] ?? ''
      ));

      if ($username === '') {
         throw new RuntimeException('telegramStaffBotUsername is not configured.');
      }

      $username = ltrim($username, '@');
      $token = Yii::$app->security->generateRandomString(48);
      $tokenHash = hash('sha256', $token);
      $ttl = max(60, (int)(
         Yii::$app->params['telegramStaffConnectTokenTtl'] ?? 600
      ));

      $user->setAttribute('staff_telegram_bind_token_hash', $tokenHash);
      $user->setAttribute('staff_telegram_bind_expires_at', time() + $ttl);

      if (!$user->save(false, [
         'staff_telegram_bind_token_hash',
         'staff_telegram_bind_expires_at',
      ])) {
         throw new RuntimeException('Unable to save staff Telegram connect token.');
      }

      return sprintf(
         'https://t.me/%s?start=%s',
         rawurlencode($username),
         rawurlencode($token)
      );
   }

   public static function findUserByToken(string $token): ?User
   {
      $token = trim($token);

      if (
         $token === ''
         || !preg_match('/^[A-Za-z0-9_-]{16,64}$/', $token)
      ) {
         return null;
      }

      $user = User::findOne([
         'staff_telegram_bind_token_hash' => hash('sha256', $token),
      ]);

      if ($user === null) {
         return null;
      }

      $expiresAt = (int)TelegramStaffUserService::attribute(
         $user,
         'staff_telegram_bind_expires_at',
         0
      );

      if ($expiresAt < time()) {
         self::clearToken($user);
         return null;
      }

      if (!TelegramStaffUserService::hasAllowedRole($user)) {
         self::clearToken($user);
         return null;
      }

      return $user;
   }

   public static function clearToken(User $user): bool
   {
      $user->setAttribute('staff_telegram_bind_token_hash', null);
      $user->setAttribute('staff_telegram_bind_expires_at', null);

      return $user->save(false, [
         'staff_telegram_bind_token_hash',
         'staff_telegram_bind_expires_at',
      ]);
   }
}
