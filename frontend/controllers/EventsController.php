<?php

namespace frontend\controllers;

use common\models\Events;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class EventsController extends Controller
{
    public function actionIndex()
    {
        $events = Events::find()
            ->where(['status' => 1])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('index', ['events' => $events]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $related = Events::find()
            ->where(['status' => 1])
            ->andWhere(['<>', 'id', $model->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(4)
            ->all();

        return $this->render('view', ['model' => $model, 'related' => $related]);
    }

    protected function findModel($id)
    {
        if (($model = Events::findOne(['id' => $id, 'status' => 1])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested event does not exist.');
    }
}
