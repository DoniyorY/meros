<?php

namespace frontend\controllers;

use common\models\CourseCategory;
use common\models\Courses;
use yii\web\Controller;

class CoursesController extends Controller
{
   
   public function actionIndex($slug)
   {
      $courses = Courses::findOne(['slug' => $slug]);
      return $this->render('no_subs', [
         'courses' => $courses,
      ]);
   }
   
}