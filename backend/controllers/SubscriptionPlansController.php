<?php

namespace backend\controllers;

use common\models\SubscriptionPlans;
use common\models\SubscriptionPlanItems;
use common\models\search\SubscriptionPlansSearch;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SubscriptionPlansController implements the CRUD actions for SubscriptionPlans model.
 */
class SubscriptionPlansController extends Controller
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
                        'delete-item' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function beforeAction($action)
    {
        if ($action->id == 'delete-item') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    /**
     * Lists all SubscriptionPlans models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SubscriptionPlansSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SubscriptionPlans model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
       $subscriptionPlans = $this->findModel($id);
       /*$items = [
          [
             'name_en' => '5 x 1-hour lessons with an expert teacher',
             'desc_en' => '<p>You receive one-to-one lessons with an expert Nursing English tutor (5 classes of 1 hour each). These classes target what you want to practise and what you most need to work on to develop your professional English.</p>',
          ],
          [
             'name_en' => 'Personal study programme',
             'desc_en' => '<p>Do you have specific language goals? Do you want to improve your spoken confidence? Do you want feedback on your written English from an expert? Then the Premium course is a great choice for you.</p><p>Before you take the course, we ask you to complete a detailed needs analysis form. This means your lessons will be targeted to your needs from the very start.</p>',
          ],
          [
             'name_en' => 'Classes organised around your schedule',
             'desc_en' => '<p>You take your classes with your teacher when you are available. We understand how busy nurses and nursing students are, so offer you maximum flexibility.</p>',
          ],
          [
             'name_en' => "60 hours' online study",
             'desc_en' => '<p>A wide range of language, covering communication skills, nursing and everyday terms, hospital and care scenarios, interactions with patients and colleagues – all explained clearly with multiple practice activities.</p>',
          ],
          [
             'name_en' => 'Healthcare-focus at all times',
             'desc_en' => '<p>All the course content is nursing-focused, including terminology, communication skills, and language skills development. All grammar, vocabulary and pronunciation work is contextualised, so your learning remains relevant at all times.</p>',
          ],
          [
             'name_en' => 'Multi media- video, audio, visual',
             'desc_en' => '<p>Learning inputs and exercises are varied throughout – from animated videos to voice recording tasks to charts, diagrams, articles, dialogues and practice activities.</p>',
          ],
          [
             'name_en' => 'Authentic hospital documents',
             'desc_en' => '<p>The course includes authentic hospital charts and forms throughout, so you can analyse and learn nursing English as it is used in practice.</p>',
          ],
          [
             'name_en' => 'Interactive recording tasks',
             'desc_en' => '<p>Practise your communication skills by responding to what patients and colleagues say in unique voice recording activities. Your conversations can be downloaded as mp3 files.</p>',
          ],
          [
             'name_en' => 'Downloadable glossaries',
             'desc_en' => '<p>Download the key language items from each unit so you have a convenient reference guide to hand at any time.</p>',
          ],
          [
             'name_en' => 'Wide range of tasks and quizzes',
             'desc_en' => '<p>Tasks and quizzes include drag and drop, multiple choice, gap-fill, drop-down selection, re-ordering language, spotting errors and voice recording. And all optimised for mobile phone use as well as PC and tablet.</p>',
          ],
          [
             'name_en' => 'Up-to-date content',
             'desc_en' => '<p>Content is continuously updated to make sure it is up-to-date and relevant at all times.</p>',
          ],
          [
             'name_en' => 'CPD certificate',
             'desc_en' => '<p>English for Nurses is accredited by the CPD Standards Office in the UK, whose qualifications are recognised worldwide. You will receive your Certificate of Achievement after completing the course with an overall score of 70% accuracy or more.</p>',
          ],
          [
             'name_en' => '3 months access',
             'desc_en' => '<p>You have 3 months to work through the course. This can be extended at any time for a small fee.</p>',
          ],
       ];
       foreach ($items as $item) {
          $plan = SubscriptionPlans::find()->where(['id' => $subscriptionPlans->id])->one();
          $check = SubscriptionPlans::find()
             ->where(['name_en'=>$item['name_en']])->exists();
          if ($check) continue;
          if (!$plan) continue;
          $plan_item = new SubscriptionPlanItems([
             'plan_id'=>$plan->id,
             'name_ru'=>'-',
             'name_uz'=>'-',
             'name_en'=>$item['name_en'],
             'desc_ru'=>'-',
             'desc_uz'=>'-',
             'desc_en'=>$item['desc_en'],
          ]);
          $plan_item->save(false);
       }*/
       return $this->render('view', [
            'model' => $subscriptionPlans,
        ]);
    }

    /**
     * Creates a new SubscriptionPlans model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SubscriptionPlans();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->created_at=time();
                $model->updated_at=time();
                $model->status = 0;
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

    public function actionAddItems($plan_id)
    {
        $model = new SubscriptionPlanItems();
        $model->plan_id = $plan_id;
        if ($model->load($this->request->post())) {
            $model->save();
        }
        \Yii::$app->session->setFlash('success','Facility is Successfully Added');
        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionStatus($id, $status)
    {
        $model = $this->findModel($id);
        $model->status = $status;
        $model->save(false);
        \Yii::$app->session->setFlash('success','Status Changed Successfully');
        return $this->redirect(\Yii::$app->request->referrer);
    }
    /**
     * Updates an existing SubscriptionPlans model.
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
    public function actionUpdateItemModal($id)
    {
        $model = SubscriptionPlanItems::findOne(['id'=>$id]);
        return $this->renderAjax('_form_item', [
            'model' => $model,
            'plan_id'=>$model->plan_id,
            'url'=>Url::to(['update-facility','id'=>$model->id])
        ]);
    }

    public function actionUpdateFacility($id)
    {
        $item = SubscriptionPlanItems::findOne(['id'=>$id]);
        if ($item->load($this->request->post())) {
            $item->save();
            \Yii::$app->session->setFlash('success','Facility is Successfully Updated');
        }else{
            \Yii::$app->session->setFlash('error','Facility Not Updated');
        }
        return $this->redirect(\Yii::$app->request->referrer);

    }

    /**
     * Deletes an existing SubscriptionPlans model.
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

    public function actionDeleteItem($id)
    {
        $model = SubscriptionPlanItems::findOne($id);
        $model->delete();
        \Yii::$app->session->setFlash('success','Item is Successfully Deleted');
        return $this->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Finds the SubscriptionPlans model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return SubscriptionPlans the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SubscriptionPlans::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
