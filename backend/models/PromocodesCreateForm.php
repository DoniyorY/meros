<?php

namespace backend\models;

use common\models\Promocodes;
use Yii;
use yii\base\Model;

class PromocodesCreateForm extends Model
{
   public const MAX_QUANTITY = 1000;
   
   public $quantity = 1;
   public $discount;
   
   public function rules()
   {
      return [
         [['quantity', 'discount'], 'required'],
         ['quantity', 'integer', 'min' => 1, 'max' => self::MAX_QUANTITY],
         ['discount', 'integer', 'min' => 1, 'max' => 100],
      ];
   }
   
   public function attributeLabels()
   {
      return ['quantity' => 'Количество', 'discount' => 'Скидка, %'];
   }
   
   public function create(): bool
   {
      if (!$this->validate()) {
         return false;
      }
      
      $transaction = null;
      try {
         $transaction = Promocodes::getDb()->beginTransaction();
         for ($i = 0; $i < (int)$this->quantity; $i++) {
            $model = new Promocodes();
            do {
               $c1 = Yii::$app->security->generateRandomString(4);
               $c2 = Yii::$app->security->generateRandomString(4);
               $code = "$c1-$c2";
            } while (Promocodes::find()->where(['code' => $code])->exists());
            
            $model->code = $code;
            $model->discount = (int)$this->discount;
            $model->status = Promocodes::STATUS_PENDING;
            if (!$model->save()) {
               throw new \RuntimeException('Promocode validation failed: ' . json_encode($model->getErrors()));
            }
         }
         $transaction->commit();
         return true;
      } catch (\Throwable $exception) {
         if ($transaction !== null && $transaction->isActive) {
            $transaction->rollBack();
         }
         Yii::error($exception, __METHOD__);
         $this->addError('quantity', 'Не удалось создать промокоды. Партия не сохранена. Попробуйте ещё раз.');
         return false;
      }
   }
}
