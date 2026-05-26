<?php

use common\models\Mentors;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\MentorsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
$params = Yii::$app->params;
$this->title = 'Mentors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mentors-index">
    <div class="row">
        <div class="col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-4">
            <?= Html::a('Create Mentors', ['create'], ['class' => 'btn btn-success w-100']) ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'fullname',
            'email:email',
            'phone',
            'image',
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
                'attribute' => 'user_id',
                'value' => function ($data) {
                    return $data->user->username;
                }
            ],
            'instagram_link',
            'linked_in_link',
            'facebook_link',
            [
                'attribute' => 'status',
                'value' => function ($data) {
                    if ($data->status == 0) {
                        return Html::a('Inactive', ['status', 'id' => $data->id, 'status' => 1], ['class' => 'btn w-100 btn-sm btn-warning', 'data' => [
                            'confirm' => 'Are you sure you want to inactivate this subscription plan?',
                        ]]);
                    } else {
                        return Html::a('Active', ['status', 'id' => $data->id, 'status' => 0], ['class' => 'btn w-100 btn-sm btn-success', 'data' => [
                            'confirm' => 'Are you sure you want to activate this subscription plan?',
                        ]]);
                    }
                },
                'format' => 'raw',
                'filter' => $params['status'],
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Mentors $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'template'=>'{view}'
            ],
        ],
    ]); ?>
</div>