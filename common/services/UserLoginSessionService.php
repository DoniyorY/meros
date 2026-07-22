<?php

namespace common\services;

use common\models\UserLoginSession;
use RuntimeException;
use Yii;

final class UserLoginSessionService
{
   private const SESSION_TOKEN_KEY = '__user_login_session_token';
   
   public static function start(int $userId): UserLoginSession
   {
      $token = Yii::$app->security->generateRandomString(64);
      $now = time();
      
      $model = new UserLoginSession([
         'user_id' => $userId,
         'token_hash' => self::hashToken($token),
         
         'ip_address' => Yii::$app->request->getUserIP(),
         'user_agent' => Yii::$app->request->getUserAgent(),
         
         'logged_in_at' => $now,
         'last_seen_at' => $now,
         
         'expires_at' => $now + Yii::$app->session->getTimeout(),
      ]);
      
      if (!$model->save()) {
         throw new RuntimeException(
            'Не удалось создать пользовательский сеанс: '
            . json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE)
         );
      }
      
      Yii::$app->session->set(self::SESSION_TOKEN_KEY, $token);
      
      return $model;
   }
   
   public static function finishCurrent(): void
   {
      $tokenHash = self::getCurrentTokenHash();
      
      if ($tokenHash === null) {
         return;
      }
      
      UserLoginSession::updateAll(
         [
            'logged_out_at' => time(),
         ],
         [
            'token_hash' => $tokenHash,
            'logged_out_at' => null,
         ]
      );
      
      Yii::$app->session->remove(self::SESSION_TOKEN_KEY);
   }
   
   public static function validateCurrent(): bool
   {
      if (Yii::$app->user->isGuest) {
         return true;
      }
      
      $tokenHash = self::getCurrentTokenHash();
      
      /*
       * Нужно для пользователей, которые уже были авторизованы
       * до добавления новой таблицы.
       */
      if ($tokenHash === null) {
         self::start((int) Yii::$app->user->id);
         
         return true;
      }
      
      $session = UserLoginSession::find()
         ->where([
            'user_id' => Yii::$app->user->id,
            'token_hash' => $tokenHash,
         ])
         ->one();
      
      if (
         $session === null
         || $session->revoked_at !== null
         || $session->logged_out_at !== null
         || $session->expires_at <= time()
      ) {
         return false;
      }
      
      /*
       * Не долбим UPDATE на каждый запрос.
       * Обновляем активность максимум раз в 5 минут.
       */
      if ($session->last_seen_at < time() - 300) {
         UserLoginSession::updateAll(
            [
               'last_seen_at' => time(),
               'expires_at' => time() + Yii::$app->session->getTimeout(),
            ],
            [
               'id' => $session->id,
            ]
         );
      }
      
      return true;
   }
   
   public static function getCurrentTokenHash(): ?string
   {
      $token = Yii::$app->session->get(self::SESSION_TOKEN_KEY);
      
      if (!is_string($token) || $token === '') {
         return null;
      }
      
      return self::hashToken($token);
   }
   
   private static function hashToken(string $token): string
   {
      return hash('sha256', $token);
   }
}