<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\Billing;
use common\models\UserSubscriptions;
use JsonException;
use RuntimeException;
use Throwable;
use Yii;
use yii\web\Controller;
use yii\web\Response;

/**
 * Endpoint Merchant API Payme:
 * POST https://example.uz/payme/webhook
 */
final class PaymeController extends Controller
{
   private const STATE_CREATED = 1;
   private const STATE_PERFORMED = 2;
   private const STATE_CANCELLED = -1;
   private const STATE_CANCELLED_AFTER_PERFORM = -2;
   
   private const CANCEL_REASON_TIMEOUT = 4;
   private const TRANSACTION_TIMEOUT_MS = 43_200_000; // 12 часов
   
   public function beforeAction($action): bool
   {
      if ($action->id === 'webhook') {
         $this->enableCsrfValidation = false;
      }
      
      return parent::beforeAction($action);
   }
   
   public function actionGetInfo()
   {
      $log = Yii::$app->db->createCommand('Select * from {{%payme_log}} order by id desc')->queryAll();
      echo "<pre>";
      print_r($log);
      die();
   }
   
   public function actionWebhook(): array
   {
      Yii::$app->response->format = Response::FORMAT_JSON;
      Yii::$app->response->statusCode = 200;
      
      $request = Yii::$app->request;
      $rawBody = $request->getRawBody();
      $startedAt = microtime(true);
      
      $rpcId = null;
      $method = null;
      $authorized = false;
      $response = [];
      
      try {
         if (!$request->isPost) {
            throw new PaymeRpcException(
               -32300,
               $this->message(
                  'Запрос должен быть отправлен методом POST',
                  'So‘rov POST orqali yuborilishi kerak',
                  'Request method must be POST'
               )
            );
         }
         
         try {
            $payload = json_decode($rawBody, true, 512, JSON_THROW_ON_ERROR);
         } catch (JsonException) {
            throw new PaymeRpcException(
               -32700,
               $this->message(
                  'Ошибка разбора JSON',
                  'JSON formatini o‘qishda xatolik',
                  'JSON parse error'
               )
            );
         }
         
         if (!is_array($payload)) {
            throw new PaymeRpcException(
               -32600,
               $this->message(
                  'Некорректный RPC-запрос',
                  'Noto‘g‘ri RPC so‘rovi',
                  'Invalid RPC request'
               )
            );
         }
         
         $rpcId = $payload['id'] ?? null;
         $method = is_string($payload['method'] ?? null)
            ? $payload['method']
            : null;
         
        /* $authorized = $this->isAllowedIp() && $this->isAuthorized();
         
         if (!$authorized) {
            throw new PaymeRpcException(
               -32504,
               $this->message(
                  'Недостаточно привилегий',
                  'Huquqlar yetarli emas',
                  'Insufficient privileges'
               )
            );
         }*/
         
         if (
            !array_key_exists('id', $payload)
            || !is_int($payload['id'])
            || !is_string($payload['method'] ?? null)
            || !is_array($payload['params'] ?? null)
         ) {
            throw new PaymeRpcException(
               -32600,
               $this->message(
                  'В RPC-запросе отсутствуют обязательные поля',
                  'RPC so‘rovida majburiy maydonlar yo‘q',
                  'Required RPC fields are missing'
               )
            );
         }
         
         $result = $this->dispatch(
            $payload['method'],
            $payload['params']
         );
         
         $response = [
            'result' => $result,
            'id' => $rpcId,
         ];
      } catch (PaymeRpcException $e) {
         $error = [
            'code' => $e->rpcCode,
            'message' => $e->rpcMessage,
         ];
         
         if ($e->rpcData !== null) {
            $error['data'] = $e->rpcData;
         }
         
         $response = [
            'error' => $error,
            'id' => $rpcId,
         ];
      } catch (Throwable $e) {
         
         Yii::error([
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
         ], 'payme');
         
         $response = [
            'error' => [
               'code' => -32400,
               'message' => $this->message(
                  'Внутренняя ошибка сервера',
                  'Serverning ichki xatosi',
                  'Internal server error'
               ),
            ],
            'id' => $rpcId,
         ];
      }
      
      $this->writeLog(
         rpcId: $rpcId,
         method: $method,
         requestBody: $rawBody,
         response: $response,
         authorized: $authorized,
         durationMs: (int) round((microtime(true) - $startedAt) * 1000)
      );
      
      return $response;
   }
   
