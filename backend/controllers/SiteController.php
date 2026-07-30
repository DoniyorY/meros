<?php

namespace backend\controllers;

use common\models\Billing;
use common\models\Contacts;
use common\models\Courses;
use common\models\LoginForm;
use common\models\Posts;
use common\models\User;
use common\models\UserSubscriptions;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
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
            'only' => [
               'index',
               'logout',
               'visit-chart',
            ],
            'rules' => [
               [
                  'actions' => [
                     'index',
                     'logout',
                     'visit-chart',
                  ],
                  'allow' => true,
                  'roles' => ['@'],
               ],
            ],
         ],
         
         'verbs' => [
            'class' => VerbFilter::class,
            'actions' => [
               'logout' => ['post'],
               'visit-chart' => ['get'],
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
      ];
   }
   
   /**
    * Displays homepage.
    *
    * @return string
    */
   public function actionIndex()
   {
      $this->dropTrashBilling();
      $this->checkAllSubs();
      $billing_count = Billing::find()->where(['status'=>Billing::STATUS_SUCCESS])->count();
      $client_count = User::find()
         ->joinWith('assignment')
         ->where(['auth_assignment.item_name' => 'guest'])
         ->andWhere(['status'=>10])
         ->count();
      $course_count = Courses::find()->where(['status'=>1])->count();
      $subs_count = UserSubscriptions::find()->where(['status'=>1])->count();
      $posts = Posts::find()->where(['status'=>1])->orderBy(['id'=>SORT_DESC])->limit(4)->all();
      $contacts = Contacts::findAll(['status'=>0]);

      $customerSort = Yii::$app->request->get('customerSort', 'subscribed');
      $customerSortOptions = [
         'subscribed' => new Expression(
            'EXISTS (SELECT 1 FROM user_subscriptions WHERE user_subscriptions.user_id = user.id) DESC, user.created_at DESC'
         ),
         'newest' => ['user.created_at' => SORT_DESC],
         'oldest' => ['user.created_at' => SORT_ASC],
      ];

      if (!is_string($customerSort) || !isset($customerSortOptions[$customerSort])) {
         $customerSort = 'subscribed';
      }

      $customersQuery = User::find()
         ->joinWith('assignment')
         ->where(['auth_assignment.item_name' => 'guest']);
      $customerPagination = new Pagination([
         'totalCount' => $customersQuery->count(),
         'pageSize' => 5,
         'pageParam' => 'customerPage',
      ]);
      $customers = $customersQuery
         ->orderBy($customerSortOptions[$customerSort])
         ->offset($customerPagination->offset)
         ->limit($customerPagination->limit)
         ->all();

      return $this->render('index',[
         'billing_count' => $billing_count,
         'client_count' => $client_count,
         'course_count' => $course_count,
         'subs_count' => $subs_count,
         'contacts' => $contacts,
         'posts' => $posts,
         'customers' => $customers,
         'customerPagination' => $customerPagination,
         'customerSort' => $customerSort,
      ]);
   }
   
   /**
    * @throws \DateInvalidTimeZoneException
    * @throws BadRequestHttpException
    * @throws \Exception
    */
   public function actionVisitChart()
   {
      Yii::$app->response->format = Response::FORMAT_JSON;
      
      $period = Yii::$app->request->get('period', 'current_week');
      
      $allowedPeriods = [
         'today',
         'current_week',
         'last_week',
         'last_month',
         'current_year',
      ];
      
      if (!in_array($period, $allowedPeriods, true)) {
         throw new BadRequestHttpException('Invalid chart period.');
      }
      
      $timezone = new \DateTimeZone(
         Yii::$app->timeZone ?: 'Asia/Tashkent'
      );
      
      $now = new \DateTimeImmutable('now', $timezone);
      
      $categories = [];
      $bucketKeys = [];
      
      switch ($period) {
         case 'today':
            $periodLabel = 'Today';
            
            $start = $now->setTime(0, 0, 0);
            $end = $start->modify('+1 day');
            
            $groupExpression = new Expression(
               "DATE_FORMAT(FROM_UNIXTIME(visited_at), '%H')"
            );
            
            for ($hour = 0; $hour < 24; $hour++) {
               $key = str_pad((string) $hour, 2, '0', STR_PAD_LEFT);
               
               $bucketKeys[] = $key;
               $categories[] = $key . ':00';
            }
            
            break;
         
         case 'last_week':
            $periodLabel = 'Last Week';
            
            $end = $now
               ->modify('monday this week')
               ->setTime(0, 0, 0);
            
            $start = $end->modify('-7 days');
            
            $groupExpression = new Expression(
               "DATE_FORMAT(FROM_UNIXTIME(visited_at), '%Y-%m-%d')"
            );
            
            for ($date = $start; $date < $end; $date = $date->modify('+1 day')) {
               $bucketKeys[] = $date->format('Y-m-d');
               $categories[] = $date->format('D');
            }
            
            break;
         
         case 'last_month':
            $periodLabel = 'Last Month';
            
            $end = $now
               ->modify('first day of this month')
               ->setTime(0, 0, 0);
            
            $start = $end->modify('-1 month');
            
            $groupExpression = new Expression(
               "DATE_FORMAT(FROM_UNIXTIME(visited_at), '%Y-%m-%d')"
            );
            
            for ($date = $start; $date < $end; $date = $date->modify('+1 day')) {
               $bucketKeys[] = $date->format('Y-m-d');
               $categories[] = $date->format('j');
            }
            
            break;
         
         case 'current_year':
            $periodLabel = 'Current Year';
            
            $start = $now
               ->setDate((int) $now->format('Y'), 1, 1)
               ->setTime(0, 0, 0);
            
            $end = $start->modify('+1 year');
            
            $groupExpression = new Expression(
               "DATE_FORMAT(FROM_UNIXTIME(visited_at), '%m')"
            );
            
            for ($month = 1; $month <= 12; $month++) {
               $date = $start->modify('+' . ($month - 1) . ' months');
               
               $bucketKeys[] = $date->format('m');
               $categories[] = $date->format('M');
            }
            
            break;
         
         case 'current_week':
         default:
            $periodLabel = 'Current Week';
            
            $start = $now
               ->modify('monday this week')
               ->setTime(0, 0, 0);
            
            $end = $start->modify('+7 days');
            
            $groupExpression = new Expression(
               "DATE_FORMAT(FROM_UNIXTIME(visited_at), '%Y-%m-%d')"
            );
            
            for ($date = $start; $date < $end; $date = $date->modify('+1 day')) {
               $bucketKeys[] = $date->format('Y-m-d');
               $categories[] = $date->format('D');
            }
            
            break;
      }
      
      $rows = (new Query())
         ->select([
            'bucket' => $groupExpression,
            'visits' => new Expression(
               'COUNT(DISTINCT session_id)'
            ),
         ])
         ->from('{{%site_visit}}')
         ->where([
            '>=',
            'visited_at',
            $start->getTimestamp(),
         ])
         ->andWhere([
            '<',
            'visited_at',
            $end->getTimestamp(),
         ])
         ->andWhere([
            'not',
            ['session_id' => null],
         ])
         ->andWhere([
            '<>',
            'session_id',
            '',
         ])
         ->andWhere([
            'is_bot'=>0,
         ])
         ->groupBy($groupExpression)
         ->indexBy('bucket')
         ->all();
      
      $chartData = [];
      
      foreach ($bucketKeys as $bucketKey) {
         $chartData[] = isset($rows[$bucketKey])
            ? (int) $rows[$bucketKey]['visits']
            : 0;
      }
      
      return [
         'period' => $period,
         'periodLabel' => $periodLabel,
         'range' => [
            'start' => $start->format('Y-m-d H:i:s'),
            'end' => $end->format('Y-m-d H:i:s'),
         ],
         'categories' => $categories,
         'series' => [
            [
               'name' => 'Visits',
               'data' => $chartData,
            ],
         ],
         'total' => array_sum($chartData),
      ];
   }
   
   /**
    * Login action.
    *
    * @return string|Response
    */
   public function actionLogin()
   {
      if (!Yii::$app->user->isGuest) {
         return $this->goHome();
      }
      
      $this->layout = 'blank';
      
      $model = new LoginForm();
      if ($model->load(Yii::$app->request->post()) && $model->login()) {
         if (Yii::$app->user->identity->status == 9){
            Yii::$app->session->setFlash('warning','Your Account Has Been Inactivated');
            return $this->refresh();
         }
         if (Yii::$app->user->can('guest')) {
            Yii::$app->user->logout();
            Yii::$app->session->setFlash('warning', 'HTTP ERROR 403 You are not allowed to access this page.');;
            return $this->refresh();
         }
         
         return $this->goBack();
      }
      
      
      $model->password = '';
      
      return $this->render('login', [
         'model' => $model,
      ]);
   }
   
   /**
    * Logout action.
    *
    * @return Response
    */
   public function actionLogout()
   {
      Yii::$app->user->logout();
      
      return $this->goHome();
   }
}
