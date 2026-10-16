<?php

namespace backend\controllers;

use common\models\Promocodes;
use common\models\search\PromocodesSearch;
use backend\models\PromocodesCreateForm;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Lists, generates and deletes promocodes.
 */
class PromocodesController extends BaseController
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
     * Lists all Promocodes models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PromocodesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new PromocodesCreateForm();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->create()) {
            Yii::$app->session->setFlash('success', 'Создано промокодов: ' . (int) $model->quantity);
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Deletes an existing Promocodes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        Promocodes::getDb()->transaction(function () use ($id) {
            Promocodes::getDb()->createCommand(
                'SELECT [[id]] FROM {{%promocodes}} WHERE [[id]] = :id FOR UPDATE',
                [':id' => $id]
            )->queryScalar();
            $model = $this->findModel($id);
            if (\common\models\Billing::find()->where(['promocode_id' => $model->id])->exists()) {
                Yii::$app->session->setFlash('error', 'Нельзя удалить промокод, связанный со счётом.');
                return;
            }
            $model->delete();
        });

        return $this->redirect(['index']);
    }

    /**
     * Finds the Promocodes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Promocodes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Promocodes::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Промокод не найден.');
    }
}
