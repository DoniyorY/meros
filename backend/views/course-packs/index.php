<?php

use common\models\CoursePacks;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\CoursePacksSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
$params = Yii::$app->params;
$this->title = 'Course Packs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-packs-index">
    <div class="row">
        <div class="col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-4">
            <button type="button" class="btn btn-success w-100" data-bs-toggle="modal"
                    data-bs-target="#createPackModal">
                Create Course Pack
            </button>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="createPackModal" tabindex="-1" aria-labelledby="createPackModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createPackModalLabel">New Category</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?= $this->render('_form', ['model' => new CoursePacks()]) ?>
                </div>
            </div>
        </div>
    </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name_ru',
            'name_en',
            'name_uz',
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
                },
                'filter' => ArrayHelper::map(User::find()->leftJoin('auth_assignment', 'auth_assignment.user_id=user.id')->where(['auth_assignment.item_name' => 'admin'])->all(), 'id', 'username')
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
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, CoursePacks $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'template'=>'{view}'
            ],
        ],
    ]); ?>


</div>
