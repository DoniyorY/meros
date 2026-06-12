<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;

class ApiController extends Controller
{
   public $enableCsrfValidation = false;
   public function actionPayme()
   {
      file_put_contents(
         Yii::getAlias('@runtime/logs/payme.log'),
         date('Y-m-d H:i:s') . PHP_EOL .
         Yii::$app->request->rawBody . PHP_EOL .
         str_repeat('-', 50) . PHP_EOL,
         FILE_APPEND
      );
      
      return json_encode([
         'success' => true,
      ]);
   }
}