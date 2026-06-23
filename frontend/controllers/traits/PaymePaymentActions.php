<?php

declare(strict_types=1);

namespace frontend\controllers\traits;

use common\models\Billing;
use frontend\components\PaymeCheckout;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Добавьте в существующий PaymentController:
 *
 * use \frontend\controllers\traits\PaymePaymentActions;
 */
trait PaymePaymentActions
{
   /**
    * Принимает только ID Billing, повторно загружает сумму из БД
    * и перенаправляет пользователя на Payme.
    */
   public function actionPayme(int $id): Response
   {
      if (Yii::$app->user->isGuest) {
         throw new ForbiddenHttpException(
            'Для оплаты необходимо авторизоваться.'
         );
      }
      
      /** @var Billing|null $billing */
      $billing = Billing::find()
         ->where([
            'id' => $id,
            'user_id' => Yii::$app->user->id,
         ])
         ->one();
      
      if ($billing === null) {
         throw new NotFoundHttpException(
            'Счёт не найден.'
         );
      }
      
      if (
         (int) $billing->payment_status
         === Billing::STATUS_SUCCESS
      ) {
         return $this->redirect([
            'payme-result',
            'token' => $billing->billing_token,
         ]);
      }
      
      $allowedStatuses = [
         null,
         Billing::STATUS_PENDING,
         Billing::STATUS_FAILED,
         Billing::STATUS_CANCELLED,
      ];
      
      if (!in_array(
         $billing->payment_status,
         $allowedStatuses,
         true
      )) {
         throw new ForbiddenHttpException(
            'Этот счёт сейчас нельзя оплатить.'
         );
      }
      
      if ((int) $billing->amount <= 0) {
         throw new ForbiddenHttpException(
            'Некорректная сумма оплаты.'
         );
      }
      
      if (empty($billing->billing_token)) {
         $billing->billing_token = Yii::$app->security
            ->generateRandomString(32);
      }
      
      $billing->payment_provider = (int) (
         Yii::$app->params['payme']['providerCode'] ?? 2
      );
      
      if (!$billing->save(false)) {
         throw new \RuntimeException(
            'Не удалось подготовить счёт к оплате.'
         );
      }
      
      // Путь callback без query string, чтобы URL корректно попал
      // внутрь base64-параметров Payme.
      $callbackUrl = Yii::$app->urlManager->createAbsoluteUrl([
         'payment/payme-result',
         'token' => $billing->billing_token,
      ]);
      
      return $this->redirect(
         PaymeCheckout::url(
            $billing,
            Yii::$app->language,
            $callbackUrl
         )
      );
   }
   
   /**
    * Только показывает итог. Статус оплаты здесь не меняется.
    */
   public function actionPaymeResult(string $token): string
   {
      /** @var Billing|null $billing */
      $billing = Billing::find()
         ->where(['billing_token' => $token])
         ->one();
      
      if ($billing === null) {
         throw new NotFoundHttpException(
            'Счёт не найден.'
         );
      }
      
      if (
         !Yii::$app->user->isGuest
         && (int) $billing->user_id
         !== (int) Yii::$app->user->id
      ) {
         throw new NotFoundHttpException(
            'Счёт не найден.'
         );
      }
      
      return $this->render(
         'payme-result',
         ['billing' => $billing]
      );
   }
}
