<?php

declare(strict_types=1);

namespace console\controllers;

use common\models\TelegramRegistration;
use RuntimeException;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

final class TelegramController extends Controller
{
   public function actionSetWebhook(?string $url = null): int
   {
      $url ??= trim((string)(Yii::$app->params['telegramWebhookUrl'] ?? ''));
      $secret = trim((string)(Yii::$app->params['telegramWebhookSecret'] ?? ''));
      
      if ($url === '' || $secret === '') {
         throw new RuntimeException(
            'telegramWebhookUrl and telegramWebhookSecret must be configured.'
         );
      }
      
      $result = Yii::$app->telegramBot->setWebhook($url, $secret);
      $this->stdout(json_encode(
            $result,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
         ) . PHP_EOL);
      
      Yii::$app->telegramBot->setMyCommands([
         ['command' => 'start', 'description' => 'Start or connect account'],
         ['command' => 'menu', 'description' => 'Open menu'],
         ['command' => 'subscriptions', 'description' => 'My subscriptions'],
         ['command' => 'info', 'description' => 'My information'],
         ['command' => 'resend', 'description' => 'Resend verification code'],
         ['command' => 'cancel', 'description' => 'Cancel account connection'],
      ]);
      
      return ExitCode::OK;
   }
   
   public function actionDeleteWebhook(bool $dropPendingUpdates = false): int
   {
      $result = Yii::$app->telegramBot->deleteWebhook($dropPendingUpdates);
      $this->stdout(json_encode(
            $result,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
         ) . PHP_EOL);
      
      return ExitCode::OK;
   }
   
   public function actionInfo(): int
   {
      $result = [
         'bot' => Yii::$app->telegramBot->getMe(),
         'webhook' => Yii::$app->telegramBot->getWebhookInfo(),
      ];
      
      $this->stdout(json_encode(
            $result,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
         ) . PHP_EOL);
      
      return ExitCode::OK;
   }
   
   public function actionCleanupRegistrations(): int
   {
      $deleted = TelegramRegistration::deleteAll([
         'and',
         ['status' => TelegramRegistration::STATUS_WAIT_CODE],
         ['<', 'code_expires_at', time() - 86400],
      ]);
      
      $this->stdout("Deleted expired Telegram registrations: {$deleted}" . PHP_EOL);
      
      return ExitCode::OK;
   }
}
