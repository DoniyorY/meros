<?php

namespace frontend\components;

use Yii;
use yii\base\Component;
use yii\httpclient\Client;

final class GoogleAnalytics extends Component
{
   public string $measurementId;
   
   public string $apiSecret;
   
   public bool $debug = false;
   
   public function purchase(
      $billing,
      $plan,
      string $paymentProvider
   ): bool {
      if (!$billing->ga_client_id) {
         Yii::warning(
            "GA4 purchase skipped: billing {$billing->id} has no client_id",
            'analytics'
         );
         
         return false;
      }
      
      $params = [
         'transaction_id' => "MEROS-{$billing->id}",
         
         'currency' => 'UZS',
         
         'value' => (float)$billing->amount,
         
         'payment_type' => $paymentProvider,
         
         'engagement_time_msec' => 100,
         
         'items' => [
            [
               'item_id' => (string)$plan->id,
               
               'item_name' =>
                  $plan->name_en
                  ?? 'Meros subscription',
               
               'price' => (float)$billing->amount,
               
               'quantity' => 1,
            ],
         ],
      ];
      
      if ($billing->ga_session_id) {
         $params['session_id'] =
            (int)$billing->ga_session_id;
      }
      
      $payload = [
         'client_id' => $billing->ga_client_id,
         
         'events' => [
            [
               'name' => 'purchase',
               'params' => $params,
            ],
         ],
      ];
      
      /*
       * Если пользователь авторизован,
       * можно связать событие ещё и с User ID.
       *
       * Никаких email/телефонов сюда нельзя.
       */
      if ($billing->user_id) {
         $payload['user_id'] =
            (string)$billing->user_id;
      }
      
      return $this->send($payload);
   }
   
   private function send(array $payload): bool
   {
      $endpoint = $this->debug
         ? 'https://www.google-analytics.com/debug/mp/collect'
         : 'https://www.google-analytics.com/mp/collect';
      
      if ($this->debug) {
         $payload['validation_behavior'] =
            'ENFORCE_RECOMMENDATIONS';
      }
      
      $url =
         $endpoint
         . '?measurement_id='
         . rawurlencode($this->measurementId)
         . '&api_secret='
         . rawurlencode($this->apiSecret);
      
      $client = new Client();
      
      $response = $client
         ->createRequest()
         ->setMethod('POST')
         ->setUrl($url)
         ->setFormat(Client::FORMAT_JSON)
         ->setData($payload)
         ->send();
      
      if ($this->debug) {
         Yii::info(
            [
               'request' => $payload,
               'response' => $response->data,
            ],
            'analytics'
         );
         
         return empty(
         $response->data['validationMessages']
         );
      }
      
      if (!$response->isOk) {
         Yii::error(
            [
               'status' => $response->statusCode,
               'body' => $response->content,
            ],
            'analytics'
         );
         
         return false;
      }
      
      return true;
   }
}