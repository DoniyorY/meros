<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\Billing;
use common\models\ClickPayment;
use common\models\ClickWebhookLog;
use common\models\UserSubscriptions;
use frontend\controllers\traits\PaymePaymentActions;
use RuntimeException;
use Throwable;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

final class PaymentController extends Controller
{
   use PaymePaymentActions;
   
   public function behaviors(): array
   {
      $behaviors = parent::behaviors();
      $behaviors['verbs'] = [
         'class' => VerbFilter::class,
         'actions' => [
            'click-webhook' => ['POST'],
            'click-pay' => ['POST'],
         ],
      ];
      
      return $behaviors;
   }
   
   public function beforeAction($action): bool
   {
      if ($action->id === 'click-webhook') {
         $this->enableCsrfValidation = false;
      }
      
      return parent::beforeAction($action);
   }
   
   /**
    * Один URL можно указать в CLICK одновременно как Prepare и Complete:
    * action=0 -> Prepare, action=1 -> Complete.
    */
   public function actionClickWebhook(): array
   {
      Yii::$app->response->format = Response::FORMAT_JSON;
      Yii::$app->response->statusCode = 200;
      
      $data = Yii::$app->request->post();
      
      $log = new ClickWebhookLog();
      $log->click_trans_id = isset($data['click_trans_id'])
         ? (string) $data['click_trans_id']
         : null;
      $log->merchant_trans_id = isset($data['merchant_trans_id'])
         ? (string) $data['merchant_trans_id']
         : null;
      $log->action = isset($data['action'])
         ? (int) $data['action']
         : null;
      $log->request_payload = Json::encode(
         $data,
         JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
      );
      $log->remote_ip = Yii::$app->request->userIP;
      $log->created_at = time();
      
      if (!$log->save(false)) {
         Yii::error([
            'message' => 'CLICK webhook log was not saved.',
            'payload' => $data,
         ], 'click');
      }
      
      try {
         $response = $this->processClickRequest($data, $log);
      } catch (Throwable $e) {
         Yii::error([
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'payload' => $data,
         ], 'click');
         
         $response = $this->clickResponse(
            $data,
            -7,
            'Failed to update order'
         );
      }
      
      if (!$log->isNewRecord) {
         $log->response_error = isset($response['error'])
            ? (int) $response['error']
            : -7;
         $log->response_payload = Json::encode(
            $response,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
         );
         $log->processed_at = time();
         $log->save(false);
      }
      
      return $response;
   }
   
