<?php

namespace backend\controllers;

use common\models\search\UserSubscriptionsSearch;
use Yii;

class UserSubscriptionsController extends BaseController
{
   public function actionIndex()
   {
      $searchModel = new UserSubscriptionsSearch();
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
      return $this->render('index', [
         'searchModel' => $searchModel,
         'dataProvider' => $dataProvider,
      ]);
   }
}