<?php

namespace backend\controllers;

use common\models\CoursePacks;
use common\models\Courses;
use common\models\search\CoursePacksSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * CoursePacksController implements the CRUD actions for CoursePacks model.
 */
class CoursePacksController extends BaseController
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
     * Lists all CoursePacks models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CoursePacksSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CoursePacks model.
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
     * Creates a new CoursePacks model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new CoursePacks();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->created_at = time();
                $model->updated_at = time();
                $model->user_id = \Yii::$app->user->id;
                $model->status = 1;
                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }
            \Yii::$app->session->setFlash('warning', 'Error');
            return $this->redirect(['index']);
        }
    }

    public function actionAddItems($pack_id)
    {
        $item = new CoursePackItems(['pack_id' => $pack_id]);
        if ($item->load($this->request->post())) {
            if (!$item->course_category_id || !$item->course_id) {
                Yii::$app->session->setFlash('warning', 'Fill At Least One Course');
                return $this->redirect(Yii::$app->request->referrer);
            }
            if ($item->course_category_id) {
                $courses = Courses::findAll(['category_id' => $item->course_category_id]);
                foreach ($courses as $course) {
                    $multi_item = new CoursePackItems([
                        'pack_id' => $pack_id,
                        'course_id' => $course->id,
                        'course_category_id' => $item->course_category_id,
                    ]);
                    $multi_item->save(false);
                }
            } else {
                $course = Courses::findOne(['id' => $item->course_id]);
                $item->course_category_id = $course->category_id;
            }
            if ($item->save()) {
                Yii::$app->session->setFlash('success', 'Course is Successfully Created');
            } else {
                Yii::$app->session->setFlash('warning', 'Error to Add Course');
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionStatus($id, $status)
    {
        $model = $this->findModel($id);
        $model->status = $status;
        $model->save();
        \Yii::$app->session->setFlash('success', 'Status is Successfully changed');
        return $this->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Updates an existing CoursePacks model.
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

    /**
     * Deletes an existing CoursePacks model.
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

    /**
     * Finds the CoursePacks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return CoursePacks the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CoursePacks::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
