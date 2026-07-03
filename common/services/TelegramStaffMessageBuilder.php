<?php

declare(strict_types=1);

namespace common\services;

use common\models\Billing;
use common\models\User;
use Throwable;
use Yii;
use yii\helpers\Html;

final class TelegramStaffMessageBuilder
{
   public static function build(Billing $billing): string
   {
      $user = self::relation($billing, 'user');
      // В проекте эта relation должна возвращать UserSubscriptions.
      $userSubscription = self::relation($billing, 'subscription');
      $course = $userSubscription !== null
         ? self::relation($userSubscription, 'course')
         : null;

      $lines = [
         '<b>🆕 Новая оплаченная подписка</b>',
         '',
         '<b>Заказ:</b> #' . (int)$billing->id,
      ];

      if ($user instanceof User) {
         self::append($lines, 'Клиент', self::attribute($user, 'fullname'));
         self::append($lines, 'Email', self::attribute($user, 'email'));
         self::append($lines, 'Телефон', self::firstValue($user, [
            'phone',
            'mobile_phone',
            'work_phone',
         ]));
      }

      self::append($lines, 'Курс', self::localizedName($course));
      self::append($lines, 'Подписка', self::localizedName($userSubscription));
      self::append($lines, 'SKU', self::attribute($userSubscription, 'sku_id'));

      $duration = self::attribute($userSubscription, 'duration_days');
      if ($duration !== null && $duration !== '') {
         self::append($lines, 'Срок', (string)$duration . ' дней');
      }

      $amount = self::firstValue($userSubscription, ['amount']);
      if ($amount === null || $amount === '') {
         $amount = self::firstValue($billing, [
            'amount',
            'total_price',
            'price',
         ]);
      }

      if ($amount !== null && $amount !== '') {
         $numericAmount = is_numeric($amount) ? (float)$amount : null;
         $formattedAmount = $numericAmount !== null
            ? number_format($numericAmount, 0, '.', ' ') . ' UZS'
            : (string)$amount;
         self::append($lines, 'Сумма', $formattedAmount);
      }

      self::append(
         $lines,
         'Метод оплаты',
         self::paymentMethod($userSubscription, $billing)
      );

      self::append($lines, 'Transaction ID', self::firstValue(
         $userSubscription,
         ['transaction_id', 'payment_transaction_id']
      ) ?? self::firstValue($billing, [
         'transaction_id',
         'payment_transaction_id',
      ]));

      $createdAt = self::firstValue($userSubscription, [
         'paid_at',
         'updated_at',
         'created_at',
         'created',
      ]);
      if ($createdAt === null || $createdAt === '') {
         $createdAt = self::firstValue($billing, [
            'paid_at',
            'updated_at',
            'created_at',
            'created',
         ]);
      }

      if (is_numeric($createdAt) && (int)$createdAt > 0) {
         self::append($lines, 'Дата', date('d.m.Y H:i', (int)$createdAt));
      } else {
         self::append($lines, 'Дата', date('d.m.Y H:i'));
      }

      return implode("\n", $lines);
   }

   private static function paymentMethod(
      $userSubscription,
      Billing $billing
   ): string {
      $configuredAttribute = trim((string)(
         Yii::$app->params['telegramStaffPaymentMethodAttribute']
         ?? 'payment_method'
      ));

      $attributes = array_values(array_unique(array_filter([
         $configuredAttribute,
         'payment_method',
         'payment_system',
         'payment_type',
         'provider',
         'gateway',
         'method',
      ])));

      // Главный источник — UserSubscriptions, как и требуется.
      $value = self::firstValue($userSubscription, $attributes);

      // Fallback оставлен только для совместимости со старыми оплатами.
      if ($value === null || trim((string)$value) === '') {
         $value = self::firstValue($billing, $attributes);
      }

      if ($value === null || trim((string)$value) === '') {
         return '';
      }

      $raw = trim((string)$value);
      $normalized = mb_strtolower($raw);

      $configuredMap = Yii::$app->params['telegramStaffPaymentMethodMap'] ?? [];
      if (is_array($configuredMap)) {
         foreach ($configuredMap as $key => $label) {
            if ((string)$key === $raw || mb_strtolower((string)$key) === $normalized) {
               return (string)$label;
            }
         }
      }

      return match ($normalized) {
         'paycom', 'payme' => 'payme',
         'click' => 'click',
         default => $raw,
      };
   }

   private static function append(
      array &$lines,
      string $label,
      mixed $value
   ): void {
      if ($value === null) {
         return;
      }

      $value = trim((string)$value);
      if ($value === '') {
         return;
      }

      $lines[] = '<b>' . Html::encode($label) . ':</b> '
         . Html::encode($value);
   }

   private static function localizedName($model): string
   {
      if ($model === null) {
         return '';
      }

      return (string)self::firstValue($model, [
         'name_ru',
         'title_ru',
         'name_en',
         'title_en',
         'name',
         'title',
      ]);
   }

   private static function firstValue($model, array $attributes): mixed
   {
      foreach ($attributes as $attribute) {
         $value = self::attribute($model, (string)$attribute);
         if ($value !== null && trim((string)$value) !== '') {
            return $value;
         }
      }

      return null;
   }

   private static function attribute(
      $model,
      string $attribute,
      mixed $default = null
   ): mixed {
      if (
         is_object($model)
         && method_exists($model, 'hasAttribute')
         && $model->hasAttribute($attribute)
      ) {
         return $model->getAttribute($attribute);
      }

      return $default;
   }

   private static function relation($model, string $name): mixed
   {
      if (!is_object($model)) {
         return null;
      }

      try {
         return $model->{$name};
      } catch (Throwable) {
         return null;
      }
   }
}
