<?php

use common\models\MedicalDictionary;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\MedicalDictionarySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Medical Dictionaries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
                    <h4 class="mb-sm-0"><?= Html::encode($this->title) ?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= Yii::$app->homeUrl ?>"><?= Yii::$app->name ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= Html::encode($this->title) ?></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="medical-dictionary-index">

            <h1><?= Html::encode($this->title) ?></h1>

            <p>
               <?= Html::a('Create Medical Dictionary', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
           
           <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
           
           <?= GridView::widget([
              'dataProvider' => $dataProvider,
              'filterModel' => $searchModel,
              'pager'=>Yii::$app->params['pager'],
              'columns' => [
                 ['class' => 'yii\grid\SerialColumn'],
                 
                 //'id',
                 [
                    'attribute' => 'category_id',
                    'value' => function ($data) {
                       return Yii::$app->params['medical_dictionary_categories']['en'][$data->category_id] ?? 'Not Set';
                    },
                    'filter'=>Yii::$app->params['medical_dictionary_categories']['en'],
                 ],
                 [
                    'attribute' => 'type',
                    'value' => function ($data) {
                       return Yii::$app->params['medical_dictionary_types']['en'][$data->type] ?? 'Not Set';
                    },
                    'filter'=>Yii::$app->params['medical_dictionary_types']['en'],
                 ],
                 //'name_ru',
                 'name_en',
                 //'name_uz',
                 //'slug_ru',
                 //'slug_en',
                 //'slug_uz',
                 //'desc_ru',
                 'desc_en',
                 //'desc_uz',
                 //'content_ru:ntext',
                 //'content_en:ntext',
                 //'content_uz:ntext',
                 //'seo_title_ru',
                 //'seo_title_en',
                 //'seo_title_uz',
                 //'seo_desc_ru',
                 //'seo_desc_en',
                 //'seo_desc_uz',
                 'created_at:datetime',
                 //'updated_at',
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
                    'filter' => Yii::$app->params['status'],
                 ],
                 [
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, MedicalDictionary $model, $key, $index, $column) {
                       return Url::toRoute([$action, 'id' => $model->id]);
                    },
                    'template' => '{view} {update}'
                 ],
              ],
           ]); ?>


        </div>
    </div>
</div>