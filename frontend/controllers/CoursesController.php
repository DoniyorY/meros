<?php

namespace frontend\controllers;

use common\models\Courses;
use common\models\SubscriptionPlans;
use yii\web\Controller;

class CoursesController extends Controller
{
   
   public function actionIndex($slug)
   {
      $courses = Courses::findOne(['slug' => $slug]);
      $subs = SubscriptionPlans::findAll(['status' => 1]);
      return $this->render('no_subs', [
         'courses' => $courses,
         'subs' => $subs
      ]);
   }
   
   public function actionGetPlan($id)
   {
      
      $subs = SubscriptionPlans::findOne(['id' => $id]);
      return $this->render('invoice', ['model' => $subs]);
   }
}