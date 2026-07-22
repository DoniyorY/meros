<?php

namespace backend\controllers;

use common\models\Events;
use common\models\UploadsImage;
use common\models\search\EventsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use Yii;

/**
 * EventsController implements the CRUD actions for Events model.
 */
class EventsController extends BaseController
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
                  'status' => ['POST'],
               ],
            ],
         ]
      );
   }
   
   /**
    * Lists all Events models.
    *
    * @return string
    */
   public function actionIndex()
   {
      $searchModel = new EventsSearch();
      $dataProvider = $searchModel->search($this->request->queryParams);
      
      return $this->render('index', [
         'searchModel' => $searchModel,
         'dataProvider' => $dataProvider,
      ]);
   }
   
   /**
    * Displays a single Events model.
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
    * Creates a new Events model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    * @return string|\yii\web\Response
    */
   public function actionCreate()
   {
      $model = new Events([
         'created_at' => time(),
         'updated_at' => time(),
         'status' => 1,
         'user_id' => Yii::$app->user->id,
      ]);
      
      if ($this->request->isPost && $model->load($this->request->post())) {
         $file = UploadedFile::getInstance($model, 'imageFile');
         if ($file) {
            $model->image = UploadsImage::uploadImage($model, $file, 'events');
         }
         
         if ($model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
         }
      } else {
         $model->loadDefaultValues();
      }
      
      return $this->render('create', [
         'model' => $model,
      ]);
   }
   
   /**
    * Updates an existing Events model.
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
         $model->user_id = Yii::$app->user->id;
         $oldImage = $model->image;
         $file = UploadedFile::getInstance($model, 'imageFile');
         
         if ($file) {
            $model->image = UploadsImage::uploadImage($model, $file, 'events');
            $oldImagePath = Yii::getAlias('@frontend/web/uploads/events/' . $oldImage);
            if ($oldImage && file_exists($oldImagePath)) {
               unlink($oldImagePath);
            }
         }
         
         if ($model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
         }
      }
      
      return $this->render('update', [
         'model' => $model,
      ]);
   }
   
   /**
    * Deletes an existing Events model.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    * @param int $id ID
    * @return \yii\web\Response
    * @throws NotFoundHttpException if the model cannot be found
    */
   public function actionDelete($id)
   {
      $model = $this->findModel($id);
      $imagePath = Yii::getAlias('@frontend/web/uploads/events/' . $model->image);
      if ($model->image && file_exists($imagePath)) {
         unlink($imagePath);
      }
      $model->delete();
      
      return $this->redirect(['index']);
   }
   
   public function actionStatus($id, $status)
   {
      $model = $this->findModel($id);
      $model->status = $status;
      $model->updated_at = time();
      $model->save(false, ['status', 'updated_at']);
      Yii::$app->session->setFlash('success', 'Status Changed Successfully');
      
      return $this->redirect(Yii::$app->request->referrer ?: ['index']);
   }
   
   /**
    * Finds the Events model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    * @param int $id ID
    * @return Events the loaded model
    * @throws NotFoundHttpException if the model cannot be found
    */
   protected function findModel($id)
   {
      if (($model = Events::findOne(['id' => $id])) !== null) {
         return $model;
      }
      
      throw new NotFoundHttpException('The requested page does not exist.');
   }
}
