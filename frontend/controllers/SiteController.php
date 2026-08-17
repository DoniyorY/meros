<?php

namespace frontend\controllers;

use common\models\Billing;
use common\models\Faq;
use common\models\User;
use common\models\UserSubscriptions;
use common\services\TelegramStaffNotificationService;
use frontend\models\ChangePasswordForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ProfileForm;
use frontend\models\ResetPasswordForm;
use Yii;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Contacts;
use frontend\models\SignupForm;
use common\models\Banner;
use common\models\Posts;
use common\models\Events;
use common\models\Gallery;
use yii\web\NotFoundHttpException;
use common\models\Courses;
use common\models\CourseCategory;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends BaseController
{
   /**
    * {@inheritdoc}
    */
   public function behaviors()
   {
      return [
         'access' => [
            'class' => AccessControl::class,
            'only' => ['logout', 'signup', 'profile', 'invoice'],
            'rules' => [
               [
                  'actions' => ['signup'],
                  'allow' => true,
                  'roles' => ['?'],
               ],
               [
                  'actions' => ['logout', 'profile', 'invoice'],
                  'allow' => true,
                  'roles' => ['@'],
               ],
            ],
         ],
         'verbs' => [
            'class' => VerbFilter::class,
            'actions' => [
               'logout' => ['post'],
            ],
         ],
      ];
   }
   
   /**
    * {@inheritdoc}
    */
   public function actions()
   {
      return [
         'error' => [
            'class' => \yii\web\ErrorAction::class,
         ],
         'captcha' => [
            'class' => \yii\captcha\CaptchaAction::class,
            'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
         ],
      ];
   }
   
   /**
    * Displays homepage.
    *
    * @return mixed
    */
   public function actionIndex()
   {
      $this->checkAllSubs();
      $this->dropTrashBilling();
      $banner = Banner::findAll(['status' => Banner::STATUS_ACTIVE]);
      $news = Posts::find()->where(['status' => 1])->orderBy(['id' => SORT_DESC])->limit(6)->all();
      $events = Events::find()->where(['status' => 1])->orderBy(['created_at' => SORT_DESC])->limit(2)->all();
      $contactModel = new Contacts(['scenario' => 'homepage']);
      
      if ($contactModel->load(Yii::$app->request->post())) {
         if ($contactModel->save()) {
            Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            return $this->refresh();
         }
      }
      
      return $this->render('index', [
         'banner' => $banner,
         'news' => $news,
         'events' => $events,
         'contactModel' => $contactModel,
      ]);
      
   }

   /** XML index of every public, indexable page in all supported languages. */
   public function actionSitemap(): string
   {
      Yii::$app->response->format = Response::FORMAT_RAW;
      Yii::$app->response->headers->set('Content-Type', 'application/xml; charset=UTF-8');

      $pages = [];
      foreach (['', 'about', 'contact', 'team', 'post', 'events', 'faq/faq-students', 'faq/faq-organisations'] as $path) {
         $pages[] = ['path' => $path, 'priority' => $path === '' ? '1.0' : '0.7'];
      }

      $categories = CourseCategory::find()->where(['status' => 1])->indexBy('id')->all();
      foreach (Courses::find()->where(['status' => Courses::STATUS_ACTIVE])->all() as $course) {
         if (isset($categories[$course->category_id])) {
            $pages[] = [
               'path' => $categories[$course->category_id]->slug . '/' . $course->slug,
               'lastmod' => $course->updated_at,
               'priority' => '0.9',
            ];
         }
      }
      foreach (Posts::find()->where(['status' => 1])->all() as $post) {
         $pages[] = ['path' => 'post/' . $post->id, 'lastmod' => $post->updated_at, 'priority' => '0.7'];
      }
      foreach (Events::find()->where(['status' => 1])->all() as $event) {
         $pages[] = ['path' => 'events/' . $event->id, 'lastmod' => $event->updated_at, 'priority' => '0.7'];
      }

      $origin = rtrim(Yii::$app->request->hostInfo, '/');
      $xml = ['<?xml version="1.0" encoding="UTF-8"?>', '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'];
      foreach ($pages as $page) {
         foreach (['ru', 'en', 'uz'] as $language) {
            $location = $origin . '/' . $language . '/' . ltrim($page['path'], '/');
            $xml[] = '  <url>';
            $xml[] = '    <loc>' . htmlspecialchars($location, ENT_XML1) . '</loc>';
            if (!empty($page['lastmod'])) {
               $xml[] = '    <lastmod>' . gmdate('Y-m-d', (int) $page['lastmod']) . '</lastmod>';
            }
            foreach (['ru', 'en', 'uz'] as $alternate) {
               $href = $origin . '/' . $alternate . '/' . ltrim($page['path'], '/');
               $xml[] = '    <xhtml:link rel="alternate" hreflang="' . $alternate . '" href="' . htmlspecialchars($href, ENT_XML1) . '" />';
            }
            $xml[] = '    <priority>' . $page['priority'] . '</priority>';
            $xml[] = '  </url>';
         }
      }
      $xml[] = '</urlset>';

      return implode("\n", $xml);
   }
   private function checkAllSubs()
   {
      
      $user_subs = UserSubscriptions::findAll(['status'=>1]);
      foreach ($user_subs as $item) {
         if ($item->expires_date <= time()){
            $item->status = 0;
            $item->save(false);
         }
      }
   }
   private function dropTrashBilling()
   {
      
      $billing = Billing::find()
         ->where(['start_date'=>null])
         ->andWhere(['expires_date'=>null])
         ->andWhere(['status'=>0])
         ->andWhere(['payment_transaction_id'=>null])
         ->all();
      foreach ($billing as $item) {
         $item->delete();
      }
   }
   public function actionTeams()
   {
      $mentors = \common\models\Mentors::findAll(['status' => 1]);
      return $this->render('teams', [
         'mentors' => $mentors,
      ]);
   }
   
   /**
    * Logs in a user.
    *
    * @return mixed
    */
   public function actionLogin()
   {
      if (!Yii::$app->user->isGuest) {
         return $this->redirect(['site/profile']);
      }
      
      $model = new LoginForm();
      if ($model->load(Yii::$app->request->post()) && $model->login()) {
         return $this->goBack();
      }
      
      $model->password = '';
      
      return $this->render('login', [
         'model' => $model,
      ]);
   }
   
   public function actionPolicy()
   {
      return $this->render('policy');
   }
   
   public function actionProfile()
   {
      $user = Yii::$app->user->identity;
      $profileModel = new ProfileForm($user);
      $passwordModel = new ChangePasswordForm($user);
      
      if ($profileModel->load(Yii::$app->request->post()) && $profileModel->save()) {
         Yii::$app->session->setFlash('success', $this->t('profile_saved_message'));
         return $this->redirect(['site/profile']);
      }
      
      if ($passwordModel->load(Yii::$app->request->post()) && $passwordModel->changePassword()) {
         Yii::$app->session->setFlash('success', $this->t('profile_password_changed_message'));
         return $this->redirect(['site/profile', '#' => 'tab-change-password']);
      }
      
      $currentSubscription = UserSubscriptions::find()
         ->where(['user_id' => $user->id, 'status' => 1])
         ->with(['plan'])
         ->orderBy(['expires_date' => SORT_DESC, 'id' => SORT_DESC])
         ->one();
      
      $subscriptionHistory = UserSubscriptions::find()
         ->where(['user_id' => $user->id])
         ->with(['plan'])
         ->orderBy(['created_at' => SORT_DESC, 'id' => SORT_DESC])
         ->all();

      $billings = Billing::find()
         ->where(['user_id' => Yii::$app->user->id])
         ->with(['subscription'])
         ->orderBy(['id' => SORT_DESC])
         ->all();
      
      return $this->render('profile', [
         'user' => $user,
         'profileModel' => $profileModel,
         'passwordModel' => $passwordModel,
         'currentSubscription' => $currentSubscription,
         'subscriptionHistory' => $subscriptionHistory,
         'billings' => $billings,
      ]);
   }

   public function actionInvoice($id)
   {
      $billing = Billing::find()
         ->where([
            'id' => (int)$id,
            'user_id' => Yii::$app->user->id,
         ])
         ->with(['subscription.course'])
         ->one();

      if ($billing === null) {
         throw new NotFoundHttpException('Invoice not found.');
      }

      return $this->render('invoice', [
         'billing' => $billing,
         'user' => Yii::$app->user->identity,
      ]);
   }
   
   /**
    * Logs out the current user.
    *
    * @return mixed
    */
   public function actionLogout()
   {
      Yii::$app->user->logout();
      
      return $this->goHome();
   }
   
   public function actionRequestPasswordReset()
   {
      $model = new PasswordResetRequestForm();
      if (!Yii::$app->user->isGuest) {
         $model->email = Yii::$app->user->identity->email;
      }
      
      if ($model->load(Yii::$app->request->post()) && $model->validate()) {
         if ($model->sendEmail()) {
            Yii::$app->session->setFlash('success', $this->t('password_reset_email_sent'));
            return $this->goHome();
         }
         
         Yii::$app->session->setFlash('error', $this->t('password_reset_email_error'));
      }
      
      return $this->render('requestPasswordResetToken', [
         'model' => $model,
      ]);
   }
   
   public function actionResetPassword($token)
   {
      try {
         $model = new ResetPasswordForm($token);
      } catch (\InvalidArgumentException $e) {
         throw new BadRequestHttpException($e->getMessage());
      }
      
      if ($model->load(Yii::$app->request->post()) && $model->resetPassword()) {
         Yii::$app->session->setFlash('success', $this->t('password_reset_success'));
         return $this->goHome();
      }
      
      return $this->render('resetPassword', [
         'model' => $model,
      ]);
   }
   
   /**
    * Displays contact page.
    *
    * @return mixed
    */
   public function actionContact()
   {
      $model = new Contacts();
      if ($model->load(Yii::$app->request->post())) {
         if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            return $this->refresh();
         }
      }
      
      return $this->render('contact', [
         'model' => $model,
      ]);
   }
   
   /**
    * Displays about page.
    *
    * @return mixed
    */
   public function actionAbout()
   {
      $posts = Posts::find()->where(['status' => 1])->orderBy(['id' => SORT_DESC])->limit(6)->all();
      $gallery = Gallery::find()
         ->where(['status' => Gallery::STATUS_ACTIVE])
         ->andWhere(['not', ['image' => null]])
         ->orderBy(['created_at' => SORT_DESC, 'id' => SORT_DESC])
         ->all();
      
      return $this->render('about', [
         'posts' => $posts,
         'gallery' => $gallery,
      ]);
   }
   
   public function actionTestBot()
   {
      $billing = Billing::findOne(['id' => 191]);
      TelegramStaffNotificationService::sendNewSubscriptionNotification($billing);
   }
   
   private function buildSmsText(string $type, User $user): string
   {
      $lang = substr((string)Yii::$app->language, 0, 2);
      
      $messages = Yii::$app->params['smsMessages'][$type] ?? [];
      
      $template = $messages[$lang]
         ?? $messages['en']
         ?? '';
      
      return strtr($template, [
         '{name}' => (string)$user->fullname,
         '{bot_link}' => (string)Yii::$app->params['telegramBotLink'],
         '{platform_link}' => (string)Yii::$app->params['coursePlatformUrl'],
      ]);
   }
   
   /**
    * Signs user up.
    *
    * @return mixed
    */
   
   public function actionSignup()
   {
      $model = new SignupForm();
      if ($model->load(Yii::$app->request->post()) && $model->signup()) {
         $user = User::findByUsername($model->username);
         if (!$user){
            throw new NotFoundHttpException('User not found.');
         }
         $text = $this->buildSmsText('registration', $user);
         Yii::$app->playmobile->sendSms("$user->phone", $text);
         Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
         return $this->goHome();
      }
      
      return $this->render('signup', [
         'model' => $model,
      ]);
   }
   
   public function actionFaq($page)
   {
      if ($page == 'faq-students') {
         $page = 1;
      } else {
         $page = 2;
      }
      $faq = Faq::find()->where(['page_id' => $page])->orderBy(['id' => SORT_ASC])->all();
      return $this->render('faqs', [
         'faqs' => $faq,
      ]);
   }
   
   public function actionVerifyEmail($token, $rer)
   {
      $user = User::findByVerificationToken($token);
      $user->status = User::STATUS_ACTIVE;
      $user->updated_at = time();
      $user->save(false);
      return $this->redirect($rer);
   }
   
   private function t($key)
   {
      $lang = Yii::$app->language;
      return Yii::$app->params[$key][$lang] ?? Yii::$app->params[$key]['en'] ?? $key;
   }
}
