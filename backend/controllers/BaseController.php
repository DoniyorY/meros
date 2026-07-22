<?php

namespace backend\controllers;

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
}