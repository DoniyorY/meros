<?php

namespace frontend\controllers;

use common\models\Billing;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;

class ApiController extends Controller
{
   public $enableCsrfValidation = false;

   public function actionPayme()
   {
      file_put_contents(
         Yii::getAlias('@runtime/logs/payme.log'),
         date('Y-m-d H:i:s') . PHP_EOL .
         Yii::$app->request->rawBody . PHP_EOL .
         str_repeat('-', 50) . PHP_EOL,
         FILE_APPEND
      );

      return json_encode([
         'success' => true,
      ]);
   }

   public static function sendZapierOrderPaidWebhook(Billing $billing): bool
   {
      $webhookUrl = (string) Yii::$app->params['zapierOrderPaidWebhookUrl'];

      if ($webhookUrl === '') {
         Yii::info([
            'message' => 'Zapier order paid webhook URL is not configured.',
            'billing_id' => (int) $billing->id,
         ], 'zapier');

         return false;
      }

      $payload = self::buildOrderPaidPayload($billing);
      $body = Json::encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

      $context = stream_context_create([
         'http' => [
            'method' => 'POST',
            'header' => implode("\r\n", [
               'Content-Type: application/json',
               'Accept: application/json',
            ]),
            'content' => $body,
            'ignore_errors' => true,
            'timeout' => 10,
         ],
      ]);

      $response = @file_get_contents($webhookUrl, false, $context);
      $statusCode = self::extractHttpStatusCode($http_response_header ?? []);
      $isSuccess = $statusCode >= 200 && $statusCode < 300;

      if (!$isSuccess) {
         Yii::error([
            'message' => 'Zapier order paid webhook request failed.',
            'billing_id' => (int) $billing->id,
            'status_code' => $statusCode,
            'response' => $response,
            'payload' => $payload,
         ], 'zapier');

         return false;
      }

      Yii::info([
         'message' => 'Zapier order paid webhook request sent.',
         'billing_id' => (int) $billing->id,
         'status_code' => $statusCode,
         'response' => $response,
      ], 'zapier');

      return true;
   }

   private static function buildOrderPaidPayload(Billing $billing): array
   {
      $user = $billing->user;
      $subscription = $billing->subscription;
      $course = $subscription ? $subscription->course : null;
      [$firstName, $lastName] = self::splitFullName($user ? (string) $user->fullname : '');
      $durationDays = $subscription->duration_days;

      return [
         //'event' => 'order_paid',
         'order_ref' => $subscription->sku_id,
         'first_name' => $firstName,
         'last_name' => $lastName,
         'email' => $user->email,
         'telegram_id' => $user ? (string) self::modelAttribute($user, 'telegram_id', '') : '',
         'course_sku' => (string) (Yii::$app->params['campus_id'] ?? ''),
         'duration_days' => (int)$durationDays,
      ];
   }

   private static function splitFullName(string $fullName): array
   {
      $parts = preg_split('/\s+/', trim($fullName), 2, PREG_SPLIT_NO_EMPTY);

      return [
         $parts[0] ?? '',
         $parts[1] ?? '',
      ];
   }

   private static function modelAttribute($model, string $attribute, $default = null)
   {
      if (method_exists($model, 'hasAttribute') && $model->hasAttribute($attribute)) {
         return $model->getAttribute($attribute);
      }

      return $default;
   }

   private static function extractHttpStatusCode(array $headers): int
   {
      foreach ($headers as $header) {
         if (preg_match('/^HTTP\/\S+\s+(\d{3})\b/', (string) $header, $matches)) {
            return (int) $matches[1];
         }
      }

      return 0;
   }
}
