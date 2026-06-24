<?php

namespace frontend\controllers;

use common\models\User;
use common\models\UserSubscriptions;
use frontend\models\ChangePasswordForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ProfileForm;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\ResetPasswordForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\Banner;
use common\models\Posts;
use common\models\Events;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup', 'profile'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'profile'],
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
        $banner = Banner::findAll(['status'=>Banner::STATUS_ACTIVE]);
        $news = Posts::find()->where(['status'=>1])->orderBy(['id'=>SORT_DESC])->limit(6)->all();
        $events = Events::find()->where(['status' => 1])->orderBy(['created_at' => SORT_DESC])->limit(2)->all();
        $contactModel = new ContactForm(['scenario' => 'homepage']);

        if ($contactModel->load(Yii::$app->request->post())) {
            $contactModel->email = Yii::$app->params['senderEmail'];
            $contactModel->subject = 'Homepage consultation request';

            if ($contactModel->validate() && $contactModel->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }
        
        return $this->render('index',[
            'banner'=>$banner,
            'news'=>$news,
            'events' => $events,
            'contactModel' => $contactModel,
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

        return $this->render('profile', [
            'user' => $user,
            'profileModel' => $profileModel,
            'passwordModel' => $passwordModel,
            'currentSubscription' => $currentSubscription,
            'subscriptionHistory' => $subscriptionHistory,
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
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
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
       $posts = Posts::find()->where(['status'=>1])->orderBy(['id'=>SORT_DESC])->limit(6)->all();
        return $this->render('about',['posts'=>$posts]);
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
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    
    public function actionVerifyEmail($token,$rer){
       $user = User::findByVerificationToken($token);
       $user->status = User::STATUS_ACTIVE;
       $user->updated_at=time();
       $user->save(false);
       return $this->redirect($rer);
    }

    private function t($key)
    {
        $lang = Yii::$app->language;
        return Yii::$app->params[$key][$lang] ?? Yii::$app->params[$key]['en'] ?? $key;
    }
}
