<?php

namespace backend\controllers;

use common\models\CourseLessons;
use common\models\Courses;
use common\models\search\CoursesSearch;
use common\models\UploadsImage;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CoursesController implements the CRUD actions for Courses model.
 */
class CoursesController extends Controller
{
   /**
    * @inheritDoc
    */
   public function behaviors()
   {
      return array_merge(
         parent::behaviors(),
         [
            'verbs' => [
               'class' => VerbFilter::className(),
               'actions' => [
                  'delete' => ['POST'],
                  'status' => ['post'],
                  'add-lesson' => ['post'],
               ],
            ],
         ]
      );
   }
   
   /**
    * Lists all Courses models.
    *
    * @return string
    */
   public function actionIndex()
   {
      $searchModel = new CoursesSearch();
      $dataProvider = $searchModel->search($this->request->queryParams);
      
      return $this->render('index', [
         'searchModel' => $searchModel,
         'dataProvider' => $dataProvider,
      ]);
   }
   
   public function actionStatus($id, $status)
   {
      $model = $this->findModel($id);
      $model->status = $status;
      $model->updated_at = time();
      $model->save();
      \Yii::$app->session->setFlash('success', 'Status Changed Successfully');
      return $this->redirect(\Yii::$app->request->referrer);
   }
   
   /**
    * Displays a single Courses model.
    * @param int $id ID
    * @return string
    * @throws NotFoundHttpException if the model cannot be found
    */
   public function actionView($id)
   {
      return $this->render('view', [
         'model' => $this->findModel($id),
      ]);
   }
   
   /**
    * Creates a new Courses model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    * @return string|\yii\web\Response
    */
   public function actionCreate()
   {
      $model = new Courses();
      
      if ($this->request->isPost) {
         if ($model->load($this->request->post())) {
            $model->created_at = time();
            $model->updated_at = time();
            $model->status = 0;
            $model->user_id = \Yii::$app->user->id;
            
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
         }
      } else {
         $model->loadDefaultValues();
      }
      
      return $this->render('create', [
         'model' => $model,
      ]);
   }
   
   public function actionAddLesson($course_id)
   {
      $lesson = new CourseLessons([
         'course_id' => $course_id,
         'created_at' => time(),
         'updated_at' => time(),
         'user_id' => \Yii::$app->user->id,
         'status' => 1
      ]);
      if ($this->request->isPost && $lesson->load($this->request->post())) {
         $file = UploadedFile::getInstance($lesson, 'video');
         if ($file) {
            $lesson->video = $file;
            $uploaded = $lesson->uploadVideo();
            if ($uploaded == false) {
               throw new HttpException(500, 'Failed to upload video');
            }
            $lesson->video_link = $uploaded;
         }
         \Yii::$app->session->setFlash('success', 'Lesson Added Successfully');
         $lesson->save(false);
      }
      return $this->redirect(\Yii::$app->request->referrer);
   }
   
   /**
    * Updates an existing Courses model.
    * If update is successful, the browser will be redirected to the 'view' page.
    * @param int $id ID
    * @return string|\yii\web\Response
    * @throws NotFoundHttpException if the model cannot be found
    */
   public function actionUpdate($id)
   {
      $model = $this->findModel($id);
      
      if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
         return $this->redirect(['view', 'id' => $model->id]);
      }
      
      return $this->render('update', [
         'model' => $model,
      ]);
   }
   
   /**
    * Deletes an existing Courses model.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    * @param int $id ID
    * @return \yii\web\Response
    * @throws NotFoundHttpException if the model cannot be found
    */
   public function actionDelete($id)
   {
      $this->findModel($id)->delete();
      
      return $this->redirect(['index']);
   }
   
   /**
    * Finds the Courses model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    * @param int $id ID
    * @return Courses the loaded model
    * @throws NotFoundHttpException if the model cannot be found
    */
   protected function findModel($id)
   {
      if (($model = Courses::findOne(['id' => $id])) !== null) {
         return $model;
      }
      
      throw new NotFoundHttpException('The requested page does not exist.');
   }
}
