<?php

use common\models\Posts;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\PostsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'News';
$this->params['breadcrumbs'][] = $this->title;
$params = Yii::$app->params;
?>
<div class="posts-index">
    <div class="row">
        <div class="col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-4">
            <?= Html::a('Create Posts', ['create'], ['class' => 'btn btn-success w-100']) ?>

        </div>
    </div>
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'category_id',
                'value' => function ($data) {
                    return $data->category->name_en;
                }
            ],
            //'name_ru',
            'name_en',
            //'name_uz',
            //'desc_ru:ntext',
            //'desc_en:ntext',
            //'desc_uz:ntext',
            //'content_ru:ntext',
            //'content_en:ntext',
            //'content_uz:ntext',
            [
                'attribute' => 'created_at',
                'value' => function ($data) {
                    return date('d.m.Y H:i:s', $data->created_at);
                }
            ],
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
            //'user_id',
            //'image',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Posts $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
