<?php

declare(strict_types=1);

namespace common\services;

use common\models\User;
use Yii;

final class TelegramStaffUserService
{
   public static function findByStaffChatId(string $chatId): ?User
   {
      $chatId = trim($chatId);
      if ($chatId === '' || !preg_match('/^-?\d+$/', $chatId)) {
         return null;
      }

      return User::findOne(['staff_telegram_chat_id' => $chatId]);
   }

   public static function findByStaffTelegramId(
      string $telegramUserId
   ): ?User {
      $telegramUserId = trim($telegramUserId);
      if ($telegramUserId === '' || !preg_match('/^\d+$/', $telegramUserId)) {
         return null;
      }

      return User::findOne(['staff_telegram_id' => $telegramUserId]);
   }

   /**
    * Первая независимая привязка staff-бота выполняется только по одноразовому
    * токену, который был создан для уже авторизованного сотрудника.
    */
   public static function attachByToken(
      string $token,
      string $chatId,
      string $telegramUserId,
      string $username
   ): array {
      $user = TelegramStaffLinkService::findUserByToken($token);
      if ($user === null) {
         return ['ok' => false, 'error' => 'invalid_or_expired_token'];
      }

      $result = self::attachStaffTelegram(
         $user,
         $chatId,
         $telegramUserId,
         $username
      );

      if (($result['ok'] ?? false) === true) {
         TelegramStaffLinkService::clearToken($user);
      }

      return $result;
   }

   public static function attachStaffTelegram(
      User $user,
      string $chatId,
      string $telegramUserId,
      string $username
   ): array {
      $chatId = trim($chatId);
      $telegramUserId = trim($telegramUserId);
      $username = trim($username);

      if (
         $chatId === ''
         || !preg_match('/^-?\d+$/', $chatId)
         || $telegramUserId === ''
         || !preg_match('/^\d+$/', $telegramUserId)
      ) {
         return ['ok' => false, 'error' => 'invalid_payload'];
      }

      if (!self::hasAllowedRole($user)) {
         return ['ok' => false, 'error' => 'access_denied'];
      }

      $currentTelegramId = trim((string)self::attribute(
         $user,
         'staff_telegram_id',
         ''
      ));

      if (
         $currentTelegramId !== ''
         && $currentTelegramId !== $telegramUserId
      ) {
         return ['ok' => false, 'error' => 'staff_account_already_bound'];
      }

      $telegramAlreadyBound = User::find()
         ->where(['staff_telegram_id' => $telegramUserId])
         ->andWhere(['<>', 'id', (int)$user->id])
         ->exists();

      if ($telegramAlreadyBound) {
         return ['ok' => false, 'error' => 'telegram_account_already_bound'];
      }

      $chatAlreadyBound = User::find()
         ->where(['staff_telegram_chat_id' => $chatId])
         ->andWhere(['<>', 'id', (int)$user->id])
         ->exists();

      if ($chatAlreadyBound) {
         return ['ok' => false, 'error' => 'telegram_account_already_bound'];
      }

      $user->setAttribute('staff_telegram_id', $telegramUserId);
      $user->setAttribute('staff_telegram_chat_id', $chatId);
      $user->setAttribute(
         'staff_telegram_username',
         $username !== '' ? mb_substr($username, 0, 255) : null
      );
      $user->setAttribute('staff_telegram_connected_at', time());

      if (!$user->save(false, [
         'staff_telegram_id',
         'staff_telegram_chat_id',
         'staff_telegram_username',
         'staff_telegram_connected_at',
      ])) {
         return ['ok' => false, 'error' => 'bind_save_failed'];
      }

      return [
         'ok' => true,
         'user' => $user,
      ];
   }

   public static function disconnect(User $user): bool
   {
      $user->setAttribute('staff_telegram_id', null);
      $user->setAttribute('staff_telegram_chat_id', null);
      $user->setAttribute('staff_telegram_username', null);
      $user->setAttribute('staff_telegram_connected_at', null);
      $user->setAttribute('staff_telegram_bind_token_hash', null);
      $user->setAttribute('staff_telegram_bind_expires_at', null);

      return $user->save(false, [
         'staff_telegram_id',
         'staff_telegram_chat_id',
         'staff_telegram_username',
         'staff_telegram_connected_at',
         'staff_telegram_bind_token_hash',
         'staff_telegram_bind_expires_at',
      ]);
   }

   /** @return User[] */
   public static function recipients(): array
   {
      $users = User::find()
         ->andWhere(['not', ['staff_telegram_chat_id' => null]])
         ->andWhere(['<>', 'staff_telegram_chat_id', ''])
         ->all();

      return array_values(array_filter(
         $users,
         static fn(User $user): bool => self::hasAllowedRole($user)
      ));
   }

   public static function hasAllowedRole(User $user): bool
   {
      $allowedRoles = self::allowedRoles();

      if (Yii::$app->has('authManager')) {
         $authManager = Yii::$app->get('authManager', false);
         if ($authManager !== null) {
            $roles = $authManager->getRolesByUser((string)$user->id);
            foreach (array_keys($roles) as $roleName) {
               if (in_array((string)$roleName, $allowedRoles, true)) {
                  return true;
               }
            }
         }
      }

      $directRole = self::attribute($user, 'role');
      if (is_string($directRole) && in_array($directRole, $allowedRoles, true)) {
         return true;
      }

      $allowedRoleValues = Yii::$app->params['telegramStaffAllowedRoleValues'] ?? [];
      if (
         is_array($allowedRoleValues)
         && $directRole !== null
         && in_array(
            (string)$directRole,
            array_map('strval', $allowedRoleValues),
            true
         )
      ) {
         return true;
      }

      return false;
   }

   public static function allowedRoles(): array
   {
      $roles = Yii::$app->params['telegramStaffAllowedRoles'] ?? [
         'admin',
         'techsupport',
      ];

      if (!is_array($roles)) {
         return ['admin', 'techsupport'];
      }

      $roles = array_values(array_unique(array_filter(array_map(
         static fn($role): string => trim((string)$role),
         $roles
      ))));

      return $roles !== [] ? $roles : ['admin', 'techsupport'];
   }

   public static function attribute(
      $model,
      string $attribute,
      mixed $default = null
   ): mixed {
      if (
         method_exists($model, 'hasAttribute')
         && $model->hasAttribute($attribute)
      ) {
         return $model->getAttribute($attribute);
      }

      return $default;
   }
}
