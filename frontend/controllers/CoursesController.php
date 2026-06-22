<?php

namespace frontend\controllers;

use common\models\AuthAssignment;
use common\models\Billing;
use common\models\CourseCategory;
use common\models\Courses;
use common\models\SubscriptionPlans;
use common\models\User;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CoursesController extends Controller
{
   
   public function actionIndex($category, $slug)
   {
      if ($category === 'healthcare-employers' && $slug === 'hospitals') {
         return $this->actionHospitals($category, $slug);
      }

      $category = CourseCategory::findOne(['slug' => $category]);
      $courses = Courses::findOne(['slug' => $slug]);
      if ($category === null || $courses === null) {
         throw new NotFoundHttpException('Курс не найден.');
      }
      $subs = SubscriptionPlans::findAll(['status' => 1, 'course_id' => $courses->id]);
      $view = (int)$courses->page_type === 0 ? 'b2b' : 'no_subs';
      return $this->render($view, [
         'courses' => $courses,
         'subs' => $subs
      ]);
   }

   public function actionHospitals($category, $course)
   {
      $category = CourseCategory::findOne(['slug' => $category]);
      $courses = Courses::findOne(['slug' => $course]);
      if ($category === null || $courses === null) {
         throw new NotFoundHttpException('Курс не найден.');
      }

      $subs = SubscriptionPlans::findAll(['status' => 1, 'course_id' => $courses->id]);
      return $this->render('hospitals', [
         'courses' => $courses,
         'subs' => $subs,
      ]);
   }
   
   public function actionGetPlan($id)
   {
      $subs = SubscriptionPlans::findOne($id);
      $cleaning = Billing::find()->where(['user_id'=>null])->andWhere(['status'=>0])->all();
      $now = time();
      foreach ($cleaning as $item) {
         if ($now > $item->created_at + 86400) {
            $item->delete();
         }
      }
      if (!$subs) {
         throw new \yii\web\NotFoundHttpException();
      }
      
      $cookies = Yii::$app->request->cookies;
      
      $billing = null;
      
      // Ищем существующий billing по токену из cookie
      if ($cookies->has('billing_token')) {
         
         $billingToken = $cookies->getValue('billing_token');
         
         $billing = Billing::find()
            ->where([
               'billing_token' => $billingToken,
               'status' => 0, // только неоплаченные
            ])
            ->one();
         if (!Yii::$app->user->isGuest && $billing->user_id == null) {
            $billing->user_id = Yii::$app->user->id;
            $billing->save(false);
         }
         
         // если пользователь открыл другой тариф
         if ($billing && $billing->subscription_id != $subs->id) {
            $billing = null;
         }
      }
      
      // если не нашли - создаем новый
      if (!$billing) {
         
         $billing = new Billing([
            'billing_token' => Yii::$app->security->generateRandomString(32),
            'user_id' => Yii::$app->user->id ?? null,
            'subscription_id' => $subs->id,
            'amount' => $subs->price,
            'status' => 0,
            'created_at' => time(),
            'updated_at' => time(),
         ]);
         
         $billing->save(false);
         
         Yii::$app->response->cookies->add(
            new \yii\web\Cookie([
               'name' => 'billing_token',
               'value' => $billing->billing_token,
               'expire' => time() + 86400, // 1 день
               'httpOnly' => true,
            ])
         );
      }
      
      
      return $this->render('invoice', [
         'model' => $subs,
         'billing' => $billing,
      ]);
   }
   
   public function actionGuestRegister()
   {
      if (Yii::$app->request->isPost) {
         $transaction = Yii::$app->db->beginTransaction();
         try {
            $user = new \common\models\User();
            $post = Yii::$app->request->post('User');
            $user->username = $post['username'];
            $user->email = $post['email'];
            $user->fullname = "{$post['first_name']} {$post['last_name']}";
            if ($post['password'] == $post['password_confirm']) {
               $user->setPassword($post['password']);
            }
            $user->generateAuthKey();
            $user->created_at = time();
            $user->updated_at = time();
            $user->phone = $post['phone'];
            $user->status = \common\models\User::STATUS_ACTIVE;
            $user->generateEmailVerificationToken();
            $user->save(false);
            $user_permission = new AuthAssignment([
               'user_id' => $user->id,
               'item_name' => 'guest',
            ]);
            $user_permission->save(false);
            Yii::$app->user->login($user, 3600 * 24 * 30);
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            $user->sendEmail($user);
            $transaction->commit();
         } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
         }
         
         return $this->redirect(Yii::$app->request->referrer);
      }
   }
   
   public function actionConfirm($id)
   {
      $user = User::findOne(['id'=>$id]);
      $billing = Billing::find()->where(['user_id'=>$user->id,'status'=>0])->orderBy(['id'=>SORT_DESC])->one();
      [$first_name, $last_name]= explode(' ',$user->fullname);
      $monthCount = $billing->subscription->duration_days / 30;
      $data = [
         'event'=>'order_paid',
         'order_ref'=>$billing->billing_token,
         'first_name'=>$first_name,
         'last_name'=>$last_name,
         'email'=>$user->email,
         'telegram_id'=>00,
         'course_sku'=>'11',
         'duration_month'=>intval($monthCount)
      ];
   }
   public function actionTest(){
      $user = User::findOne(['id'=>4]);
      echo "<pre>";
      print_r($user->sendEmail($user));
      die();
   }
   
   private static function getClient()
   {
      return new \yii\httpclient\Client();
   }
   private function actionWebhook($url, $data)
   {
      $url = "url";
      $response = self::getClient()->post($url, json_encode($data), [
         'Content-Type' => 'application/json',
         'Accept' => 'application/json',
         'Authorization' => "Basic {123}"
      ])->send();
      if ($response) {
         return $response->data;
      }
   }
}