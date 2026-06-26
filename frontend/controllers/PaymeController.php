<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\Billing;
use common\models\PaymeLog;
use common\models\PaymeTransaction;
use common\models\UserSubscriptions;
use JsonException;
use RuntimeException;
use Throwable;
use Yii;
use yii\db\IntegrityException;
use yii\web\Controller;
use yii\web\Response;

/**
 * Merchant API Payme endpoint:
 * POST https://example.uz/payme/webhook
 */
final class PaymeController extends Controller
{
   private const TRANSACTION_TIMEOUT_MS = 43_200_000; // 12 часов
   
   public function beforeAction($action): bool
   {
      if ($action->id === 'webhook') {
         $this->enableCsrfValidation = false;
      }
      
      return parent::beforeAction($action);
   }
   
   public function actionTest()
   {
      echo "<pre>";
      print_r(base64_decode('UGF5Y29tOm9FUXptcSY4QGlOeTIwanY2YW1Nc3N3ZCZtQUhzcEJ2I21VWg=='));
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
            $payload = json_decode(
               $rawBody,
               true,
               512,
               JSON_THROW_ON_ERROR
            );
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
         
         $authorized = $this->isAuthorized();
         
         if (!$authorized) {
            throw new PaymeRpcException(
               -32504,
               $this->message(
                  'Недостаточно привилегий',
                  'Huquqlar yetarli emas',
                  'Insufficient privileges'
               )
            );
         }
         
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
         
         $response = [
            'result' => $this->dispatch(
               $payload['method'],
               $payload['params']
            ),
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
         durationMs: (int) round(
            (microtime(true) - $startedAt) * 1000
         )
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
      $billingId = $this->extractBillingId($account);
      
      $billing = Billing::findOne($billingId);
      $this->assertBillingCanBePaid($billing, $amount *100);
      
      return ['allow' => true];
   }
   
   private function createTransaction(array $params): array
   {
      $paymeId = $this->requiredPaymeId($params, 'id');
      $paymeTime = $this->requiredPositiveInt($params, 'time');
      $amount = $this->requiredPositiveInt($params, 'amount');
      $account = $this->requiredArray($params, 'account');
      $billingId = $this->extractBillingId($account);
      
      $dbTransaction = Yii::$app->db->beginTransaction();
      
      try {
         $existing = PaymeTransaction::findByPaymeId($paymeId);
         
         if ($existing !== null) {
            $billing = Billing::findOne($billingId);
            $this->assertStoredPaymeToken($billing, $paymeId);
            $this->assertSameTransaction(
               $existing,
               $billing,
               $paymeTime,
               $amount
            );
            
            $dbTransaction->commit();
            
            return $this->createResponse($existing);
         }
         
         $billing = Billing::findOne($billingId);
         $this->assertBillingCanBePaid($billing, $amount *100);
         
         $isRecovery = $this->isCreateRecovery(
            $billing,
            $paymeId
         );
         
         $this->claimBillingForTransaction(
            $billing,
            $paymeId
         );
         
         $activeTransaction = PaymeTransaction::find()
            ->where(['billing_id' => $billing->id])
            ->andWhere([
               'state' => [
                  PaymeTransaction::STATE_CREATED,
                  PaymeTransaction::STATE_PERFORMED,
               ],
            ])
            ->one();
         
         if (
            $activeTransaction !== null
            && $activeTransaction->payme_id !== $paymeId
         ) {
            throw new PaymeRpcException(
               -31008,
               $this->message(
                  'По этому счёту уже существует активная транзакция',
                  'Bu hisob uchun faol tranzaksiya mavjud',
                  'An active transaction already exists for this billing'
               )
            );
         }
         
         $transaction = new PaymeTransaction();
         $transaction->payme_id = $paymeId;
         $transaction->billing_id = $billing->id;
         $transaction->payme_time = $paymeTime;
         $transaction->amount = $amount;
         $transaction->setAccountData($account);
         $transaction->create_time = $this->nowMs();
         $transaction->perform_time = 0;
         $transaction->cancel_time = 0;
         $transaction->state = PaymeTransaction::STATE_CREATED;
         $transaction->reason = null;
         $transaction->is_recovered = $isRecovery ? 1 : 0;
         $transaction->created_at = time();
         $transaction->updated_at = time();
         
         try {
            $this->saveTransaction($transaction);
         } catch (IntegrityException) {
            // Параллельный повторный запрос мог уже создать запись.
            $transaction = PaymeTransaction::findByPaymeId($paymeId);
            
            if ($transaction === null) {
               throw new RuntimeException(
                  'Payme transaction was not saved after duplicate key error.'
               );
            }
         }
         
         $this->markBillingPending($billing, $paymeId);
         
         $dbTransaction->commit();
         
         return $this->createResponse($transaction);
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
      $dbTransaction = Yii::$app->db->beginTransaction();
      
      try {
         $transaction = $this->findOrRecoverTransaction($paymeId);
         
         if (
            (int) $transaction->state
            === PaymeTransaction::STATE_PERFORMED
         ) {
            $dbTransaction->commit();
            
            return $this->performResponse($transaction);
         }
         
         if (
            (int) $transaction->state
            !== PaymeTransaction::STATE_CREATED
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
         
         $billing = Billing::findOne($transaction->billing_id);
         $this->assertStoredPaymeToken($billing, $paymeId);
         
         $nowMs = $this->nowMs();
         
         if (
            (int) $transaction->payme_time > 0
            && ($nowMs - (int) $transaction->payme_time)
            >= self::TRANSACTION_TIMEOUT_MS
         ) {
            $transaction->state = PaymeTransaction::STATE_CANCELLED;
            $transaction->reason = PaymeTransaction::CANCEL_REASON_TIMEOUT;
            $transaction->cancel_time = $nowMs;
            $transaction->updated_at = time();
            $this->saveTransaction($transaction);
            
            $this->markBillingCancelled($billing, false);
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
         
         $this->markBillingSuccess($billing, $paymeId);
         
         $transaction->state = PaymeTransaction::STATE_PERFORMED;
         $transaction->perform_time = $nowMs;
         $transaction->updated_at = time();
         $this->saveTransaction($transaction);
         $billing->status = Billing::STATUS_SUCCESS;
         $billing->updated_at = time();
         $billing->save(false);
         if ($billing !== null) {
            ApiController::sendZapierOrderPaidWebhook($billing);
         }
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
      $dbTransaction = Yii::$app->db->beginTransaction();
      
      try {
         $transaction = $this->findOrRecoverTransaction($paymeId);
         $state = (int) $transaction->state;
         
         if (in_array(
            $state,
            [
               PaymeTransaction::STATE_CANCELLED,
               PaymeTransaction::STATE_CANCELLED_AFTER_PERFORM,
            ],
            true
         )) {
            $dbTransaction->commit();
            
            return $this->cancelResponse($transaction);
         }
         
         if (!in_array(
            $state,
            [
               PaymeTransaction::STATE_CREATED,
               PaymeTransaction::STATE_PERFORMED,
            ],
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
            $state === PaymeTransaction::STATE_PERFORMED
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
         
         $billing = Billing::findOne($transaction->billing_id);
         $this->assertStoredPaymeToken($billing, $paymeId);
         
         $transaction->state =
            $state === PaymeTransaction::STATE_PERFORMED
               ? PaymeTransaction::STATE_CANCELLED_AFTER_PERFORM
               : PaymeTransaction::STATE_CANCELLED;
         $transaction->reason = $reason;
         $transaction->cancel_time = $this->nowMs();
         $transaction->updated_at = time();
         $this->saveTransaction($transaction);
         
         $this->markBillingCancelled(
            $billing,
            $state === PaymeTransaction::STATE_PERFORMED
         );
         $billing->status = Billing::STATUS_CANCELLED;
         $billing->updated_at = time();
         $billing->save(false);
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
      $transaction = $this->findOrRecoverTransaction($paymeId);
      
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
      
      /** @var PaymeTransaction[] $rows */
      $rows = PaymeTransaction::find()
         ->where(['between', 'payme_time', $from, $to])
         ->orderBy(['payme_time' => SORT_ASC])
         ->all();
      
      return [
         'transactions' => array_map(
            fn (PaymeTransaction $transaction): array =>
            $this->statementResponse($transaction),
            $rows
         ),
      ];
   }
   
   private function setFiscalData(array $params): array
   {
      $paymeId = $this->requiredPaymeId($params, 'id');
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
      
      $transaction = $this->findOrRecoverTransaction($paymeId);
      $encoded = $this->jsonEncode($fiscalData);
      
      if ($type === 'PERFORM') {
         $transaction->fiscal_perform = $encoded;
      } else {
         $transaction->fiscal_cancel = $encoded;
      }
      
      $transaction->updated_at = time();
      $this->saveTransaction($transaction);
      
      return ['success' => true];
   }
   
   /**
    * Восстанавливает AR-запись, если CreateTransaction успел сохранить
    * Payme ID в Billing, но payme_transaction по какой-то причине отсутствует.
    */
   private function findOrRecoverTransaction(
      string $paymeId
   ): PaymeTransaction {
      $transaction = PaymeTransaction::findByPaymeId($paymeId);
      
      if ($transaction !== null) {
         return $transaction;
      }
      
      /** @var Billing|null $billing */
      $billing = Billing::find()
         ->where(['payment_transaction_id' => $paymeId])
         ->one();
      
      if ($billing === null) {
         throw $this->transactionNotFoundError();
      }
      
      $this->assertStoredPaymeToken($billing, $paymeId);
      
      $timestampMs = $this->billingTimestampMs($billing);
      $transaction = new PaymeTransaction();
      $transaction->payme_id = $paymeId;
      $transaction->billing_id = $billing->id;
      $transaction->payme_time = $timestampMs;
      $transaction->amount = $this->billingAmountTiyin($billing);
      $transaction->setAccountData([
         'billing_id' => $billing->id,
      ]);
      $transaction->create_time = $timestampMs;
      $transaction->perform_time = 0;
      $transaction->cancel_time = 0;
      $transaction->reason = null;
      $transaction->state = PaymeTransaction::STATE_CREATED;
      $transaction->is_recovered = 1;
      $transaction->created_at = time();
      $transaction->updated_at = time();
      
      if (
         (int) $billing->payment_status
         === Billing::STATUS_SUCCESS
      ) {
         $transaction->state = PaymeTransaction::STATE_PERFORMED;
         $transaction->perform_time = $timestampMs;
      } elseif (
         (int) $billing->payment_status
         === Billing::STATUS_CANCELLED
      ) {
         $transaction->state = $this->hasSubscriptionHistory($paymeId)
            ? PaymeTransaction::STATE_CANCELLED_AFTER_PERFORM
            : PaymeTransaction::STATE_CANCELLED;
         $transaction->cancel_time = $timestampMs;
         $transaction->reason =
            PaymeTransaction::CANCEL_REASON_SYSTEM_ERROR;
      }
      
      try {
         $this->saveTransaction($transaction);
      } catch (IntegrityException) {
         $existing = PaymeTransaction::findByPaymeId($paymeId);
         
         if ($existing !== null) {
            return $existing;
         }
         
         throw new RuntimeException(
            'Failed to recover Payme transaction.'
         );
      }
      
      return $transaction;
   }
   
   private function isCreateRecovery(
      Billing $billing,
      string $paymeId
   ): bool {
      if (
         (int) $billing->payment_status
         !== Billing::STATUS_PENDING
      ) {
         return false;
      }
      
      $storedToken = trim(
         (string) $billing->payment_transaction_id
      );
      
      if ($storedToken === '') {
         // Обычный первый CreateTransaction: Billing уже может быть
         // STATUS_PENDING, но Payme ID ещё не был получен.
         return false;
      }
      
      if (!hash_equals($storedToken, $paymeId)) {
         throw new PaymeRpcException(
            -31050,
            $this->message(
               'Этот счёт ожидает другую транзакцию Payme',
               'Bu hisob boshqa Payme tranzaksiyasini kutmoqda',
               'This billing is waiting for another Payme transaction'
            )
         );
      }
      
      return true;
   }
   
   private function claimBillingForTransaction(
      Billing $billing,
      string $paymeId
   ): void {
      $currentToken = trim(
         (string) $billing->payment_transaction_id
      );
      
      if (
         (int) $billing->payment_status
         === Billing::STATUS_PENDING
         && $currentToken !== ''
      ) {
         if (!hash_equals($currentToken, $paymeId)) {
            throw new PaymeRpcException(
               -31008,
               $this->message(
                  'Этот счёт уже занят другой транзакцией Payme',
                  'Bu hisob boshqa Payme tranzaksiyasi bilan band',
                  'This billing is already claimed by another Payme transaction'
               )
            );
         }
         
         return;
      }
      
      $updated = Billing::updateAll([
         'payment_status' => Billing::STATUS_PENDING,
         'payment_transaction_id' => $paymeId,
         'payment_provider' => (int) $this->config(
            'providerCode',
            2
         ),
         'updated_at' => time(),
      ], [
         'and',
         ['id' => $billing->id],
         [
            'or',
            ['payment_status' => null],
            [
               'payment_status' => [
                  Billing::STATUS_FAILED,
                  Billing::STATUS_CANCELLED,
               ],
            ],
            [
               'and',
               ['payment_status' => Billing::STATUS_PENDING],
               [
                  'or',
                  ['payment_transaction_id' => null],
                  ['payment_transaction_id' => ''],
               ],
            ],
         ],
      ]);
      
      if ($updated !== 1) {
         $freshBilling = Billing::findOne($billing->id);
         
         if (
            $freshBilling === null
            || trim((string) $freshBilling->payment_transaction_id)
            !== $paymeId
         ) {
            throw new PaymeRpcException(
               -31008,
               $this->message(
                  'Не удалось закрепить счёт за транзакцией Payme',
                  'Hisobni Payme tranzaksiyasiga biriktirib bo‘lmadi',
                  'Unable to claim billing for Payme transaction'
               )
            );
         }
      }
      
      $billing->refresh();
   }
   
   private function assertSameTransaction(
      PaymeTransaction $transaction,
      ?Billing $billing,
      int $paymeTime,
      int $amount
   ): void {
      if (
         $billing === null
         || (string) $transaction->billing_id
         !== (string) $billing->id
         || (int) $transaction->amount !== $amount
         || (int) $transaction->payme_time !== $paymeTime
      ) {
         throw new PaymeRpcException(
            -31008,
            $this->message(
               'Параметры повторного запроса не совпадают с транзакцией',
               'Takroriy so‘rov parametrlari tranzaksiyaga mos emas',
               'Repeated request parameters do not match the transaction'
            )
         );
      }
   }
   
   private function assertStoredPaymeToken(
      ?Billing $billing,
      string $paymeId
   ): void {
      if ($billing === null) {
         throw new PaymeRpcException(
            -31003,
            $this->message(
               'Транзакция Payme не связана со счётом',
               'Payme tranzaksiyasi hisob bilan bog‘lanmagan',
               'Payme transaction is not linked to billing'
            )
         );
      }
      
      $storedToken = trim(
         (string) $billing->payment_transaction_id
      );
      
      if ($storedToken === '') {
         throw $this->missingStoredPaymeTokenError();
      }
      
      if (!hash_equals($storedToken, $paymeId)) {
         throw new PaymeRpcException(
            -31008,
            $this->message(
               'Токен Payme не совпадает с транзакцией счёта',
               'Payme tokeni hisob tranzaksiyasiga mos emas',
               'Payme token does not match the billing transaction'
            )
         );
      }
   }
   
   private function transactionNotFoundError(): PaymeRpcException
   {
      return new PaymeRpcException(
         -31003,
         $this->message(
            'Транзакция не найдена',
            'Tranzaksiya topilmadi',
            'Transaction not found'
         )
      );
   }
   
   private function missingStoredPaymeTokenError(): PaymeRpcException
   {
      return new PaymeRpcException(
         -32400,
         $this->message(
            'В Billing отсутствует идентификатор транзакции Payme',
            'Billing ichida Payme tranzaksiya identifikatori yo‘q',
            'Payme transaction identifier is missing in Billing'
         ),
         'payment_transaction_id'
      );
   }
   
   private function assertBillingCanBePaid(
      ?Billing $billing,
      int $amountTiyin
   ): void {
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
      
      $paymentStatus = $billing->payment_status === null
         ? null
         : (int) $billing->payment_status;
      
      $allowedStatuses = [
         null,
         Billing::STATUS_PENDING,
         Billing::STATUS_FAILED,
         Billing::STATUS_CANCELLED,
      ];
      
      if (!in_array(
         $paymentStatus,
         $allowedStatuses,
         true
      )) {
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
      
      return (bool) $this->config(
         'amountAlreadyInTiyin',
         false
      )
         ? $amount
         : $amount * 100;
   }
   
   private function billingTimestampMs(Billing $billing): int
   {
      $timestamp = (int) ($billing->updated_at
         ?: $billing->created_at
            ?: time());
      
      return $timestamp * 1000;
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
      
      if ($wasPerformed) {
         $this->deactivateUserSubscription(
            (string) $billing->payment_transaction_id
         );
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
            'Failed to save UserSubscriptions record.'
         );
      }
   }
   
   private function deactivateUserSubscription(
      string $paymeId
   ): void {
      if ($paymeId === '') {
         throw $this->missingStoredPaymeTokenError();
      }
      
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
   
   private function hasSubscriptionHistory(string $paymeId): bool
   {
      return UserSubscriptions::find()
         ->where(['payment_transaction_id' => $paymeId])
         ->exists();
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
   
   private function canCancelPerformedBilling(): bool
   {
      return (bool) $this->config(
         'allowCancelPerformed',
         true
      );
   }
   
   private function saveTransaction(
      PaymeTransaction $transaction
   ): void {
      if (!$transaction->save(false)) {
         throw new RuntimeException(
            'Failed to save PaymeTransaction.'
         );
      }
   }
   
   private function createResponse(
      PaymeTransaction $transaction
   ): array {
      return [
         'create_time' => (int) $transaction->create_time,
         'transaction' => (string) $transaction->id,
         'state' => (int) $transaction->state,
      ];
   }
   
   private function performResponse(
      PaymeTransaction $transaction
   ): array {
      return [
         'transaction' => (string) $transaction->id,
         'perform_time' => (int) $transaction->perform_time,
         'state' => (int) $transaction->state,
      ];
   }
   
   private function cancelResponse(
      PaymeTransaction $transaction
   ): array {
      return [
         'transaction' => (string) $transaction->id,
         'cancel_time' => (int) $transaction->cancel_time,
         'state' => (int) $transaction->state,
      ];
   }
   
   private function checkResponse(
      PaymeTransaction $transaction
   ): array {
      return [
         'create_time' => (int) $transaction->create_time,
         'perform_time' => (int) $transaction->perform_time,
         'cancel_time' => (int) $transaction->cancel_time,
         'transaction' => (string) $transaction->id,
         'state' => (int) $transaction->state,
         'reason' => $transaction->reason !== null
            ? (int) $transaction->reason
            : null,
      ];
   }
   
   private function statementResponse(
      PaymeTransaction $transaction
   ): array {
      return [
         'id' => $transaction->payme_id,
         'time' => (int) $transaction->payme_time,
         'amount' => (int) $transaction->amount,
         'account' => $transaction->getAccountData(),
         'create_time' => (int) $transaction->create_time,
         'perform_time' => (int) $transaction->perform_time,
         'cancel_time' => (int) $transaction->cancel_time,
         'transaction' => (string) $transaction->id,
         'state' => (int) $transaction->state,
         'reason' => $transaction->reason !== null
            ? (int) $transaction->reason
            : null,
      ];
   }
   
   private function extractBillingId(array $account): int|string
   {
      $billingId = $account['order_id'] ?? null;
      
      if (!is_int($billingId) && !is_string($billingId)) {
         throw $this->invalidBillingIdError();
      }
      
      if (is_string($billingId)) {
         $billingId = trim($billingId);
      }
      
      if (
         $billingId === ''
         || preg_match('/^[1-9]\d*$/', (string) $billingId) !== 1
      ) {
         throw $this->invalidBillingIdError();
      }
      
      return $billingId;
   }
   
   private function invalidBillingIdError(): PaymeRpcException
   {
      return new PaymeRpcException(
         -31050,
         $this->message(
            'Неверный ID счёта',
            'Hisob ID noto‘g‘ri',
            'Invalid billing ID'
         ),
         'billing_id'
      );
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
        $authorization = trim((string) Yii::$app->request
            ->headers
            ->get('Authorization', ''));
         $login = Yii::$app->params['payme']['login'];
         $pass = Yii::$app->params['payme']['key'];
        $key = (string) "$login:$pass";
        
        if ($authorization === '' || $key === '') {
            return false;
        }

        $expectedAuthorization = 'Basic ' . base64_encode($key);

        return hash_equals(
            $expectedAuthorization,
            $authorization
        );
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
         $log = new PaymeLog();
         $log->rpc_id = is_int($rpcId) ? $rpcId : null;
         $log->method = $method;
         $log->request_body = $requestBody;
         $log->response_body = json_encode(
            $response,
            JSON_UNESCAPED_UNICODE
            | JSON_UNESCAPED_SLASHES
            | JSON_THROW_ON_ERROR
         );
         $log->authorization_ok = $authorized ? 1 : 0;
         $log->ip = Yii::$app->request->userIP;
         $log->duration_ms = $durationMs;
         $log->created_at = time();
         
         if (!$log->save(false)) {
            throw new RuntimeException(
               'Failed to save PaymeLog.'
            );
         }
      } catch (Throwable $e) {
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
