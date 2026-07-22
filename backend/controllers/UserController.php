<?php

namespace backend\controllers;

use common\models\AuthAssignment;
use common\models\ChangePass;
use common\models\UploadsImage;
use common\models\User;
use common\models\search\UserSearch;
use common\models\UserLoginSession;
use common\models\UserSubscriptions;
use common\services\UserLoginSessionService;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseController
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
                  'reset-password' => ['POST'],
                  'change-pass'=>['post'],
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
      $searchModel->role = 'admin';
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
      $model = $this->findModel($id);
      $subs = UserSubscriptions::findAll(['user_id' => $id]);
      $loginSessions = UserLoginSession::find()
         ->where([
            'user_id' => $model->id,
         ])
         ->orderBy([
            'last_seen_at' => SORT_DESC,
         ])
         ->limit(20)
         ->all();
      
      return $this->render('view', [
         'model' => $model,
         'subs' => $subs,
         'loginSessions' => $loginSessions,
      ]);
   }
   
   public function actionResetPassword($id)
   {
      $user = User::findOne(['id'=>$id]);
      $user->setPassword('123456');
      $user->save(false);
      Yii::$app->session->setFlash('success', 'Your password has been reset.');
      return $this->redirect(Yii::$app->request->referrer);
   }
   
   
   public function actionChangePass(int $id)
   {
      $user = User::findOne($id);
      
      if ($user === null) {
         throw new NotFoundHttpException('User not found.');
      }
      
      $changePass = new ChangePass($user);
      
      if (
         Yii::$app->request->isPost
         && $changePass->load(Yii::$app->request->post())
      ) {
         if ($changePass->changePassword()) {
            Yii::$app->session->setFlash(
               'success',
               'Password has been changed.'
            );
         } else {
            Yii::$app->session->setFlash(
               'error',
               implode('<br>', $changePass->getFirstErrors())
            );
         }
      }
      
      return $this->redirect(Yii::$app->request->referrer);
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
   public function actionLogoutSession(int $id)
   {
      $loginSession = UserLoginSession::findOne($id);
      
      if ($loginSession === null) {
         throw new \yii\web\NotFoundHttpException(
            'Login session was not found.'
         );
      }
      
      if (!Yii::$app->user->can('admin')) {
         throw new \yii\web\ForbiddenHttpException(
            'You are not allowed to manage user sessions.'
         );
      }
      
      if (!$loginSession->getIsActive()) {
         return $this->redirect([
            'profile',
            'id' => $loginSession->user_id,
         ]);
      }
      
      $currentTokenHash = UserLoginSessionService::getCurrentTokenHash();
      
      $isCurrentSession = $currentTokenHash !== null
         && hash_equals(
            $loginSession->token_hash,
            $currentTokenHash
         );
      
      $userId = $loginSession->user_id;
      
      if ($isCurrentSession) {
         $loginSession->logged_out_at = time();
         $loginSession->save(false, ['logged_out_at']);
         
         Yii::$app->user->logout();
         
         return $this->redirect(['/site/login']);
      }
      
      $loginSession->revoked_at = time();
      $loginSession->save(false, ['revoked_at']);
      
      Yii::$app->session->setFlash(
         'success',
         'The selected session has been logged out.'
      );
      
      return $this->redirect([
         'profile',
         'id' => $userId,
      ]);
   }
   
   public function actionLogoutOtherSessions(int $userId)
   {
      if (!Yii::$app->user->can('admin')) {
         throw new \yii\web\ForbiddenHttpException(
            'You are not allowed to manage user sessions.'
         );
      }
      
      $condition = [
         'and',
         ['user_id' => $userId],
         ['logged_out_at' => null],
         ['revoked_at' => null],
         ['>', 'expires_at', time()],
      ];
      
      $currentTokenHash = UserLoginSessionService::getCurrentTokenHash();
      
      /*
       * Не исключаем текущую сессию администратора,
       * если он смотрит профиль другого пользователя.
       */
      if (
         (int) Yii::$app->user->id === $userId
         && $currentTokenHash !== null
      ) {
         $condition[] = [
            '<>',
            'token_hash',
            $currentTokenHash,
         ];
      }
      
      UserLoginSession::updateAll(
         [
            'revoked_at' => time(),
         ],
         $condition
      );
      
      Yii::$app->session->setFlash(
         'success',
         'All other sessions have been logged out.'
      );
      
      return $this->redirect([
         'profile',
         'id' => $userId,
      ]);
   }
}
