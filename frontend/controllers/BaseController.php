<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
   public function beforeAction($action)
   {
      if (!parent::beforeAction($action)) {
         return false;
      }
      
      Yii::$app->visitTracker->track();
      
      return true;
   }
}