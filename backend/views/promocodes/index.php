<?php

use common\models\Promocodes;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\PromocodesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Промокоды';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
                    <h4 class="mb-sm-0"><?= \yii\helpers\Html::encode($this->title) ?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a
                                        href="<?= Yii::$app->homeUrl ?>"><?= Yii::$app->name ?></a></li>
                            <li class="breadcrumb-item active"><?= \yii\helpers\Html::encode($this->title) ?></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
    </div>

    <div class="promocodes-index">
        <div class="card">
            <div class="card-body">
                <?= \common\widgets\Alert::widget() ?>
                <p>
                   <?= Html::a('Создать промокоды', ['create'], ['class' => 'btn btn-success']) ?>
                </p>
               <?= GridView::widget([
                  'dataProvider' => $dataProvider,
                  'filterModel' => $searchModel,
                  'columns' => [
                     ['class' => 'yii\grid\SerialColumn'],
                     
                     'id',
                     'code',
                     ['attribute' => 'discount', 'value' => static function (Promocodes $model) {
                         return $model->discount === null ? null : $model->discount . '%';
                     }],
                     ['attribute' => 'status', 'filter' => Promocodes::statuses(), 'value' => 'statusLabel'],
                     ['attribute' => 'created_at', 'format' => ['datetime', 'php:d.m.Y H:i'], 'filter' => false],
                     ['attribute' => 'updated_at', 'format' => ['datetime', 'php:d.m.Y H:i'], 'filter' => false],
                     'user_id',
                     [
                        'class' => ActionColumn::className(),
                        'template' => '{delete}',
                        'buttons' => [
                            'delete' => static function ($url, Promocodes $model) {
                                return Html::a('Удалить', $url, [
                                    'class' => 'btn btn-sm btn-outline-danger',
                                    'data' => ['method' => 'post', 'confirm' => 'Удалить промокод ' . $model->code . '?'],
                                ]);
                            },
                        ],
                        'urlCreator' => function ($action, Promocodes $model, $key, $index, $column) {
                           return Url::toRoute([$action, 'id' => $model->id]);
                        }
                     ],
                  ],
               ]); ?>
            </div>
        </div>
    </div>
</div>