   private function dispatch(string $method, array $params): array
   {
      return match ($method) {
         'CheckPerformTransaction' => $this->checkPerformTransaction($params),
         'CreateTransaction' => $this->createTransaction($params),
         'PerformTransaction' => $this->performTransaction($params),
         'CancelTransaction' => $this->cancelTransaction($params),
         'CheckTransaction' => $this->checkTransaction($params),
         'GetStatement' => $this->getStatement($params),
         'SetFiscalData' => $this->setFiscalData($params),
         default => throw new PaymeRpcException(
            -32601,
            $this->message(
               'Метод не найден',
               'Metod topilmadi',
               'Method not found'
            ),
            $method
         ),
      };
   }
   
   private function checkPerformTransaction(array $params): array
   {
      $amount = $this->requiredPositiveInt($params, 'amount');
      $account = $this->requiredArray($params, 'account');
      $billingId = $account['order_id']; //$this->extractBillingId($account);
      
      $billing = Billing::findOne($billingId);
      
      $this->assertBillingCanBePaid($billing, $amount*100);
      
      return ['allow' => true];
   }
   
   private function createTransaction(array $params): array
   {
      $paymeId = $this->requiredPaymeId($params, 'id');
      $paymeTime = $this->requiredPositiveInt($params, 'time');
      $amount = $this->requiredPositiveInt($params, 'amount');
      $amount *= 100;
      $account = $this->requiredArray($params, 'account');
      $billingId = $account['order_id'];//$this->extractBillingId($account);
      
      $db = Yii::$app->db;
      $dbTransaction = $db->beginTransaction();
      
      try {
         $existing = $db->createCommand(
            'SELECT *
                   FROM {{%payme_transaction}}
                  WHERE [[payme_id]] = :payme_id
                  FOR UPDATE',
            [':payme_id' => $paymeId]
         )->queryOne();
         
         // Повторный CreateTransaction обязан вернуть тот же результат.
         if ($existing !== false) {
            $billing = $this->lockBillingById($billingId);
            
            if (
               $billing === null
               || (int) $existing['billing_id'] !== (int) $billing->id
               || (int) $existing['amount'] !== $amount
               || (int) $existing['payme_time'] !== $paymeTime
            ) {
               throw new PaymeRpcException(
                  -31008,
                  $this->message(
                     'Невозможно выполнить операцию',
                     'Amalni bajarib bo‘lmaydi',
                     'Unable to perform operation'
                  )
               );
            }
            
            $dbTransaction->commit();
            
            return $this->createResponse($existing);
         }
         
         $billing = $this->lockBillingById((int)$billingId);
         $this->assertBillingCanBePaid($billing, $amount);
         
         $activeTransaction = $db->createCommand(
            'SELECT [[id]]
                   FROM {{%payme_transaction}}
                  WHERE [[billing_id]] = :billing_id
                    AND [[state]] IN (:created, :performed)
                  LIMIT 1
                  FOR UPDATE',
            [
               ':billing_id' => $billing->id,
               ':created' => self::STATE_CREATED,
               ':performed' => self::STATE_PERFORMED,
            ]
         )->queryOne();
         
         if ($activeTransaction !== false) {
            throw new PaymeRpcException(
               -31008,
               $this->message(
                  'По этому счёту уже существует активная транзакция',
                  'Bu hisob uchun faol tranzaksiya mavjud',
                  'An active transaction already exists for this billing'
               )
            );
         }
         
         $nowMs = $this->nowMs();
         $now = time();
         
         $db->createCommand()->insert('{{%payme_transaction}}', [
            'payme_id' => $paymeId,
            'billing_id' => $billing->id,
            'payme_time' => $paymeTime,
            'amount' => $amount,
            'account' => $this->jsonEncode($account),
            'create_time' => $nowMs,
            'perform_time' => 0,
            'cancel_time' => 0,
            'state' => self::STATE_CREATED,
            'reason' => null,
            'created_at' => $now,
            'updated_at' => $now,
         ])->execute();
         
         $localTransactionId = (string) $db->getLastInsertID();
         
         $this->markBillingPending($billing, $paymeId);
         
         $dbTransaction->commit();
         
         return [
            'create_time' => $nowMs,
            'transaction' => $localTransactionId,
            'state' => self::STATE_CREATED,
         ];
      } catch (Throwable $e) {
         if ($dbTransaction->isActive) {
            $dbTransaction->rollBack();
         }
         
         throw $e;
      }
   }
   
