<?php

namespace frontend\controllers;

use common\models\Courses;
use common\models\SubscriptionPlans;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CoursesController extends Controller
{
   
   public function actionIndex($slug, $category = null)
   {
      $query = Courses::find()
         ->alias('course')
         ->joinWith('category courseCategory')
         ->andWhere(['course.slug' => $slug, 'course.status' => Courses::STATUS_ACTIVE]);

      if ($category !== null) {
         $query->andWhere(['courseCategory.slug' => $category, 'courseCategory.status' => 1]);
      }

      $courses = $query->one();

      if ($courses === null) {
         throw new NotFoundHttpException('The requested course does not exist.');
      }

      $subs = SubscriptionPlans::findAll(['status' => 1]);
      return $this->render('no_subs', [
         'courses' => $courses,
         'subs' => $subs
      ]);
   }
   
}