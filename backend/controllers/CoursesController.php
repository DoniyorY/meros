<?php

namespace backend\controllers;

use common\models\CourseFeatures;
use common\models\CourseLessons;
use common\models\Courses;
use common\models\search\CoursesSearch;
use common\models\UploadsImage;
use Yii;
use yii\helpers\Url;
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
                  'add-lesson' => ['post'],
                  'delete-video' => ['post'],
                  'update-lesson' => ['post']
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
   
   public function actionUpdateLessonStatus($id, $status)
   {
      $lesson = CourseLessons::findOne($id);
      $lesson->status = $status;
      $lesson->updated_at = time();
      $lesson->save(false);
      \Yii::$app->session->setFlash('success', 'Lesson Status Changed Successfully!!!');
      return $this->redirect(\Yii::$app->request->referrer);
   }
   
   public function actionUpdateLessonModal($id)
   {
      $model = CourseLessons::findOne($id);
      
      return $this->renderAjax('_form_lessons', [
         'model' => $model,
         'url' => Url::to(['update-lesson', 'id' => $model->id]),
         'course_id' => $model->course_id
      ]);
   }
   
   public function actionUpdateLesson($id)
   {
      $lesson = CourseLessons::findOne($id);
      if ($this->request->isPost && $lesson->load($this->request->post())) {
         $lesson->updated_at = time();
         $file = UploadedFile::getInstance($lesson, 'video');
         if ($file) {
            $old_video = Yii::getAlias('@frontend/web/uploads/lessons/') . $lesson->video_link;
            $lesson->video = $file;
            $uploaded = $lesson->uploadVideo();
            if ($uploaded == false) {
               throw new HttpException(500, 'Failed to upload video');
            }
            $lesson->video_link = $uploaded;
            
            if (file_exists($old_video)) {
               unlink($old_video);
            }
         }
         $lesson->save(false);
         return $this->redirect(Yii::$app->request->referrer);
      }
   }
   
   public function actionShowVideo($id)
   {
      $model = CourseLessons::findOne($id);
      return $this->renderAjax('_video', [
         'model' => $model,
      ]);
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
            $file = UploadedFile::getInstance($model, 'imageFile');
            if ($file) {
               $model->imageFile = $file;
               $uploaded = $model->uploadImage();
               
               if ($uploaded === false) {
                  throw new HttpException(500, 'Failed to upload image');
               }
               
               $model->image = $uploaded;
            }
            $file = UploadedFile::getInstance($model, 'syllabus');
            if ($file) {
               $model->syllabus = $file;
               $name = "syllabus_". date('d.m.Y.H.i.s').".".$file->extension;
               $path = Yii::getAlias('@frontend/web/uploads/course_docs/');
               $file->saveAs($path . $name);
               $model->syllabus_file = $name;
            }
            $file = UploadedFile::getInstance($model, 'flyer');
            if ($file) {
               $model->flyer = $file;
               $name = "flyer_".date('d.m.Y.H.i.s').".".$file->extension;
               $path = Yii::getAlias('@frontend/web/uploads/course_docs/');
               $file->saveAs($path . $name);
               $model->flyer_file = $name;
            }
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
   
   public function actionAddFeature($course_id)
   {
      $model = new CourseFeatures(['course_id' => $course_id]);
      if ($model->load(Yii::$app->request->post())) {
         
         $model->save();
         return $this->redirect(Yii::$app->request->referrer);
      }
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
      
      if ($this->request->isPost && $model->load($this->request->post())) {
         $model->updated_at = time();
         $oldImage = $model->image;
         $file = UploadedFile::getInstance($model, 'imageFile');
         
         if ($file) {
            $model->imageFile = $file;
            $uploaded = $model->uploadImage();
            
            if ($uploaded === false) {
               throw new HttpException(500, 'Failed to upload image');
            }
            
            $model->image = $uploaded;
            $oldImagePath = Yii::getAlias('@frontend/web/uploads/courses/' . $oldImage);
            
            if ($oldImage && file_exists($oldImagePath)) {
               unlink($oldImagePath);
            }
         }
         $file = UploadedFile::getInstance($model, 'syllabus');
         if ($file) {
            $oldFile = $model->syllabus_file;
            $model->syllabus = $file;
            $name = "syllabus_". date('d.m.Y.H.i.s').".".$file->extension;
            $path = Yii::getAlias('@frontend/web/uploads/course_docs/');
            $file->saveAs($path . $name);
            $model->syllabus_file = $name;
            if ($oldFile && file_exists($oldFile)) {
               $oldPath = Yii::getAlias('@frontend/web/uploads/course_docs/' . $oldFile);
               unlink($oldFile);
            }
         }
         $file = UploadedFile::getInstance($model, 'flyer');
         if ($file) {
            $oldFile = $model->flyer_file;
            $model->flyer = $file;
            $name = "flyer_".date('d.m.Y.H.i.s').".".$file->extension;
            $path = Yii::getAlias('@frontend/web/uploads/course_docs/');
            $file->saveAs($path . $name);
            $model->flyer_file = $name;
            if ($oldFile && file_exists($oldFile)) {
               $oldPath = Yii::getAlias('@frontend/web/uploads/course_docs/' . $oldFile);
               unlink($oldFile);
            }
         }
         
         if ($model->save(false)) {
            return $this->redirect(['view', 'id' => $model->id]);
         }
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
   
   public function actionDeleteVideo($id)
   {
      $model = CourseLessons::findOne(['id' => $id]);
      $model->delete();
      \Yii::$app->session->setFlash('success', 'Video Deleted Successfully');
      return $this->redirect(\Yii::$app->request->referrer);
   }
   
   public function actionDeleteFeature($id)
   {
      $model = CourseFeatures::findOne(['id' => $id]);
      $model->delete();
      \Yii::$app->session->setFlash('success', 'Feature Deleted Successfully');
      return $this->redirect(\Yii::$app->request->referrer);
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
