<?php

namespace common\services;

use common\models\Billing;
use common\models\Promocodes;
use DomainException;
use LogicException;
use RuntimeException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

final class PromocodeService
{
    public static function apply(int $billingId, int $userId, string $code): Billing
    {
        return Billing::getDb()->transaction(function () use ($billingId, $userId, $code) {
            $billing = self::findForUpdate(Billing::find()->where([
                'id' => $billingId,
                'user_id' => $userId,
            ]));

            if ($billing === null) {
                throw new NotFoundHttpException('Счёт не найден.');
            }

            self::checkBilling($billing);

            $originalAmount = (int) ($billing->no_discount_amount ?? $billing->amount);
            $code = strtoupper(trim($code));

            if ($code === '') {
                $billing->promocode_id = null;
                $billing->amount = $originalAmount;
            } else {
                $promocode = self::findAvailablePromocode($code, $billingId);

                $billing->amount = self::calculateAmount($originalAmount, (int) $promocode->discount);
                $billing->no_discount_amount = $originalAmount;
                $billing->promocode_id = $promocode->id;
            }

            $billing->updated_at = time();

            if (!$billing->save(false, ['amount', 'no_discount_amount', 'promocode_id', 'updated_at'])) {
                throw new RuntimeException('Не удалось сохранить скидку в счёте.');
            }

            return $billing;
        });
    }

    private static function checkBilling(Billing $billing): void
    {
        $paid = (int) $billing->status === Billing::STATUS_SUCCESS
            || (int) $billing->payment_status === Billing::STATUS_SUCCESS;
        $paymentStarted = trim((string) $billing->payment_transaction_id) !== '';

        if ($paid || $paymentStarted) {
            throw new DomainException('После начала оплаты промокод изменить нельзя.');
        }

        if ((int) ($billing->no_discount_amount ?? $billing->amount) <= 0) {
            throw new DomainException('Некорректная сумма счёта.');
        }
    }

    private static function findAvailablePromocode(string $code, int $billingId): Promocodes
    {
        $promocode = self::findForUpdate(Promocodes::find()->where(['code' => $code]));

        if ($promocode === null || (int) $promocode->status !== Promocodes::STATUS_PENDING) {
            throw new DomainException('Промокод не найден, уже использован или просрочен.');
        }

        $otherBilling = self::findForUpdate(Billing::find()
            ->where(['promocode_id' => $promocode->id])
            ->andWhere(['<>', 'id', $billingId]));

        if ($otherBilling !== null) {
            throw new DomainException('Промокод уже применён к другому счёту.');
        }

        return $promocode;
    }

    private static function calculateAmount(int $originalAmount, int $discount): int
    {
        if ($discount < 1 || $discount > 99) {
            throw new DomainException('У промокода некорректная скидка.');
        }

        $amount = (int) round($originalAmount * (100 - $discount) / 100);

        if ($amount <= 0) {
            throw new DomainException('Этот промокод обнуляет сумму. Оплата через Click и Payme недоступна.');
        }

        return $amount;
    }

    public static function lockBilling(int $id): void
    {
        self::findForUpdate(Billing::find()->where(['id' => $id]));
    }

    // Вызывается внутри транзакции успешной оплаты Click или Payme.
    public static function consume(Billing $billing): void
    {
        if (!$billing->promocode_id) {
            return;
        }

        $transaction = Billing::getDb()->getTransaction();
        if ($transaction === null || !$transaction->isActive) {
            throw new LogicException('Промокод должен использоваться в транзакции оплаты.');
        }

        $promocode = self::findForUpdate(Promocodes::find()->where(['id' => $billing->promocode_id]));

        if ($promocode === null || !$billing->user_id) {
            throw new RuntimeException('В оплаченном счёте отсутствует промокод или пользователь.');
        }

        $alreadyUsed = (int) $promocode->status === Promocodes::STATUS_USED;
        $sameUser = (int) $promocode->user_id === (int) $billing->user_id;

        if ($alreadyUsed && $sameUser) {
            return;
        }

        if ((int) $promocode->status !== Promocodes::STATUS_PENDING) {
            throw new RuntimeException('Промокод счёта больше недоступен.');
        }

        $promocode->status = Promocodes::STATUS_USED;
        $promocode->user_id = $billing->user_id;

        if (!$promocode->save(false, ['status', 'user_id', 'updated_at'])) {
            throw new RuntimeException('Не удалось отметить промокод использованным.');
        }
    }

    private static function findForUpdate(ActiveQuery $query): ?ActiveRecord
    {
        // В этой версии Yii нет forUpdate(). Блокировка действует до конца транзакции
        // и не даёт параллельным запросам применить один промокод к разным счетам.
        $command = $query->limit(1)->createCommand();
        $modelClass = $query->modelClass;

        return $modelClass::findBySql($command->sql . ' FOR UPDATE', $command->params)->one();
    }
}
