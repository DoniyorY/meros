<?php

declare(strict_types=1);

namespace frontend\components;

use common\models\Billing;
use InvalidArgumentException;
use Yii;

/**
 * Формирует безопасный URL перехода на стандартную форму Payme.
 *
 * Сумма и billing_id берутся только из БД.
 */
final class PaymeCheckout
{
   public static function url(
      Billing $billing,
      string $lang = 'ru',
      ?string $callbackUrl = null,
      int $callbackTimeoutMs = 15000
   ): string {
      $config = Yii::$app->params['payme'] ?? [];
      
      $merchantId = (string) ($config['merchantId'] ?? '');
      $checkoutUrl = rtrim(
         (string) ($config['checkoutUrl'] ?? ''),
         '/'
      );
      
      if ($merchantId === '' || $checkoutUrl === '') {
         throw new InvalidArgumentException(
            'Payme merchantId or checkoutUrl is not configured.'
         );
      }
      
      $lang = strtolower(substr($lang, 0, 2));
      
      if (!in_array($lang, ['ru', 'uz', 'en'], true)) {
         $lang = 'ru';
      }
      
      $amount = (int) $billing->amount;
      
      if (!(bool) ($config['amountAlreadyInTiyin'] ?? false)) {
         $amount *= 100;
      }
      
      if ($amount <= 0) {
         throw new InvalidArgumentException(
            'Payment amount must be greater than zero.'
         );
      }
      
      $params = [
         'm=' . $merchantId,
         'ac.order_id=' . (int) $billing->id,
         'a=' . $amount,
         'l=' . $lang,
      ];
      
      if ($callbackUrl !== null && $callbackUrl !== '') {
         $params[] = 'c=' . $callbackUrl;
         $params[] = 'ct=' . max(0, $callbackTimeoutMs);
      }
      
      return $checkoutUrl . '/' . base64_encode(
            implode(';', $params)
         );
   }
}
