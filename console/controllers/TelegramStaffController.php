<?php

declare(strict_types=1);

namespace console\controllers;

use RuntimeException;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

final class TelegramStaffController extends Controller
{
   public function actionSetWebhook(?string $url = null): int
   {
      $url ??= trim((string)(
         Yii::$app->params['telegramStaffWebhookUrl'] ?? ''
      ));
      $secret = trim((string)(
         Yii::$app->params['telegramStaffWebhookSecret'] ?? ''
      ));

      if ($url === '' || $secret === '') {
         throw new RuntimeException(
            'telegramStaffWebhookUrl and telegramStaffWebhookSecret must be configured.'
         );
      }

      $result = Yii::$app->telegramStaffBot->setWebhook($url, $secret);
      $this->stdout(json_encode(
         $result,
         JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
      ) . PHP_EOL);

      Yii::$app->telegramStaffBot->setMyCommands([
         ['command' => 'start', 'description' => 'Enable staff notifications'],
      ]);

      return ExitCode::OK;
   }

   public function actionDeleteWebhook(bool $dropPendingUpdates = false): int
   {
      $result = Yii::$app->telegramStaffBot->deleteWebhook($dropPendingUpdates);
      $this->stdout(json_encode(
         $result,
         JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
      ) . PHP_EOL);

      return ExitCode::OK;
   }

   public function actionInfo(): int
   {
      $result = [
         'bot' => Yii::$app->telegramStaffBot->getMe(),
         'webhook' => Yii::$app->telegramStaffBot->getWebhookInfo(),
      ];

      $this->stdout(json_encode(
         $result,
         JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
      ) . PHP_EOL);

      return ExitCode::OK;
   }
}
