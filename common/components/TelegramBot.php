<?php

declare(strict_types=1);

namespace common\components;

use JsonException;
use RuntimeException;
use yii\base\Component;

final class TelegramBot extends Component
{
   public string $token = '';
   public int $timeout = 10;

   public function init(): void
   {
      parent::init();

      if ($this->token === '') {
         throw new RuntimeException('Telegram bot token is not configured.');
      }
   }

   public function sendMessage(
      int|string $chatId,
      string $text,
      ?array $replyMarkup = null
   ): array {
      $payload = [
         'chat_id' => (string)$chatId,
         'text' => $text,
         'parse_mode' => 'HTML',
         'link_preview_options' => [
            'is_disabled' => true,
         ],
      ];

      if ($replyMarkup !== null) {
         $payload['reply_markup'] = $replyMarkup;
      }

      return $this->request('sendMessage', $payload);
   }

   public function setWebhook(string $url, string $secretToken): array
   {
      return $this->request('setWebhook', [
         'url' => $url,
         'secret_token' => $secretToken,
         'allowed_updates' => ['message'],
         'drop_pending_updates' => true,
      ]);
   }

   public function deleteWebhook(bool $dropPendingUpdates = false): array
   {
      return $this->request('deleteWebhook', [
         'drop_pending_updates' => $dropPendingUpdates,
      ]);
   }

   public function setMyCommands(array $commands): array
   {
      return $this->request('setMyCommands', [
         'commands' => $commands,
      ]);
   }

   public function getWebhookInfo(): array
   {
      return $this->request('getWebhookInfo');
   }

   public function getMe(): array
   {
      return $this->request('getMe');
   }

   private function request(string $method, array $payload = []): array
   {
      $url = sprintf(
         'https://api.telegram.org/bot%s/%s',
         $this->token,
         $method
      );

      try {
         $body = json_encode(
            $payload,
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
         );
      } catch (JsonException $exception) {
         throw new RuntimeException(
            'Unable to encode Telegram request: ' . $exception->getMessage(),
            0,
            $exception
         );
      }

      if (function_exists('curl_init')) {
         [$statusCode, $responseBody] = $this->requestWithCurl($url, $body);
      } elseif ((bool)ini_get('allow_url_fopen')) {
         [$statusCode, $responseBody] = $this->requestWithStreams($url, $body);
      } else {
         throw new RuntimeException(
            'Neither cURL nor allow_url_fopen is available for Telegram requests.'
         );
      }

      try {
         $data = json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);
      } catch (JsonException $exception) {
         throw new RuntimeException(sprintf(
            'Telegram API %s returned invalid JSON. HTTP %d: %s',
            $method,
            $statusCode,
            mb_substr($responseBody, 0, 1000)
         ));
      }

      if (
         $statusCode < 200
         || $statusCode >= 300
         || !is_array($data)
         || ($data['ok'] ?? false) !== true
      ) {
         throw new RuntimeException(sprintf(
            'Telegram API %s failed. HTTP %d: %s',
            $method,
            $statusCode,
            (string)($data['description'] ?? mb_substr($responseBody, 0, 1000))
         ));
      }

      return $data;
   }

   private function requestWithCurl(string $url, string $body): array
   {
      $curl = curl_init($url);
      if ($curl === false) {
         throw new RuntimeException('Unable to initialize cURL.');
      }

      curl_setopt_array($curl, [
         CURLOPT_POST => true,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_CONNECTTIMEOUT => $this->timeout,
         CURLOPT_TIMEOUT => $this->timeout,
         CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
         ],
         CURLOPT_POSTFIELDS => $body,
      ]);

      $responseBody = curl_exec($curl);
      $statusCode = (int)curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
      $error = curl_error($curl);
      curl_close($curl);

      if ($responseBody === false) {
         throw new RuntimeException('Telegram cURL request failed: ' . $error);
      }

      return [$statusCode, (string)$responseBody];
   }

   private function requestWithStreams(string $url, string $body): array
   {
      $context = stream_context_create([
         'http' => [
            'method' => 'POST',
            'header' => implode("\r\n", [
               'Content-Type: application/json',
               'Accept: application/json',
            ]),
            'content' => $body,
            'ignore_errors' => true,
            'timeout' => $this->timeout,
         ],
      ]);

      $responseBody = @file_get_contents($url, false, $context);
      $headers = $http_response_header ?? [];

      if ($responseBody === false) {
         throw new RuntimeException('Telegram stream request failed.');
      }

      return [$this->extractStatusCode($headers), (string)$responseBody];
   }

   private function extractStatusCode(array $headers): int
   {
      foreach ($headers as $header) {
         if (preg_match('/^HTTP\/\S+\s+(\d{3})\b/', (string)$header, $matches)) {
            return (int)$matches[1];
         }
      }

      return 0;
   }
}
