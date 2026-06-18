<?php

use common\models\Events;
use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\search\EventsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Events';
$this->params['breadcrumbs'][] = $this->title;
$params = Yii::$app->params;
?>
<div class="events-index">
    <div class="row">
        <div class="col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-4">
           <?= Html::a('Create Events', ['create'], ['class' => 'btn btn-success w-100']) ?>
        </div>
    </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'name_ru',
            'name_en',
            //'name_uz',
            //'desc_ru',
            'desc_en',
            //'desc_uz',
            //'content_ru:ntext',
            //'content_en:ntext',
            //'content_uz:ntext',
            [
                'attribute' => 'image',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->image ? Html::img(Yii::getAlias('@web') . '/../uploads/events/' . $data->image, [
                        'style' => 'max-width: 90px; max-height: 70px;',
                        'class' => 'img-thumbnail',
                        'alt' => $data->name_en,
                    ]) : null;
                },
            ],
            //'created_at',
           [
              'attribute' => 'updated_at',
              'value' => function ($data) {
                 return date('d.m.Y H:i:s', $data->updated_at);
              }
           ],
           [
              'attribute' => 'status',
              'value' => function ($data) {
                 if ($data->status == 0) {
                    return Html::a('Inactive', ['status', 'id' => $data->id, 'status' => 1], ['class' => 'btn w-100 btn-sm btn-warning', 'data' => [
                       'confirm' => 'Are you sure you want to inactivate this subscription plan?',
                       'method' => 'post',
                    ]]);
                 } else {
                    return Html::a('Active', ['status', 'id' => $data->id, 'status' => 0], ['class' => 'btn w-100 btn-sm btn-success', 'data' => [
                       'confirm' => 'Are you sure you want to activate this subscription plan?',
                       'method' => 'post',
                    ]]);
                 }
              },
              'format' => 'raw',
              'filter' => $params['status'],
           ],
           [
              'attribute' => 'user_id',
              'value' => function ($data) {
                 return $data->user ? $data->user->username : null;
              },
              'filter' => ArrayHelper::map(User::find()->all(), 'id', 'username')
           ],
            //'video_link',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Events $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
