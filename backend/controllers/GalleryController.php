<?php

namespace backend\controllers;

use common\models\Gallery;
use common\models\search\GallerySearch;
use common\models\UploadsImage;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * GalleryController implements the CRUD actions for Gallery model.
 */
class GalleryController extends BaseController
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
     * Lists all Gallery models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new GallerySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Gallery model.
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
     * Creates one or many Gallery models from uploaded image files.
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Gallery();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $files = UploadedFile::getInstances($model, 'imageFiles');

            if (empty($files)) {
                $model->addError('imageFiles', 'Please upload at least one image.');
            } else {
                $savedIds = [];

                foreach ($files as $file) {
                    $gallery = new Gallery();
                    $gallery->page_id = $model->page_id;
                    $gallery->status = $model->status ?? Gallery::STATUS_ACTIVE;
                    $gallery->created_at = time();
                    $gallery->updated_at = time();
                    $gallery->user_id = Yii::$app->user->id;

                    $uploaded = UploadsImage::uploadImage($gallery, $file, 'gallery');
                    if ($uploaded) {
                        $gallery->image = $uploaded;
                    }

                    if ($gallery->save()) {
                        $savedIds[] = $gallery->id;
                    } else {
                        $model->addErrors($gallery->errors);
                    }
                }

                if (!empty($savedIds) && !$model->hasErrors()) {
                    Yii::$app->session->setFlash('success', 'Gallery images uploaded successfully.');
                    return count($savedIds) === 1
                        ? $this->redirect(['view', 'id' => reset($savedIds)])
                        : $this->redirect(['index']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Gallery model.
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
                $uploaded = UploadsImage::uploadImage($model, $file, 'gallery');
                if ($uploaded) {
                    $model->image = $uploaded;
                }
            }

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Gallery model.
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
     * Finds the Gallery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Gallery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Gallery::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
