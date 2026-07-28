<?php

namespace backend\controllers;

use common\models\Billing;
use common\models\UserSubscriptions;
use common\services\UserLoginSessionService;
use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
   public function beforeAction($action): bool
   {
      if (!parent::beforeAction($action)) {
         return false;
      }
      
      if (!UserLoginSessionService::validateCurrent()) {
         Yii::$app->user->logout();
         
         Yii::$app->session->setFlash(
            'warning',
            'This Session was Expired'
         );
         
         Yii::$app->response->redirect(['/site/login']);
         
         return false;
      }
      
      return true;
   }
   protected function checkAllSubs()
   {
      
      $user_subs = UserSubscriptions::findAll(['status'=>1]);
      foreach ($user_subs as $item) {
         if ($item->expires_date <= time()){
            $item->status = 0;
            $item->save(false);
         }
      }
   }
   protected function dropTrashBilling()
   {
      
      $billing = Billing::find()
         ->where(['start_date'=>null])
         ->andWhere(['expires_date'=>null])
         ->andWhere(['status'=>0])
         ->andWhere(['payment_transaction_id'=>null])
         ->all();
      foreach ($billing as $item) {
         $item->delete();
      }
   }
}