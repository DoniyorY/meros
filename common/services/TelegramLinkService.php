<?php

declare(strict_types=1);

namespace common\services;

use common\models\User;
use RuntimeException;
use Yii;

final class TelegramLinkService
{
   public static function createForUser(User $user, int $ttl = 900): string
   {
      $botUsername = ltrim(
         trim((string)(Yii::$app->params['telegramBotUsername'] ?? '')),
         '@'
      );

      if ($botUsername === '') {
         throw new RuntimeException('telegramBotUsername is not configured.');
      }

      $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');

      $user->telegram_link_token_hash = hash('sha256', $token);
      $user->telegram_link_expires_at = time() + $ttl;

      if (!$user->save(false, [
         'telegram_link_token_hash',
         'telegram_link_expires_at',
      ])) {
         throw new RuntimeException('Unable to save Telegram link token.');
      }

      return sprintf(
         'https://t.me/%s?start=%s',
         rawurlencode($botUsername),
         rawurlencode($token)
      );
   }
}
