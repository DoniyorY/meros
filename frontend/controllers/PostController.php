<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\models\Posts;

class PostController extends Controller
{

    public function actionIndex()
    {
        $model = Posts::findAll(['status' => 1]);
        return $this->render('index', ['model' => $model]);
    }

    public function actionView($id)
    {
        $model = Posts::findOne($id);
        $related = Posts::find()
            ->where(['status' => 1])
            ->andWhere(['category_id' => $model->category_id])
            ->andWhere(['<>', 'id', $model->id])
            ->all();
        return $this->render('view', ['model' => $model, 'related' => $related]);
    }
}