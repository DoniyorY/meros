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
        return $this->render('view', [
            'model' => $this->findModel($id),
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
