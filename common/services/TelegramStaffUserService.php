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

      return User::findOne(['telegram_staff_chat_id' => $chatId]);
   }

   /**
    * Ищет сотрудника по Telegram User ID, который уже был сохранён
    * при подключении основного клиентского бота.
    */
   public static function findEmployeeByTelegramUserId(
      string $telegramUserId
   ): ?User {
      $telegramUserId = trim($telegramUserId);
      if ($telegramUserId === '' || !preg_match('/^\d+$/', $telegramUserId)) {
         return null;
      }

      $schema = User::getTableSchema();
      if ($schema->getColumn('telegram_user_id') === null) {
         return null;
      }

      $user = User::findOne(['telegram_user_id' => $telegramUserId]);

      return $user !== null && self::hasAllowedRole($user)
         ? $user
         : null;
   }

   /**
    * Строгая привязка: никакой почты и кодов.
    * Telegram ID должен уже принадлежать пользователю с разрешённой ролью.
    */
   public static function attachByEmployeeTelegramId(
      string $chatId,
      string $telegramUserId,
      string $username
   ): array {
      $user = self::findEmployeeByTelegramUserId($telegramUserId);
      if ($user === null) {
         return ['ok' => false, 'error' => 'employee_not_found'];
      }

      return self::attachStaffTelegram(
         $user,
         $chatId,
         $telegramUserId,
         $username
      );
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

      $currentChatId = trim((string)self::attribute(
         $user,
         'telegram_staff_chat_id',
         ''
      ));

      if ($currentChatId !== '' && $currentChatId !== $chatId) {
         return ['ok' => false, 'error' => 'staff_account_already_bound'];
      }

      $chatAlreadyBound = User::find()
         ->where(['telegram_staff_chat_id' => $chatId])
         ->andWhere(['<>', 'id', (int)$user->id])
         ->exists();

      if ($chatAlreadyBound) {
         return ['ok' => false, 'error' => 'telegram_account_already_bound'];
      }

      $telegramUserAlreadyBound = User::find()
         ->where(['telegram_staff_user_id' => $telegramUserId])
         ->andWhere(['<>', 'id', (int)$user->id])
         ->exists();

      if ($telegramUserAlreadyBound) {
         return ['ok' => false, 'error' => 'telegram_account_already_bound'];
      }

      $user->setAttribute('telegram_staff_chat_id', $chatId);
      $user->setAttribute('telegram_staff_user_id', $telegramUserId);
      $user->setAttribute(
         'telegram_staff_username',
         $username !== '' ? mb_substr($username, 0, 255) : null
      );
      $user->setAttribute('telegram_staff_connected_at', time());

      if (!$user->save(false, [
         'telegram_staff_chat_id',
         'telegram_staff_user_id',
         'telegram_staff_username',
         'telegram_staff_connected_at',
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
      $user->setAttribute('telegram_staff_chat_id', null);
      $user->setAttribute('telegram_staff_user_id', null);
      $user->setAttribute('telegram_staff_username', null);
      $user->setAttribute('telegram_staff_connected_at', null);

      return $user->save(false, [
         'telegram_staff_chat_id',
         'telegram_staff_user_id',
         'telegram_staff_username',
         'telegram_staff_connected_at',
      ]);
   }

   /** @return User[] */
   public static function recipients(): array
   {
      $users = User::find()
         ->andWhere(['not', ['telegram_staff_chat_id' => null]])
         ->andWhere(['<>', 'telegram_staff_chat_id', ''])
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

      // Оставлено для проектов, где роль хранится прямо в user.role.
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