   private function performTransaction(array $params): array
   {
      $paymeId = $this->requiredPaymeId($params, 'id');
      
      $db = Yii::$app->db;
      $dbTransaction = $db->beginTransaction();
      
      try {
         $transaction = $this->lockTransaction($paymeId);
         
         if ($transaction === null) {
            throw new PaymeRpcException(
               -31003,
               $this->message(
                  'Транзакция не найдена',
                  'Tranzaksiya topilmadi',
                  'Transaction not found'
               )
            );
         }
         
         if ((int) $transaction['state'] === self::STATE_PERFORMED) {
            $dbTransaction->commit();
            
            return $this->performResponse($transaction);
         }
         
         if ((int) $transaction['state'] !== self::STATE_CREATED) {
            throw new PaymeRpcException(
               -31008,
               $this->message(
                  'Невозможно выполнить операцию',
                  'Amalni bajarib bo‘lmaydi',
                  'Unable to perform operation'
               )
            );
         }
         
         $nowMs = $this->nowMs();
         
         if (
            ($nowMs - (int) $transaction['payme_time'])
            >= self::TRANSACTION_TIMEOUT_MS
         ) {
            $billing = $this->lockBillingById(
               (int) $transaction['billing_id']
            );
            
            $db->createCommand()->update('{{%payme_transaction}}', [
               'state' => self::STATE_CANCELLED,
               'reason' => self::CANCEL_REASON_TIMEOUT,
               'cancel_time' => $nowMs,
               'updated_at' => time(),
            ], ['id' => $transaction['id']])->execute();
            
            if ($billing !== null) {
               $this->markBillingCancelled($billing, false);
            }
            
            $dbTransaction->commit();
            
            throw new PaymeRpcException(
               -31008,
               $this->message(
                  'Транзакция отменена по таймауту',
                  'Tranzaksiya vaqt tugashi sababli bekor qilindi',
                  'Transaction cancelled by timeout'
               )
            );
         }
         
         $billing = $this->lockBillingById(
            (int) $transaction['billing_id']
         );
         
         if ($billing === null) {
            throw new RuntimeException(
               'Billing not found during PerformTransaction.'
            );
         }
         
         $this->markBillingSuccess($billing, $paymeId);
         
         $db->createCommand()->update('{{%payme_transaction}}', [
            'state' => self::STATE_PERFORMED,
            'perform_time' => $nowMs,
            'updated_at' => time(),
         ], ['id' => $transaction['id']])->execute();
         
         $transaction['state'] = self::STATE_PERFORMED;
         $transaction['perform_time'] = $nowMs;
         
         $dbTransaction->commit();
         
         return $this->performResponse($transaction);
      } catch (Throwable $e) {
         if ($dbTransaction->isActive) {
            $dbTransaction->rollBack();
         }
         
         throw $e;
      }
   }
   
