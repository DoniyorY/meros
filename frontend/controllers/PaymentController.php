<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\Billing;
use common\models\ClickPayment;
use common\models\ClickWebhookLog;
use common\models\UserSubscriptions;
use RuntimeException;
use Throwable;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use frontend\controllers\traits\PaymePaymentActions;
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
     * Один URL можно указать в кабинете CLICK одновременно как Prepare URL и Complete URL.
     * action=0 -> Prepare, action=1 -> Complete.
     */
    public function actionClickWebhook(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->statusCode = 200;

        $data = Yii::$app->request->post();

        $log = new ClickWebhookLog();
        $log->click_trans_id = isset($data['click_trans_id']) ? (string)$data['click_trans_id'] : null;
        $log->merchant_trans_id = isset($data['merchant_trans_id']) ? (string)$data['merchant_trans_id'] : null;
        $log->action = isset($data['action']) ? (int)$data['action'] : null;
        $log->request_payload = Json::encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $log->remote_ip = Yii::$app->request->userIP;
        $log->created_at = time();

        if (!$log->save(false)) {
            Yii::error(['message' => 'CLICK webhook log was not saved', 'payload' => $data], 'click');
        }

        try {
            $response = $this->processClickRequest($data, $log);
        } catch (Throwable $e) {
            Yii::error([
                'message' => $e->getMessage(),
                'exception' => $e,
                'payload' => $data,
            ], 'click');

            $response = $this->clickResponse($data, -7, 'Failed to update order');
        }

        if (!$log->isNewRecord) {
            $log->response_error = isset($response['error']) ? (int)$response['error'] : -7;
            $log->response_payload = Json::encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $log->processed_at = time();
            $log->save(false);
        }

        return $response;
    }

    /**
     * Переход пользователя на страницу оплаты CLICK.
     */
    public function actionClickPay(int $id): Response
    {
        $subscription = Billing::findOne([
            'id' => $id,
            'user_id' => Yii::$app->user->id,
        ]);

        if ($subscription === null) {
            throw new \yii\web\NotFoundHttpException('Subscription not found.');
        }

        if ((int)$subscription->status === Billing::STATUS_SUCCESS) {
            return $this->redirect(['/profile/subscriptions']);
        }

        $config = $this->clickConfig();
        $returnUrl = Url::to(['/payment/click-return', 'id' => $subscription->id], true);

        $query = http_build_query([
            'service_id' => $config['service_id'],
            'merchant_id' => $config['merchant_id'],
            'amount' => number_format((float)$subscription->amount, 2, '.', ''),
            'transaction_param' => (string)$subscription->id,
            'return_url' => $returnUrl,
        ], '', '&', PHP_QUERY_RFC3986);

        return $this->redirect('https://my.click.uz/services/pay?' . $query);
    }

    /**
     * Возврат браузера пользователя. Здесь нельзя подтверждать оплату — только показывать статус из БД.
     */
    public function actionClickReturn(int $id): string
    {
        $subscription = UserSubscriptions::findOne([
            'id' => $id,
            'user_id' => Yii::$app->user->id,
        ]);

        if ($subscription === null) {
            throw new \yii\web\NotFoundHttpException('Subscription not found.');
        }

        return $this->render('click-return', [
            'subscription' => $subscription,
        ]);
    }

    private function processClickRequest(array $data, ClickWebhookLog $log): array
    {
        $validationError = $this->validateClickRequest($data);
        if ($validationError !== null) {
            return $validationError;
        }

        return (int)$data['action'] === 0
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
            if (!array_key_exists($key, $data) || $data[$key] === '') {
                return $this->clickResponse($data, -8, 'Error in request from click');
            }
        }

        $action = (int)$data['action'];
        if (!in_array($action, [0, 1], true)) {
            return $this->clickResponse($data, -3, 'Action not found');
        }

        if ($action === 1 && (!array_key_exists('merchant_prepare_id', $data) || $data['merchant_prepare_id'] === '')) {
            return $this->clickResponse($data, -8, 'Error in request from click');
        }

        $config = $this->clickConfig();

        if ((string)$data['service_id'] !== (string)$config['service_id']) {
            return $this->clickResponse($data, -8, 'Incorrect service_id');
        }

        $expectedSign = md5(
            (string)$data['click_trans_id'] .
            (string)$data['service_id'] .
            (string)$config['secret_key'] .
            (string)$data['merchant_trans_id'] .
            ($action === 1 ? (string)$data['merchant_prepare_id'] : '') .
            (string)$data['amount'] .
            (string)$data['action'] .
            (string)$data['sign_time']
        );

        $providedSign = strtolower(trim((string)$data['sign_string']));

        if (!hash_equals($expectedSign, $providedSign)) {
            return $this->clickResponse($data, -1, 'SIGN CHECK FAILED!');
        }

        return null;
    }

    private function prepareClickPayment(array $data, ClickWebhookLog $log): array
    {
        $subscription = $this->findSubscription((string)$data['merchant_trans_id']);

        if ($subscription === null) {
            return $this->clickResponse($data, -5, 'User does not exist');
        }

        if (!$this->amountsEqual($subscription->amount, $data['amount'])) {
            return $this->clickResponse($data, -2, 'Incorrect parameter amount');
        }

        if ((int)$subscription->status === UserSubscriptions::STATUS_ACTIVE) {
            return $this->clickResponse($data, -4, 'Already paid');
        }

        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();

        try {
            $this->lockSubscription((int)$subscription->id);
            $subscription->refresh();

            if ((int)$subscription->status === UserSubscriptions::STATUS_ACTIVE) {
                $transaction->rollBack();
                return $this->clickResponse($data, -4, 'Already paid');
            }

            $payment = ClickPayment::findOne([
                'click_trans_id' => (string)$data['click_trans_id'],
            ]);

            if ($payment !== null) {
                if (
                    (string)$payment->merchant_trans_id !== (string)$subscription->id ||
                    !$this->amountsEqual($payment->amount, $data['amount'])
                ) {
                    $transaction->rollBack();
                    return $this->clickResponse($data, -6, 'Transaction does not exist');
                }

                if ((int)$payment->status === ClickPayment::STATUS_PAID) {
                    $transaction->rollBack();
                    return $this->clickResponse($data, -4, 'Already paid');
                }

                if (in_array((int)$payment->status, [ClickPayment::STATUS_CANCELLED, ClickPayment::STATUS_FAILED], true)) {
                    $transaction->rollBack();
                    return $this->clickResponse($data, -9, 'Transaction cancelled');
                }
            } else {
                $payment = new ClickPayment();
                $payment->subscription_id = (int)$subscription->id;
                $payment->click_trans_id = (string)$data['click_trans_id'];
                $payment->click_paydoc_id = (string)$data['click_paydoc_id'];
                $payment->service_id = (int)$data['service_id'];
                $payment->merchant_trans_id = (string)$data['merchant_trans_id'];
                $payment->amount = number_format((float)$data['amount'], 2, '.', '');
                $payment->status = ClickPayment::STATUS_PREPARED;
                $payment->click_error = (int)$data['error'];
                $payment->click_error_note = (string)$data['error_note'];
                $payment->sign_time = (string)$data['sign_time'];
                $payment->prepared_at = time();
                $payment->created_at = time();
                $payment->updated_at = time();

                if (!$payment->save(false)) {
                    throw new RuntimeException('CLICK payment prepare record was not saved.');
                }
            }

            if (!$log->isNewRecord) {
                $log->click_payment_id = (int)$payment->id;
                $log->save(false);
            }

            $transaction->commit();

            return $this->clickResponse($data, 0, 'Success', [
                'merchant_prepare_id' => (int)$payment->id,
            ]);
        } catch (Throwable $e) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }
            throw $e;
        }
    }

    private function completeClickPayment(array $data, ClickWebhookLog $log): array
    {
        $payment = ClickPayment::findOne((int)$data['merchant_prepare_id']);

        if ($payment === null) {
            return $this->clickResponse($data, -6, 'Transaction does not exist');
        }

        if (
            (string)$payment->click_trans_id !== (string)$data['click_trans_id'] ||
            (string)$payment->merchant_trans_id !== (string)$data['merchant_trans_id'] ||
            (string)$payment->service_id !== (string)$data['service_id']
        ) {
            return $this->clickResponse($data, -6, 'Transaction does not exist');
        }

        if (!$this->amountsEqual($payment->amount, $data['amount'])) {
            return $this->clickResponse($data, -2, 'Incorrect parameter amount');
        }

        $subscription = $this->findSubscription((string)$data['merchant_trans_id']);
        if ($subscription === null) {
            return $this->clickResponse($data, -5, 'User does not exist');
        }

        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();

        try {
            $this->lockPayment((int)$payment->id);
            $this->lockSubscription((int)$subscription->id);

            $payment->refresh();
            $subscription->refresh();

            if (!$log->isNewRecord) {
                $log->click_payment_id = (int)$payment->id;
                $log->save(false);
            }

            if ((int)$data['error'] < 0) {
                if ((int)$payment->status !== ClickPayment::STATUS_PAID) {
                    $payment->status = ClickPayment::STATUS_CANCELLED;
                    $payment->click_error = (int)$data['error'];
                    $payment->click_error_note = (string)$data['error_note'];
                    $payment->cancelled_at = time();
                    $payment->updated_at = time();
                    $payment->save(false);
                }

                $transaction->commit();

                return $this->clickResponse($data, -9, 'Transaction cancelled', [
                    'merchant_confirm_id' => (int)$payment->id,
                ]);
            }

            if (
                (int)$payment->status === ClickPayment::STATUS_PAID ||
                (int)$subscription->status === UserSubscriptions::STATUS_ACTIVE
            ) {
                $transaction->rollBack();
                return $this->clickResponse($data, -4, 'Already paid', [
                    'merchant_confirm_id' => (int)$payment->id,
                ]);
            }

            if ((int)$payment->status !== ClickPayment::STATUS_PREPARED) {
                $transaction->rollBack();
                return $this->clickResponse($data, -6, 'Transaction does not exist');
            }

            $payment->status = ClickPayment::STATUS_PAID;
            $payment->click_error = (int)$data['error'];
            $payment->click_error_note = (string)$data['error_note'];
            $payment->paid_at = time();
            $payment->updated_at = time();

            if (!$payment->save(false)) {
                throw new RuntimeException('CLICK payment complete record was not saved.');
            }

            // Активацию подписки делаем только здесь, после валидного Complete.
            $subscription->status = UserSubscriptions::STATUS_ACTIVE;
            $subscription->transaction_id = (string)$data['click_trans_id'];

            if (empty($subscription->start_date)) {
                $subscription->start_date = time();
            }

            // expires_date рассчитай здесь по длительности выбранного плана.

            if (!$subscription->save(false)) {
                throw new RuntimeException('Subscription was not activated.');
            }

            $transaction->commit();

            $billing = Billing::findOne((int)$data['merchant_trans_id']);
            if ($billing !== null) {
                ApiController::sendZapierOrderPaidWebhook($billing);
            }

            return $this->clickResponse($data, 0, 'Success', [
                'merchant_confirm_id' => (int)$payment->id,
            ]);
        } catch (Throwable $e) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }
            throw $e;
        }
    }

    private function findSubscription(string $merchantTransId): ?UserSubscriptions
    {
        if (!ctype_digit($merchantTransId)) {
            return null;
        }

        return UserSubscriptions::findOne((int)$merchantTransId);
    }

    private function lockSubscription(int $id): void
    {
        Yii::$app->db->createCommand(
            'SELECT [[id]] FROM {{%user_subscriptions}} WHERE [[id]] = :id FOR UPDATE',
            [':id' => $id]
        )->queryScalar();
    }

    private function lockPayment(int $id): void
    {
        Yii::$app->db->createCommand(
            'SELECT [[id]] FROM {{%click_payments}} WHERE [[id]] = :id FOR UPDATE',
            [':id' => $id]
        )->queryScalar();
    }

    private function amountsEqual($left, $right): bool
    {
        return (int)round((float)$left * 100) === (int)round((float)$right * 100);
    }

    private function clickResponse(array $request, int $error, string $note, array $extra = []): array
    {
        $response = [
            'error' => $error,
            'error_note' => $note,
        ];

        if (array_key_exists('click_trans_id', $request)) {
            $response['click_trans_id'] = $request['click_trans_id'];
        }

        if (array_key_exists('merchant_trans_id', $request)) {
            $response['merchant_trans_id'] = $request['merchant_trans_id'];
        }

        return array_merge($response, $extra);
    }

    private function clickConfig(): array
    {
        $config = Yii::$app->params['click'] ?? [];

        foreach (['merchant_id', 'service_id', 'secret_key'] as $key) {
            if (!isset($config[$key]) || $config[$key] === '') {
                throw new RuntimeException("CLICK config parameter '{$key}' is not set.");
            }
        }

        return $config;
    }
}
