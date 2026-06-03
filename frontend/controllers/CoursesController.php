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
      $subs = SubscriptionPlans::find()->all();
      return $this->render('no_subs', [
         'courses' => $courses,
         'subs'=> $subs
      ]);
   }
   
}