   private function cancelTransaction(array $params): array
   {
      $paymeId = $this->requiredPaymeId($params, 'id');
      $reason = $this->requiredPositiveInt($params, 'reason');
      
      $db = Yii::$app->db;
      $dbTransaction = $db->beginTransaction();
      
      try {
         $transaction = $this->lockTransaction($paymeId);
         
         if ($transaction === null) {
            throw new PaymeRpcException(
               -31003,
               $this->message(
                  'Транзакция не найдена',
                  'Tranzaksiya topilmadi',
                  'Transaction not found'
               )
            );
         }
         
         $state = (int) $transaction['state'];
         
         if (in_array(
            $state,
            [
               self::STATE_CANCELLED,
               self::STATE_CANCELLED_AFTER_PERFORM,
            ],
            true
         )) {
            $dbTransaction->commit();
            
            return $this->cancelResponse($transaction);
         }
         
         if (!in_array(
            $state,
            [self::STATE_CREATED, self::STATE_PERFORMED],
            true
         )) {
            throw new PaymeRpcException(
               -31008,
               $this->message(
                  'Невозможно выполнить операцию',
                  'Amalni bajarib bo‘lmaydi',
                  'Unable to perform operation'
               )
            );
         }
         
         if (
            $state === self::STATE_PERFORMED
            && !$this->canCancelPerformedBilling()
         ) {
            throw new PaymeRpcException(
               -31007,
               $this->message(
                  'Услуга уже предоставлена, отмена невозможна',
                  'Xizmat ko‘rsatilgan, bekor qilib bo‘lmaydi',
                  'The service has already been provided'
               )
            );
         }
         
         $newState = $state === self::STATE_PERFORMED
            ? self::STATE_CANCELLED_AFTER_PERFORM
            : self::STATE_CANCELLED;
         
         $cancelTime = $this->nowMs();
         
         $billing = $this->lockBillingById(
            (int) $transaction['billing_id']
         );
         
         $db->createCommand()->update('{{%payme_transaction}}', [
            'state' => $newState,
            'reason' => $reason,
            'cancel_time' => $cancelTime,
            'updated_at' => time(),
         ], ['id' => $transaction['id']])->execute();
         
         if ($billing !== null) {
            $this->markBillingCancelled(
               $billing,
               $state === self::STATE_PERFORMED
            );
         }
         
         $transaction['state'] = $newState;
         $transaction['reason'] = $reason;
         $transaction['cancel_time'] = $cancelTime;
         
         $dbTransaction->commit();
         
         return $this->cancelResponse($transaction);
      } catch (Throwable $e) {
         if ($dbTransaction->isActive) {
            $dbTransaction->rollBack();
         }
         
         throw $e;
      }
   }
   
   private function checkTransaction(array $params): array
   {
      $paymeId = $this->requiredPaymeId($params, 'id');
      
      $transaction = Yii::$app->db->createCommand(
         'SELECT *
               FROM {{%payme_transaction}}
              WHERE [[payme_id]] = :payme_id',
         [':payme_id' => $paymeId]
      )->queryOne();
      
      if ($transaction === false) {
         throw new PaymeRpcException(
            -31003,
            $this->message(
               'Транзакция не найдена',
               'Tranzaksiya topilmadi',
               'Transaction not found'
            )
         );
      }
      
      return $this->checkResponse($transaction);
   }
   
   private function getStatement(array $params): array
   {
      $from = $this->requiredNonNegativeInt($params, 'from');
      $to = $this->requiredNonNegativeInt($params, 'to');
      
      if ($from > $to) {
         throw new PaymeRpcException(
            -32600,
            $this->message(
               'Некорректный период',
               'Noto‘g‘ri davr',
               'Invalid period'
            )
         );
      }
      
      $rows = Yii::$app->db->createCommand(
         'SELECT *
               FROM {{%payme_transaction}}
              WHERE [[payme_time]] BETWEEN :from_time AND :to_time
              ORDER BY [[payme_time]] ASC',
         [
            ':from_time' => $from,
            ':to_time' => $to,
         ]
      )->queryAll();
      
      $transactions = array_map(
         function (array $row): array {
            return [
               'id' => $row['payme_id'],
               'time' => (int) $row['payme_time'],
               'amount' => (int) $row['amount'],
               'account' => $this->jsonDecode($row['account']),
               'create_time' => (int) $row['create_time'],
               'perform_time' => (int) $row['perform_time'],
               'cancel_time' => (int) $row['cancel_time'],
               'transaction' => (string) $row['id'],
               'state' => (int) $row['state'],
               'reason' => $row['reason'] !== null
                  ? (int) $row['reason']
                  : null,
            ];
         },
         $rows
      );
      
      return ['transactions' => $transactions];
   }
   
