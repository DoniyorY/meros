<?php

namespace frontend\controllers;

use common\models\Billing;
use common\models\User;
use common\services\TelegramNotificationService;
use common\services\TelegramStaffNotificationService;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;

class ApiController extends Controller
{
    public $enableCsrfValidation = false;


    public static function sendZapierOrderPaidWebhook(Billing $billing): bool
    {
        $webhookUrl = (string)Yii::$app->params['zapierOrderPaidWebhookUrl'];

        if ($webhookUrl === '') {
            Yii::info([
                'message' => 'Zapier order paid webhook URL is not configured.',
                'billing_id' => (int)$billing->id,
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
                'billing_id' => (int)$billing->id,
                'status_code' => $statusCode,
                'response' => $response,
                'payload' => $payload,
            ], 'zapier');

            return false;
        }

        Yii::info([
            'message' => 'Zapier order paid webhook request sent.',
            'billing_id' => (int)$billing->id,
            'status_code' => $statusCode,
            'payload' => $payload,
            'response' => $response,
        ], 'zapier');
        $user = User::findOne($billing->user_id);
        $user->sendCongratsEmail($user, $billing);
        /*if (is_null($user->telegram_chat_id)) {
            $text = self::buildSmsText('purchase_without_telegram', $user);
            Yii::$app->playmobile->sendSms("$user->phone", $text);
        }*/
        TelegramNotificationService::sendPurchaseNotification($billing);
        TelegramStaffNotificationService::sendNewSubscriptionNotification($billing);
        return true;
    }

    private static function buildOrderPaidPayload(Billing $billing): array
    {
        $user = $billing->user;
        $subscription = $billing->subscription;
        $course = $subscription ? $subscription->course : null;
        [$firstName, $lastName] = self::splitFullName($user ? (string)$user->fullname : '');
        $durationDays = $subscription->duration_days;

        return [
            'email' => $user->email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'sku' => $subscription->sku_id,
            'duration_days' => $durationDays,
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
            if (preg_match('/^HTTP\/\S+\s+(\d{3})\b/', (string)$header, $matches)) {
                return (int)$matches[1];
            }
        }

        return 0;
    }

    private static function buildSmsText(string $type, User $user): string
    {
        $lang = substr((string)Yii::$app->language, 0, 2);

        $messages = Yii::$app->params['smsMessages'][$type] ?? [];

        $template = $messages[$lang]
            ?? $messages['en']
            ?? '';

        return strtr($template, [
            '{name}' => (string)$user->fullname,
            '{bot_link}' => (string)Yii::$app->params['telegramBotLink'],
            '{platform_link}' => (string)Yii::$app->params['coursePlatformUrl'],
        ]);
    }
}