   /**
    * Перенаправляет пользователя на CLICK.
    * В transaction_param передаётся ID Billing, а не UserSubscriptions.
    */
   public function actionClickPay(int $id): Response
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
         throw new NotFoundHttpException('Счёт не найден.');
      }
      
      if (
         (int) $billing->payment_status
         === Billing::STATUS_SUCCESS
      ) {
         return $this->redirect([
            'click-return',
            'id' => $billing->id,
         ]);
      }
      
      $paymentStatus = $billing->payment_status === null
         ? null
         : (int) $billing->payment_status;
      
      if (!in_array($paymentStatus, [
         null,
         Billing::STATUS_PENDING,
         Billing::STATUS_FAILED,
         Billing::STATUS_CANCELLED,
      ], true)) {
         throw new ForbiddenHttpException(
            'Этот счёт сейчас нельзя оплатить.'
         );
      }
      
      if ((int) $billing->amount <= 0) {
         throw new ForbiddenHttpException(
            'Некорректная сумма оплаты.'
         );
      }
      
      $config = $this->clickConfig();
      $returnUrl = Url::to([
         '/payment/click-return',
         'id' => $billing->id,
      ], 'https');
      
      $query = http_build_query([
         'service_id' => $config['service_id'],
         'merchant_id' => $config['merchant_id'],
         'amount' => number_format(
            (float) $billing->amount,
            2,
            '.',
            ''
         ),
         'transaction_param' => (string) $billing->id,
         'return_url' => $returnUrl,
      ], '', '&', PHP_QUERY_RFC3986);
      
      return $this->redirect(
         'https://my.click.uz/services/pay?' . $query
      );
   }
   
   /**
    * Возврат браузера пользователя.
    * Здесь статус не меняется — показываем состояние Billing из БД.
    */
   public function actionClickReturn(int $id): string
   {
      /** @var Billing|null $billing */
      $billing = Billing::find()
         ->where([
            'id' => $id,
            'user_id' => Yii::$app->user->id,
         ])
         ->one();
      
      if ($billing === null) {
         throw new NotFoundHttpException('Счёт не найден.');
      }
      
      return $this->render('click-return', [
         'billing' => $billing,
      ]);
   }
   
   private function processClickRequest(
      array $data,
      ClickWebhookLog $log
   ): array {
      $validationError = $this->validateClickRequest($data);
      
      if ($validationError !== null) {
         return $validationError;
      }
      
      return (int) $data['action'] === 0
         ? $this->prepareClickPayment($data, $log)
         : $this->completeClickPayment($data, $log);
   }
   
   private function validateClickRequest(array $data): ?array
   {
      $required = [
         'click_trans_id',
         'service_id',
         'click_paydoc_id',
         'merchant_trans_id',
         'amount',
         'action',
         'error',
         'error_note',
         'sign_time',
         'sign_string',
      ];
      
      foreach ($required as $key) {
         if (
            !array_key_exists($key, $data)
            || $data[$key] === ''
         ) {
            return $this->clickResponse(
               $data,
               -8,
               'Error in request from click'
            );
         }
      }
      
      $action = (int) $data['action'];
      
      if (!in_array($action, [0, 1], true)) {
         return $this->clickResponse(
            $data,
            -3,
            'Action not found'
         );
      }
      
      if (
         $action === 1
         && (
            !array_key_exists('merchant_prepare_id', $data)
            || $data['merchant_prepare_id'] === ''
         )
      ) {
         return $this->clickResponse(
            $data,
            -8,
            'Error in request from click'
         );
      }
      
      $config = $this->clickConfig();
      
      if (
         (string) $data['service_id']
         !== (string) $config['service_id']
      ) {
         return $this->clickResponse(
            $data,
            -8,
            'Incorrect service_id'
         );
      }
      
      $expectedSign = md5(
         (string) $data['click_trans_id']
         . (string) $data['service_id']
         . (string) $config['secret_key']
         . (string) $data['merchant_trans_id']
         . ($action === 1
            ? (string) $data['merchant_prepare_id']
            : '')
         . (string) $data['amount']
         . (string) $data['action']
         . (string) $data['sign_time']
      );
      
      $providedSign = strtolower(
         trim((string) $data['sign_string'])
      );
      
      if (!hash_equals($expectedSign, $providedSign)) {
         return $this->clickResponse(
            $data,
            -1,
            'SIGN CHECK FAILED!'
         );
      }
      
      return null;
   }
   
   /**
    * Prepare: находит Billing, фиксирует платёжную попытку
    * и возвращает ID ClickPayment как merchant_prepare_id.
    */
   private function prepareClickPayment(
      array $data,
      ClickWebhookLog $log
   ): array {
      $billing = $this->findBilling(
         (string) $data['merchant_trans_id']
      );
      
      if ($billing === null) {
         return $this->clickResponse(
            $data,
            -5,
            'User does not exist'
         );
      }
      
      if (!$this->amountsEqual($billing->amount, $data['amount'])) {
         return $this->clickResponse(
            $data,
            -2,
            'Incorrect parameter amount'
         );
      }
      
      if (
         (int) $billing->payment_status
         === Billing::STATUS_SUCCESS
      ) {
         return $this->clickResponse(
            $data,
            -4,
            'Already paid'
         );
      }
      
      $dbTransaction = Yii::$app->db->beginTransaction();
      
      try {
         $this->lockBilling((int) $billing->id);
         $billing->refresh();
         
         if (
            (int) $billing->payment_status
            === Billing::STATUS_SUCCESS
         ) {
            $dbTransaction->rollBack();
            
            return $this->clickResponse(
               $data,
               -4,
               'Already paid'
            );
         }
         
         $clickTransId = (string) $data['click_trans_id'];
         $storedTransactionId = trim(
            (string) $billing->payment_transaction_id
         );
         
         if (
            (int) $billing->payment_status
            === Billing::STATUS_PENDING
            && $storedTransactionId !== ''
            && !hash_equals(
               $storedTransactionId,
               $clickTransId
            )
         ) {
            $dbTransaction->rollBack();
            
            return $this->clickResponse(
               $data,
               -9,
               'Transaction cancelled'
            );
         }
         
         /** @var ClickPayment|null $payment */
         $payment = ClickPayment::find()
            ->where(['click_trans_id' => $clickTransId])
            ->one();
         
         if ($payment !== null) {
            if (
               (int) $payment->billing_id
               !== (int) $billing->id
               || (string) $payment->merchant_trans_id
               !== (string) $billing->id
               || !$this->amountsEqual(
                  $payment->amount,
                  $data['amount']
               )
            ) {
               $dbTransaction->rollBack();
               
               return $this->clickResponse(
                  $data,
                  -6,
                  'Transaction does not exist'
               );
            }
            
            if (
               (int) $payment->status
               === ClickPayment::STATUS_PAID
            ) {
               $dbTransaction->rollBack();
               
               return $this->clickResponse(
                  $data,
                  -4,
                  'Already paid'
               );
            }
            
            if (in_array((int) $payment->status, [
               ClickPayment::STATUS_CANCELLED,
               ClickPayment::STATUS_FAILED,
            ], true)) {
               $dbTransaction->rollBack();
               
               return $this->clickResponse(
                  $data,
                  -9,
                  'Transaction cancelled'
               );
            }
         } else {
            $payment = new ClickPayment();
            $payment->billing_id = (int) $billing->id;
            $payment->click_trans_id = $clickTransId;
            $payment->click_paydoc_id =
               (string) $data['click_paydoc_id'];
            $payment->service_id = (int) $data['service_id'];
            $payment->merchant_trans_id =
               (string) $data['merchant_trans_id'];
            $payment->amount = number_format(
               (float) $data['amount'],
               2,
               '.',
               ''
            );
            $payment->status = ClickPayment::STATUS_PREPARED;
            $payment->click_error = (int) $data['error'];
            $payment->click_error_note =
               (string) $data['error_note'];
            $payment->sign_time = (string) $data['sign_time'];
            $payment->prepared_at = time();
            $payment->created_at = time();
            $payment->updated_at = time();
            
            if (!$payment->save(false)) {
               throw new RuntimeException(
                  'CLICK payment prepare record was not saved.'
               );
            }
         }
         
         $this->markBillingPending($billing, $clickTransId);
         $this->linkWebhookLogToPayment($log, $payment);
         
         $dbTransaction->commit();
         
         return $this->clickResponse($data, 0, 'Success', [
            'merchant_prepare_id' => (int) $payment->id,
         ]);
      } catch (Throwable $e) {
         if ($dbTransaction->isActive) {
            $dbTransaction->rollBack();
         }
         
         throw $e;
      }
   }
   
   /**
    * Complete: завершает ClickPayment, обновляет Billing и только после
    * успешной оплаты создаёт запись истории UserSubscriptions.
    */
   private function completeClickPayment(
      array $data,
      ClickWebhookLog $log
   ): array {
      /** @var ClickPayment|null $payment */
      $payment = ClickPayment::findOne(
         (int) $data['merchant_prepare_id']
      );
      
      if ($payment === null) {
         return $this->clickResponse(
            $data,
            -6,
            'Transaction does not exist'
         );
      }
      
      if (
         (string) $payment->click_trans_id
         !== (string) $data['click_trans_id']
         || (string) $payment->merchant_trans_id
         !== (string) $data['merchant_trans_id']
         || (string) $payment->service_id
         !== (string) $data['service_id']
      ) {
         return $this->clickResponse(
            $data,
            -6,
            'Transaction does not exist'
         );
      }
      
      if (!$this->amountsEqual($payment->amount, $data['amount'])) {
         return $this->clickResponse(
            $data,
            -2,
            'Incorrect parameter amount'
         );
      }
      
      $billing = $this->findBilling(
         (string) $data['merchant_trans_id']
      );
      
      if ($billing === null) {
         return $this->clickResponse(
            $data,
            -5,
            'User does not exist'
         );
      }
      
      if ((int) $payment->billing_id !== (int) $billing->id) {
         return $this->clickResponse(
            $data,
            -6,
            'Transaction does not exist'
         );
      }
      
      $dbTransaction = Yii::$app->db->beginTransaction();
      
      try {
         $this->lockPayment((int) $payment->id);
         $this->lockBilling((int) $billing->id);
         
         $payment->refresh();
         $billing->refresh();
         
         $this->linkWebhookLogToPayment($log, $payment);
         
         $clickTransId = (string) $data['click_trans_id'];
         
         if ((int) $data['error'] < 0) {
            if (
               (int) $payment->status
               !== ClickPayment::STATUS_PAID
            ) {
               $payment->status =
                  ClickPayment::STATUS_CANCELLED;
               $payment->click_error = (int) $data['error'];
               $payment->click_error_note =
                  (string) $data['error_note'];
               $payment->cancelled_at = time();
               $payment->updated_at = time();
               
               if (!$payment->save(false)) {
                  throw new RuntimeException(
                     'CLICK cancellation was not saved.'
                  );
               }
               
               $this->markBillingCancelled(
                  $billing,
                  $clickTransId
               );
            }
            
            $dbTransaction->commit();
            
            return $this->clickResponse(
               $data,
               -9,
               'Transaction cancelled',
               [
                  'merchant_confirm_id' =>
                     (int) $payment->id,
               ]
            );
         }
         
         if (
            (int) $payment->status
            === ClickPayment::STATUS_PAID
            || (int) $billing->payment_status
            === Billing::STATUS_SUCCESS
         ) {
            $dbTransaction->rollBack();
            
            return $this->clickResponse(
               $data,
               -4,
               'Already paid',
               [
                  'merchant_confirm_id' =>
                     (int) $payment->id,
               ]
            );
         }
         
         if (
            (int) $payment->status
            !== ClickPayment::STATUS_PREPARED
         ) {
            $dbTransaction->rollBack();
            
            return $this->clickResponse(
               $data,
               -6,
               'Transaction does not exist'
            );
         }
         
         if (!hash_equals(
            trim((string) $billing->payment_transaction_id),
            $clickTransId
         )) {
            $dbTransaction->rollBack();
            
            return $this->clickResponse(
               $data,
               -6,
               'Transaction does not exist'
            );
         }
         
         $payment->status = ClickPayment::STATUS_PAID;
         $payment->click_error = (int) $data['error'];
         $payment->click_error_note =
            (string) $data['error_note'];
         $payment->paid_at = time();
         $payment->updated_at = time();
         
         if (!$payment->save(false)) {
            throw new RuntimeException(
               'CLICK payment complete record was not saved.'
            );
         }
         
         $this->markBillingSuccess($billing, $clickTransId);
         $this->createOrUpdateUserSubscription(
            $billing,
            $clickTransId
         );
         
         $dbTransaction->commit();
         
         $this->sendPaidWebhookSafely($billing);
         
         return $this->clickResponse($data, 0, 'Success', [
            'merchant_confirm_id' => (int) $payment->id,
         ]);
      } catch (Throwable $e) {
         if ($dbTransaction->isActive) {
            $dbTransaction->rollBack();
         }
         
         throw $e;
      }
   }
   
   private function findBilling(string $merchantTransId): ?Billing
   {
      if (!ctype_digit($merchantTransId)) {
         return null;
      }
      
      return Billing::findOne((int) $merchantTransId);
   }
   
   private function markBillingPending(
      Billing $billing,
      string $clickTransId
   ): void {
      $billing->payment_status = Billing::STATUS_PENDING;
      $billing->payment_transaction_id = $clickTransId;
      $billing->payment_provider = $this->clickProviderCode();
      $billing->updated_at = time();
      
      if (!$billing->save(false)) {
         throw new RuntimeException(
            'Failed to mark Billing as pending.'
         );
      }
   }
   
   private function markBillingSuccess(
      Billing $billing,
      string $clickTransId
   ): void {
      $billing->payment_status = Billing::STATUS_SUCCESS;
      $billing->payment_transaction_id = $clickTransId;
      $billing->payment_provider = $this->clickProviderCode();
      
      $startDate = (int) ($billing->start_date ?: time());
      $billing->start_date = $startDate;
      
      if (empty($billing->expires_date)) {
         $plan = $billing->subscription;
         
         if ($plan === null || (int) $plan->duration_days <= 0) {
            throw new RuntimeException(
               'Subscription plan duration_days is invalid.'
            );
         }
         
         $expiresDate = strtotime(
            '+' . (int) $plan->duration_days . ' days',
            $startDate
         );
         
         if ($expiresDate === false) {
            throw new RuntimeException(
               'Failed to calculate subscription expires_date.'
            );
         }
         
         $billing->expires_date = $expiresDate;
      }
      
      $billing->updated_at = time();
      
      if (!$billing->save(false)) {
         throw new RuntimeException(
            'Failed to mark Billing as successful.'
         );
      }
   }
   
   private function markBillingCancelled(
      Billing $billing,
      string $clickTransId
   ): void {
      $storedTransactionId = trim(
         (string) $billing->payment_transaction_id
      );
      
      // Не даём запоздалому Complete отменить уже другую попытку оплаты.
      if (
         $storedTransactionId !== ''
         && !hash_equals($storedTransactionId, $clickTransId)
      ) {
         return;
      }
      
      if (
         (int) $billing->payment_status
         === Billing::STATUS_SUCCESS
      ) {
         return;
      }
      
      $billing->payment_status = Billing::STATUS_CANCELLED;
      $billing->payment_transaction_id = $clickTransId;
      $billing->payment_provider = $this->clickProviderCode();
      $billing->updated_at = time();
      
      if (!$billing->save(false)) {
         throw new RuntimeException(
            'Failed to mark Billing as cancelled.'
         );
      }
   }
   
   /**
    * UserSubscriptions — только история уже оплаченной подписки.
    * Повторный Complete обновит ту же запись по provider + transaction ID.
    */
   private function createOrUpdateUserSubscription(
      Billing $billing,
      string $clickTransId
   ): void {
      $provider = $this->clickSubscriptionProvider();
      
      /** @var UserSubscriptions|null $subscription */
      $subscription = UserSubscriptions::find()
         ->where([
            'payment_provider' => $provider,
            'payment_transaction_id' => $clickTransId,
         ])
         ->one();
      
      if ($subscription === null) {
         $subscription = new UserSubscriptions();
         $subscription->subscription_key =
            $this->generateSubscriptionKey();
         $subscription->created_at = time();
      }
      
      $subscription->plan_id = (int) $billing->subscription_id;
      $subscription->user_id = (int) $billing->user_id;
      $subscription->status =
         UserSubscriptions::STATUS_ACTIVE;
      $subscription->start_date = (int) $billing->start_date;
      $subscription->expires_date =
         (int) $billing->expires_date;
      $subscription->amount = (int) $billing->amount;
      $subscription->currency_code =
         $this->clickCurrencyCode();
      $subscription->payment_transaction_id = $clickTransId;
      $subscription->payment_provider = $provider;
      $subscription->updated_at = time();
      
      if (!$subscription->save(false)) {
         throw new RuntimeException(
            'Failed to save UserSubscriptions record.'
         );
      }
   }
   
   private function generateSubscriptionKey(): string
   {
      for ($attempt = 0; $attempt < 10; $attempt++) {
         $key = Yii::$app->security
            ->generateRandomString(32);
         
         if (!UserSubscriptions::find()
            ->where(['subscription_key' => $key])
            ->exists()
         ) {
            return $key;
         }
      }
      
      throw new RuntimeException(
         'Failed to generate a unique subscription key.'
      );
   }
   
   private function linkWebhookLogToPayment(
      ClickWebhookLog $log,
      ClickPayment $payment
   ): void {
      if ($log->isNewRecord) {
         return;
      }
      
      $log->click_payment_id = (int) $payment->id;
      $log->save(false);
   }
   
   private function sendPaidWebhookSafely(Billing $billing): void
   {
      try {
         ApiController::sendZapierOrderPaidWebhook($billing);
      } catch (Throwable $e) {
         Yii::error([
            'message' => 'Zapier webhook failed after CLICK payment.',
            'billing_id' => (int) $billing->id,
            'exception' => $e,
         ], 'click');
      }
   }
   
   private function lockBilling(int $id): void
   {
      Yii::$app->db->createCommand(
         'SELECT [[id]] FROM {{%billing}} '
         . 'WHERE [[id]] = :id FOR UPDATE',
         [':id' => $id]
      )->queryScalar();
   }
   
   private function lockPayment(int $id): void
   {
      Yii::$app->db->createCommand(
         'SELECT [[id]] FROM {{%click_payments}} '
         . 'WHERE [[id]] = :id FOR UPDATE',
         [':id' => $id]
      )->queryScalar();
   }
   
   private function amountsEqual($left, $right): bool
   {
      return (int) round((float) $left * 100)
         === (int) round((float) $right * 100);
   }
   
   private function clickResponse(
      array $request,
      int $error,
      string $note,
      array $extra = []
   ): array {
      $response = [
         'error' => $error,
         'error_note' => $note,
      ];
      
      if (array_key_exists('click_trans_id', $request)) {
         $response['click_trans_id'] =
            $request['click_trans_id'];
      }
      
      if (array_key_exists('merchant_trans_id', $request)) {
         $response['merchant_trans_id'] =
            $request['merchant_trans_id'];
      }
      
      return array_merge($response, $extra);
   }
   
   private function clickProviderCode(): int
   {
      return (int) ($this->clickConfig()['providerCode'] ?? 1);
   }
   
   private function clickCurrencyCode(): int
   {
      return (int) ($this->clickConfig()['currencyCode'] ?? 860);
   }
   
   private function clickSubscriptionProvider(): string
   {
      return (string) (
         $this->clickConfig()['subscriptionPaymentProvider']
         ?? 'click'
      );
   }
   
   private function clickConfig(): array
   {
      $config = Yii::$app->params['click'] ?? [];
      
      foreach ([
                  'merchant_id',
                  'service_id',
                  'secret_key',
               ] as $key) {
         if (!isset($config[$key]) || $config[$key] === '') {
            throw new RuntimeException(
               "CLICK config parameter '{$key}' is not set."
            );
         }
      }
      
      return $config;
   }
}