   /**
    * Необязательный метод для получения данных фискального чека.
    */
   private function setFiscalData(array $params): array
   {
      $receiptId = $this->requiredPaymeId($params, 'id');
      $type = $params['type'] ?? null;
      $fiscalData = $params['fiscal_data'] ?? null;
      
      if (
         !in_array($type, ['PERFORM', 'CANCEL'], true)
         || !is_array($fiscalData)
      ) {
         throw new PaymeRpcException(
            -32602,
            'Invalid fiscal parameters'
         );
      }
      
      $transaction = Yii::$app->db->createCommand(
         'SELECT [[id]]
               FROM {{%payme_transaction}}
              WHERE [[payme_id]] = :payme_id',
         [':payme_id' => $receiptId]
      )->queryOne();
      
      if ($transaction === false) {
         throw new PaymeRpcException(
            -32001,
            'Receipt not found'
         );
      }
      
      $column = $type === 'PERFORM'
         ? 'fiscal_perform'
         : 'fiscal_cancel';
      
      Yii::$app->db->createCommand()
         ->update('{{%payme_transaction}}', [
            $column => $this->jsonEncode($fiscalData),
            'updated_at' => time(),
         ], ['id' => $transaction['id']])
         ->execute();
      
      return ['success' => true];
   }
   
   private function assertBillingCanBePaid(Billing $billing, int $amountTiyin): void {
      if ($billing === null) {
         throw new PaymeRpcException(
            -31050,
            $this->message(
               'Счёт не найден',
               'Hisob topilmadi',
               'Billing not found'
            ),
            'billing_id'
         );
      }
      
      $paymentStatus = $billing->payment_status;
      
      $allowedStatuses = [
         null,
         Billing::STATUS_PENDING,
         Billing::STATUS_FAILED,
         Billing::STATUS_CANCELLED,
      ];
      
      if (!in_array($paymentStatus, $allowedStatuses, true)) {
         throw new PaymeRpcException(
            -31050,
            $this->message(
               'Счёт недоступен для оплаты',
               'Hisobni to‘lab bo‘lmaydi',
               'Billing is not available for payment'
            ),
            'billing_id'
         );
      }
      
      if ($this->billingAmountTiyin($billing) !== $amountTiyin) {
         throw new PaymeRpcException(
            -31001,
            $this->message(
               'Неверная сумма',
               'Noto‘g‘ri summa',
               'Invalid amount'
            )
         );
      }
   }
   
   private function billingAmountTiyin(Billing $billing): int
   {
      $amount = (int) $billing->amount;
      
      return (bool) $this->config('amountAlreadyInTiyin', false)
         ? $amount
         : $amount * 100;
   }
   
   private function markBillingPending(
      Billing $billing,
      string $paymeId
   ): void {
      $billing->payment_status = Billing::STATUS_PENDING;
      $billing->payment_transaction_id = $paymeId;
      $billing->payment_provider = (int) $this->config(
         'providerCode',
         2
      );
      
      if (!$billing->save(false)) {
         throw new RuntimeException(
            'Failed to mark Billing as pending.'
         );
      }
   }
   
   private function markBillingSuccess(
      Billing $billing,
      string $paymeId
   ): void {
      $billing->payment_status = Billing::STATUS_SUCCESS;
      $billing->payment_transaction_id = $paymeId;
      $billing->payment_provider = (int) $this->config(
         'providerCode',
         2
      );
      
      $startDate = $billing->start_date ?: time();
      $billing->start_date = $startDate;
      
      if (empty($billing->expires_date)) {
         $duration = (string) $this->config(
            'subscriptionDuration',
            '+3 months'
         );
         
         $expiresDate = strtotime($duration, $startDate);
         
         if ($expiresDate === false) {
            throw new RuntimeException(
               'Invalid subscriptionDuration configuration.'
            );
         }
         
         $billing->expires_date = $expiresDate;
      }
      
      if (!$billing->save(false)) {
         throw new RuntimeException(
            'Failed to mark Billing as successful.'
         );
      }
      
      /*
       * Billing остаётся финансовым документом, а отдельная запись
       * UserSubscriptions используется для доступа и истории подписок.
       *
       * Метод вызывается внутри той же DB-транзакции, что и
       * PerformTransaction. Если создание подписки упадёт, успешный
       * платёж также не будет частично зафиксирован в локальной БД.
       */
      $this->createOrUpdateUserSubscription(
         $billing,
         $paymeId
      );
   }
   
