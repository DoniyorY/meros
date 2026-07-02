<?php

namespace backend\controllers;

use common\models\Mentors;
use common\models\UploadsImage;
use common\models\search\MentorsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use Yii;

/**
 * MentorsController implements the CRUD actions for Mentors model.
 */
class MentorsController extends Controller
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
     * Lists all Mentors models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MentorsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Mentors model.
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
     * Creates a new Mentors model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Mentors();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->status = 1;
                $model->created_at = time();
                $model->updated_at = time();
                $model->user_id = Yii::$app->user->id;
                $file = UploadedFile::getInstance($model, 'imageFile');
                if ($file) {
                    $model->image = UploadsImage::uploadImage($model, $file, 'mentors');
                } else {
                    $model->image = ' ';
                }
                $avatarFile = UploadedFile::getInstance($model, 'avatarFile');
                if ($avatarFile) {
                    $model->avatar = UploadsImage::uploadImage($model, $avatarFile, 'mentors/avatar');
                }
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

    /**
     * Updates an existing Mentors model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->updated_at = time();
            $model->user_id = Yii::$app->user->id;
            $file = UploadedFile::getInstance($model, 'imageFile');
            if ($file) {
                $model->image = UploadsImage::uploadImage($model, $file, 'mentors');
            }
            $avatarFile = UploadedFile::getInstance($model, 'avatarFile');
            if ($avatarFile) {
                $model->avatar = UploadsImage::uploadImage($model, $avatarFile, 'mentors/avatar');
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Mentors model.
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
     * Finds the Mentors model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Mentors the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mentors::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
