<?php

declare(strict_types=1);

namespace common\services;

use common\models\User;
use Yii;
use yii\db\Query;

final class TelegramUserService
{
   public static function bind(
      string $token,
      string $chatId,
      string $username,
      string $telegramLanguage
   ): array {
      $token = trim($token);
      $chatId = trim($chatId);

      if ($token === '' || !preg_match('/^-?\d+$/', $chatId)) {
         return ['ok' => false, 'error' => 'invalid_payload'];
      }

      $user = User::find()
         ->where(['telegram_link_token_hash' => hash('sha256', $token)])
         ->andWhere(['>=', 'telegram_link_expires_at', time()])
         ->one();

      if ($user === null) {
         return ['ok' => false, 'error' => 'token_invalid_or_expired'];
      }

      $alreadyBound = User::find()
         ->where(['telegram_chat_id' => $chatId])
         ->andWhere(['<>', 'id', (int)$user->id])
         ->exists();

      if ($alreadyBound) {
         return ['ok' => false, 'error' => 'telegram_account_already_bound'];
      }

      $user->telegram_chat_id = $chatId;
      $user->telegram_username = $username !== ''
         ? mb_substr($username, 0, 255)
         : null;
      $user->telegram_language = PurchaseMessageBuilder::normalizeLanguage(
         $telegramLanguage
      );
      $user->telegram_connected_at = time();
      $user->telegram_link_token_hash = null;
      $user->telegram_link_expires_at = null;

      if (!$user->save(false, [
         'telegram_chat_id',
         'telegram_username',
         'telegram_language',
         'telegram_connected_at',
         'telegram_link_token_hash',
         'telegram_link_expires_at',
      ])) {
         return ['ok' => false, 'error' => 'bind_save_failed'];
      }

      return [
         'ok' => true,
         'user' => $user,
      ];
   }

   public static function findByChatId(string $chatId): ?User
   {
      $chatId = trim($chatId);
      if ($chatId === '' || !preg_match('/^-?\d+$/', $chatId)) {
         return null;
      }

      return User::findOne(['telegram_chat_id' => $chatId]);
   }

   public static function profile(User $user): array
   {
      return [
         'id' => (int)$user->id,
         'fullname' => (string)self::attribute($user, 'fullname', ''),
         'email' => (string)self::attribute($user, 'email', ''),
         'phone' => (string)self::attribute($user, 'phone', ''),
         'language' => self::resolveLanguage($user),
      ];
   }

   public static function subscriptions(User $user, string $language): array
   {
      $language = PurchaseMessageBuilder::normalizeLanguage($language);

      // If your actual table or column names differ, edit only this query.
      return (new Query())
         ->select([
            'id' => 'us.id',
            'plan_id' => 'us.plan_id',
            'plan_name' => 'sp.name_' . $language,
            'status' => 'us.status',
            'start_date' => 'us.start_date',
            'expires_date' => 'us.expires_date',
         ])
         ->from(['us' => '{{%user_subscriptions}}'])
         ->leftJoin(
            ['sp' => '{{%subscription_plans}}'],
            'sp.id = us.plan_id'
         )
         ->where([
            'us.user_id' => (int)$user->id,
            'us.status' => 1,
         ])
         ->orderBy(['us.expires_date' => SORT_DESC])
         ->all();
   }

   public static function resolveLanguage(User $user): string
   {
      foreach (['language', 'lang', 'telegram_language'] as $attribute) {
         $value = (string)self::attribute($user, $attribute, '');
         if ($value !== '') {
            return PurchaseMessageBuilder::normalizeLanguage($value);
         }
      }

      return PurchaseMessageBuilder::normalizeLanguage(
         (string)Yii::$app->language
      );
   }

   public static function attribute($model, string $attribute, mixed $default = null): mixed
   {
      if (method_exists($model, 'hasAttribute') && $model->hasAttribute($attribute)) {
         return $model->getAttribute($attribute);
      }

      return $default;
   }
}