   private function markBillingCancelled(
      Billing $billing,
      bool $wasPerformed
   ): void {
      $billing->payment_status = Billing::STATUS_CANCELLED;
      $billing->payment_provider = (int) $this->config(
         'providerCode',
         2
      );
      
      /*
       * При возврате уже проведённого платежа оставляем запись
       * UserSubscriptions для истории, но закрываем доступ.
       */
      if ($wasPerformed) {
         $this->deactivateUserSubscription($paymeId);
      }
      
      if (
         $wasPerformed
         && (bool) $this->config(
            'clearSubscriptionDatesOnRefund',
            true
         )
      ) {
         $billing->start_date = null;
         $billing->expires_date = null;
      }
      
      if (!$billing->save(false)) {
         throw new RuntimeException(
            'Failed to mark Billing as cancelled.'
         );
      }
   }
   
   /**
    * Создаёт подписку после успешного PerformTransaction.
    *
    * Идемпотентность обеспечивается поиском по паре:
    * payment_provider + payment_transaction_id.
    */
   private function createOrUpdateUserSubscription(
      Billing $billing,
      string $paymeId
   ): void {
      $provider = (string) $this->config(
         'subscriptionPaymentProvider',
         'payme'
      );
      
      /** @var UserSubscriptions|null $subscription */
      $subscription = UserSubscriptions::find()
         ->where([
            'payment_provider' => $provider,
            'payment_transaction_id' => $paymeId,
         ])
         ->one();
      
      $isNewRecord = $subscription === null;
      
      if ($subscription === null) {
         $subscription = new UserSubscriptions();
         $subscription->subscription_key =
            $this->generateSubscriptionKey();
         $subscription->created_at = time();
      }
      
      $subscription->plan_id = (int) $billing->subscription_id;
      $subscription->user_id = (int) $billing->user_id;
      $subscription->status = UserSubscriptions::STATUS_ACTIVE;
      $subscription->start_date = (int) $billing->start_date;
      $subscription->expires_date = (int) $billing->expires_date;
      $subscription->amount = (int) $billing->amount;
      $subscription->currency_code = (int) $this->config(
         'currencyCode',
         860
      );
      $subscription->payment_transaction_id = $paymeId;
      $subscription->payment_provider = $provider;
      $subscription->updated_at = time();
      
      if (!$subscription->save(false)) {
         throw new RuntimeException(
            $isNewRecord
               ? 'Failed to create UserSubscriptions record.'
               : 'Failed to update UserSubscriptions record.'
         );
      }
   }
   
   /**
    * После возврата успешного платежа подписка не удаляется:
    * это сохраняет историю, но status=INACTIVE закрывает доступ.
    */
   private function deactivateUserSubscription(
      string $paymeId
   ): void {
      $provider = (string) $this->config(
         'subscriptionPaymentProvider',
         'payme'
      );
      
      /** @var UserSubscriptions|null $subscription */
      $subscription = UserSubscriptions::find()
         ->where([
            'payment_provider' => $provider,
            'payment_transaction_id' => $paymeId,
         ])
         ->one();
      
      if ($subscription === null) {
         return;
      }
      
      $subscription->status =
         UserSubscriptions::STATUS_INACTIVE;
      $subscription->updated_at = time();
      
      if (!$subscription->save(false)) {
         throw new RuntimeException(
            'Failed to deactivate UserSubscriptions record.'
         );
      }
   }
   
   private function generateSubscriptionKey(): string
   {
      for ($attempt = 0; $attempt < 10; $attempt++) {
         $key = Yii::$app->security
            ->generateRandomString(32);
         
         $exists = UserSubscriptions::find()
            ->where(['subscription_key' => $key])
            ->exists();
         
         if (!$exists) {
            return $key;
         }
      }
      
      throw new RuntimeException(
         'Failed to generate a unique subscription key.'
      );
   }
   
