<?php

namespace backend\controllers;

use common\models\AuthAssignment;
use common\models\UploadsImage;
use common\models\User;
use common\models\search\UserSearch;
use common\models\UserSubscriptions;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
               ],
            ],
         ]
      );
   }
   
   /**
    * Lists all User models.
    *
    * @return string
    */
   public function actionIndex()
   {
      $searchModel = new UserSearch();
      $dataProvider = $searchModel->search($this->request->queryParams);
      
      return $this->render('index', [
         'searchModel' => $searchModel,
         'dataProvider' => $dataProvider,
      ]);
   }
   
   public function actionSendEmail($id)
   {
      $user = User::findOne(['id'=>$id]);
      echo "<pre>";
      var_dump($user->sendEmail($user));
   }
   
   /**
    * Displays a single User model.
    * @param int $id
    * @return string
    * @throws NotFoundHttpException if the model cannot be found
    */
   public function actionView($id)
   {
      $subs = UserSubscriptions::findAll(['user_id' => $id]);
      return $this->render('view', [
         'model' => $this->findModel($id),
         'subs' => $subs,
      ]);
   }
   
   /**
    * Creates a new User model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    * @return string|\yii\web\Response
    */
   public function actionCreate()
   {
      $model = new User();
      
      if ($this->request->isPost) {
         if ($model->load($this->request->post())) {
            $model->created_at = time();
            $model->updated_at = time();
            $model->setPassword($model->password);
            $model->generateAuthKey();
            $model->status = User::STATUS_ACTIVE;
            $model->save();
            Yii::$app->session->setFlash('success', 'New User Successfully Created');
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
    * Updates an existing User model.
    * If update is successful, the browser will be redirected to the 'view' page.
    * @param int $id
    * @return string|\yii\web\Response
    * @throws NotFoundHttpException if the model cannot be found
    */
   public function actionUpdate($id)
   {
      $model = $this->findModel($id);
      
      if ($this->request->isPost && $model->load($this->request->post())) {
         $db = Yii::$app->db->beginTransaction();
         try {
            $model->updated_at = time();
            $current_permission = AuthAssignment::findOne(['user_id' => $id]);
            if ($current_permission && $_POST['User']['permission'] != $current_permission->item_name) {
               $current_permission->delete();
            }
            $new_permission = new AuthAssignment([
               'item_name' => $_POST['User']['permission'],
               'user_id' => $id,
               'created_at' => time(),
            ]);
            $new_permission->save(false);
            $files = UploadedFile::getInstance($model, 'imageFile');
            if ($files) {
               $uploads = UploadsImage::uploadImage($model, $files, 'user');
               if ($uploads) {
                  $model->image = $uploads;
               }
            }
            $model->save(false);
            $db->commit();
            Yii::$app->session->setFlash('success', 'User Successfully Updated');
         } catch (\Exception $e) {
            $db->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
         }
         
         return $this->redirect(['view', 'id' => $model->id]);
      }
      
      return $this->render('update', [
         'model' => $model,
      ]);
   }
   
   public function actionStatus($id, $status)
   {
      $model = $this->findModel($id);
      $model->status = $status;
      $model->save(false);
      Yii::$app->session->setFlash('success', 'User Successfully Updated');
      return $this->redirect(Yii::$app->request->referrer);
   }
   
   /**
    * Deletes an existing User model.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    * @param int $id
    * @return \yii\web\Response
    * @throws NotFoundHttpException if the model cannot be found
    */
   public function actionDelete($id)
   {
      $this->findModel($id)->delete();
      
      return $this->redirect(['index']);
   }
   
   /**
    * Finds the User model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    * @param int $id
    * @return User the loaded model
    * @throws NotFoundHttpException if the model cannot be found
    */
   protected function findModel($id)
   {
      if (($model = User::findOne(['id' => $id])) !== null) {
         return $model;
      }
      
      throw new NotFoundHttpException('The requested page does not exist.');
   }
}
