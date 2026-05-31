<?php

use common\models\Banner;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\BannerSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Banners';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-index">
    <div class="row">
        <div class="col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-4">
            <?= Html::a('Create Banner', ['create'], ['class' => 'btn btn-success w-100']) ?>
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
            //'image',
            //'link',
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
                        return Html::a('Inactive', ['status', 'id' => $data->id, 'status' => 1],
                            [
                                'class' => 'btn btn-warning w-100',
                                'data' => [
                                    'confirm' => 'Are you sure you want to activate this item?',
                                ]
                            ]);
                    } else {
                        return Html::a('Active', ['status', 'id' => $data->id, 'status' => 0],
                            [
                                'class' => 'btn btn-success w-100',
                                'data' => [
                                    'confirm' => 'Are you sure you want to Inactivate this item?',
                                ]
                            ]);
                    }
                },
                'format' => 'raw',
                'filter' => Yii::$app->params['status']
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Banner $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