   private function canCancelPerformedBilling(): bool
   {
      return (bool) $this->config(
         'allowCancelPerformed',
         true
      );
   }
   
   private function lockBillingById(int $billingId): ?Billing
   {
      $row = Yii::$app->db->createCommand(
         'SELECT [[id]]
               FROM {{%billing}}
              WHERE [[id]] = :id
              FOR UPDATE',
         [':id' => $billingId]
      )->queryOne();
      
      return $row === false
         ? null
         : Billing::findOne($billingId);
   }
   
   private function lockTransaction(
      string $paymeId
   ): ?array {
      $row = Yii::$app->db->createCommand(
         'SELECT *
               FROM {{%payme_transaction}}
              WHERE [[payme_id]] = :payme_id
              FOR UPDATE',
         [':payme_id' => $paymeId]
      )->queryOne();
      
      return $row === false ? null : $row;
   }
   
   private function createResponse(array $transaction): array
   {
      return [
         'create_time' => (int) $transaction['create_time'],
         'transaction' => (string) $transaction['id'],
         'state' => (int) $transaction['state'],
      ];
   }
   
   private function performResponse(array $transaction): array
   {
      return [
         'transaction' => (string) $transaction['id'],
         'perform_time' => (int) $transaction['perform_time'],
         'state' => (int) $transaction['state'],
      ];
   }
   
   private function cancelResponse(array $transaction): array
   {
      return [
         'transaction' => (string) $transaction['id'],
         'cancel_time' => (int) $transaction['cancel_time'],
         'state' => (int) $transaction['state'],
      ];
   }
   
   private function checkResponse(array $transaction): array
   {
      return [
         'create_time' => (int) $transaction['create_time'],
         'perform_time' => (int) $transaction['perform_time'],
         'cancel_time' => (int) $transaction['cancel_time'],
         'transaction' => (string) $transaction['id'],
         'state' => (int) $transaction['state'],
         'reason' => $transaction['reason'] !== null
            ? (int) $transaction['reason']
            : null,
      ];
   }
   
   private function extractBillingId(array $account): int
   {
      $billingId = $account['billing_id'] ?? null;
      
      if (
         !is_int($billingId)
         && !(
            is_string($billingId)
            && ctype_digit($billingId)
         )
      ) {
         throw new PaymeRpcException(
            -31050,
            $this->message(
               'Неверный ID счёта',
               'Hisob ID noto‘g‘ri',
               'Invalid billing ID'
            ),
            'billing_id'
         );
      }
      
      $billingId = (int) $billingId;
      
      if ($billingId <= 0) {
         throw new PaymeRpcException(
            -31050,
            $this->message(
               'Неверный ID счёта',
               'Hisob ID noto‘g‘ri',
               'Invalid billing ID'
            ),
            'billing_id'
         );
      }
      
      return $billingId;
   }
   
   private function requiredPaymeId(
      array $params,
      string $key
   ): string {
      $value = $params[$key] ?? null;
      
      if (
         !is_string($value)
         || preg_match('/^[a-f0-9]{24}$/i', $value) !== 1
      ) {
         throw new PaymeRpcException(
            -32600,
            $this->message(
               'Некорректный идентификатор транзакции',
               'Tranzaksiya ID noto‘g‘ri',
               'Invalid transaction ID'
            )
         );
      }
      
      return $value;
   }
   
   private function requiredPositiveInt(
      array $params,
      string $key
   ): int {
      $value = $params[$key] ?? null;
      
      if (!is_int($value) || $value <= 0) {
         throw new PaymeRpcException(
            -32600,
            $this->message(
               "Некорректный параметр: {$key}",
               "Noto‘g‘ri parametr: {$key}",
               "Invalid parameter: {$key}"
            )
         );
      }
      
      return $value;
   }
   
   private function requiredNonNegativeInt(
      array $params,
      string $key
   ): int {
      $value = $params[$key] ?? null;
      
      if (!is_int($value) || $value < 0) {
         throw new PaymeRpcException(
            -32600,
            $this->message(
               "Некорректный параметр: {$key}",
               "Noto‘g‘ri parametr: {$key}",
               "Invalid parameter: {$key}"
            )
         );
      }
      
      return $value;
   }
   
