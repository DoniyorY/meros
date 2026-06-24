<?php

namespace backend\controllers;

use common\models\Faq;
use common\models\search\FaqSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FaqController implements the CRUD actions for Faq model.
 */
class FaqController extends Controller
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
     * Lists all Faq models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new FaqSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Faq model.
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
     * Creates one or more new Faq models with the same course and page.
     * If creation is successful, the browser will be redirected to the index page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Faq();

        if ($this->request->isPost) {
            $post = $this->request->post('Faq', []);
            $model->course_id = $post['course_id'] ?? null;
            $model->page_id = $post['page_id'] ?? null;

            $rows = $this->buildFaqRows($post);
            $models = [];
            $hasError = empty($rows);
            $transaction = Yii::$app->db->beginTransaction();

            try {
                foreach ($rows as $row) {
                    $faq = new Faq();
                    $faq->course_id = $model->course_id;
                    $faq->page_id = $model->page_id;
                    $faq->question_ru = $row['question_ru'] ?? null;
                    $faq->question_en = $row['question_en'] ?? null;
                    $faq->question_uz = $row['question_uz'] ?? null;
                    $faq->answer_ru = $row['answer_ru'] ?? null;
                    $faq->answer_en = $row['answer_en'] ?? null;
                    $faq->answer_uz = $row['answer_uz'] ?? null;
                    $faq->created_at = time();
                    $faq->updated_at = time();
                    $faq->user_id = Yii::$app->user->id;

                    if (!$faq->save()) {
                        $hasError = true;
                    }

                    $models[] = $faq;
                }

                if (!$hasError) {
                    $transaction->commit();
                    return $this->redirect(['index']);
                }

                $transaction->rollBack();
                $model->addError('question_en', 'Fill at least one FAQ row and check required fields.');
                foreach ($models as $faq) {
                    foreach ($faq->getErrors() as $attribute => $errors) {
                        foreach ($errors as $error) {
                            $model->addError($attribute, $error);
                        }
                    }
                }
            } catch (\Throwable $exception) {
                $transaction->rollBack();
                throw $exception;
            }
        } else {
            $model->loadDefaultValues();
            $models = [new Faq()];
        }

        return $this->render('create', [
            'model' => $model,
            'models' => $models ?? [new Faq()],
        ]);
    }

    /**
     * Updates an existing Faq model.
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
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Faq model.
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
     * Converts tabular FAQ form fields to row arrays and skips completely empty rows.
     *
     * @param array $post
     * @return array
     */
    private function buildFaqRows(array $post): array
    {
        $attributes = ['question_ru', 'question_en', 'question_uz', 'answer_ru', 'answer_en', 'answer_uz'];
        $count = 0;

        foreach ($attributes as $attribute) {
            if (isset($post[$attribute]) && is_array($post[$attribute])) {
                $count = max($count, count($post[$attribute]));
            }
        }

        $rows = [];
        for ($index = 0; $index < $count; $index++) {
            $row = [];
            $hasValue = false;

            foreach ($attributes as $attribute) {
                $value = $post[$attribute][$index] ?? null;
                $value = is_string($value) ? trim($value) : $value;
                $row[$attribute] = $value;

                if ($value !== null && $value !== '') {
                    $hasValue = true;
                }
            }

            if ($hasValue) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    /**
     * Finds the Faq model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Faq the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Faq::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
