<?php

use common\models\SubscriptionPlans;
use kartik\grid\EditableColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var common\models\search\SubscriptionPlansSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Subscription Plans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
                    <h4 class="mb-sm-0"><?=\yii\helpers\Html::encode($this->title)?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?=Yii::$app->homeUrl?>"><?=Yii::$app->name?></a></li>
                            <li class="breadcrumb-item active"><?=\yii\helpers\Html::encode($this->title)?></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="subscription-plans-index">

            <div class="row">
                <div class="col-md-10">
                    <h1><?= Html::encode($this->title) ?></h1>
                </div>
                <div class="col-md-2">
                   <?= Html::a('Create Subscription Plans', ['create'], ['class' => 'btn w-100 btn-success']) ?>
                </div>
            </div>
           
           <?= GridView::widget([
              'dataProvider' => $dataProvider,
              'filterModel' => $searchModel,
              'pager' => [
                 'prevPageLabel' => '<span class="page-item">Prev</span>',
                 'nextPageLabel' => '<span class="page-item">Next</span>',
                 'disabledPageCssClass' => 'page-link',
                 'activePageCssClass' => 'page-item active',
                 'maxButtonCount' => 5,
                 'linkOptions' => ['class' => 'page-link'],
                 'options' => [
                    'tag' => 'ul',
                    'class' => 'pagination',
                    'style' => 'margin-left: 1px;'
                 ],
              ],
              'columns' => [
                 ['class' => 'yii\grid\SerialColumn'],
                 [
                    'attribute' => 'course_id',
                    'value' => function ($data) {
                       if ($data->course) {
                          return $data->course->name_en;
                       }else{
                          return "Not Set!!!";
                       }
                    },
                    'filter' => \yii\helpers\ArrayHelper::map(\common\models\Courses::find()->all(), 'id', 'name_en','catName'),
                    'format' => 'raw',
                 ],
                 //'id',
                 //'name_ru',
                 'name_en',
                 //'name_uz',
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
                 'price',
                 'duration_days',
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
                 ],
                 [
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, SubscriptionPlans $model, $key, $index, $column) {
                       return Url::toRoute([$action, 'id' => $model->id]);
                    },
                    'template' => '{view}'
                 ],
              ],
           ]); ?>


        </div>
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->