   private function requiredArray(
      array $params,
      string $key
   ): array {
      $value = $params[$key] ?? null;
      
      if (!is_array($value)) {
         throw new PaymeRpcException(
            -32600,
            $this->message(
               "Некорректный параметр: {$key}",
               "Noto‘g‘ri parametr: {$key}",
               "Invalid parameter: {$key}"
            )
         );
      }
      
      return $value;
   }
   
   private function isAuthorized(): bool
   {
      $authorization = (string) Yii::$app->request
         ->headers
         ->get('Authorization', '');
      
      if (!str_starts_with($authorization, 'Basic ')) {
         return false;
      }
      
      $decoded = base64_decode(
         substr($authorization, 6),
         true
      );
      
      if (
         $decoded === false
         || !str_contains($decoded, ':')
      ) {
         return false;
      }
      
      [$login, $password] = explode(':', $decoded, 2);
      
      $expectedLogin = (string) $this->config(
         'login',
         ''
      );
      $expectedPassword = (string) $this->config(
         'key',
         ''
      );
      
      return $expectedLogin !== ''
         && $expectedPassword !== ''
         && hash_equals($expectedLogin, $login)
         && hash_equals($expectedPassword, $password);
   }
   
   private function isAllowedIp(): bool
   {
      $allowedIps = $this->config('allowedIps', []);
      
      if (!is_array($allowedIps) || $allowedIps === []) {
         return true;
      }
      
      return in_array(
         Yii::$app->request->userIP,
         $allowedIps,
         true
      );
   }
   
   private function config(
      string $path,
      mixed $default = null
   ): mixed {
      $value = Yii::$app->params['payme'] ?? [];
      
      foreach (explode('.', $path) as $segment) {
         if (
            !is_array($value)
            || !array_key_exists($segment, $value)
         ) {
            return $default;
         }
         
         $value = $value[$segment];
      }
      
      return $value;
   }
   
   private function nowMs(): int
   {
      return (int) floor(microtime(true) * 1000);
   }
   
   private function jsonEncode(array $value): string
   {
      return json_encode(
         $value,
         JSON_UNESCAPED_UNICODE
         | JSON_UNESCAPED_SLASHES
         | JSON_THROW_ON_ERROR
      );
   }
   
   private function jsonDecode(?string $value): array
   {
      if ($value === null || $value === '') {
         return [];
      }
      
      try {
         $decoded = json_decode(
            $value,
            true,
            512,
            JSON_THROW_ON_ERROR
         );
         
         return is_array($decoded) ? $decoded : [];
      } catch (JsonException) {
         return [];
      }
   }
   
   private function message(
      string $ru,
      string $uz,
      string $en
   ): array {
      return compact('ru', 'uz', 'en');
   }
   
   private function writeLog(
      mixed $rpcId,
      ?string $method,
      string $requestBody,
      array $response,
      bool $authorized,
      int $durationMs
   ): void {
      try {
         Yii::$app->db->createCommand()
            ->insert('{{%payme_log}}', [
               'rpc_id' => is_int($rpcId)
                  ? $rpcId
                  : null,
               'method' => $method,
               'request_body' => $requestBody,
               'response_body' => json_encode(
                  $response,
                  JSON_UNESCAPED_UNICODE
                  | JSON_UNESCAPED_SLASHES
               ),
               'authorization_ok' => $authorized ? 1 : 0,
               'ip' => Yii::$app->request->userIP,
               'duration_ms' => $durationMs,
               'created_at' => time(),
            ])
            ->execute();
      } catch (Throwable $e) {
         // Ошибка логирования не должна ломать оплату.
         Yii::error(
            $e->getMessage(),
            'payme-log'
         );
      }
   }
}

final class PaymeRpcException extends RuntimeException
{
   public function __construct(
      public readonly int $rpcCode,
      public readonly array|string $rpcMessage,
      public readonly mixed $rpcData = null
   ) {
      parent::__construct(
         is_string($rpcMessage)
            ? $rpcMessage
            : ($rpcMessage['ru'] ?? 'Payme RPC error')
      );
   }
}
