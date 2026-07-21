<?php

use common\models\Courses;
use common\models\User;
use common\models\CourseCategory;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\CoursesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Courses';
$this->params['breadcrumbs'][] = $this->title;
$params = Yii::$app->params;
?>
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
                    <h4 class="mb-sm-0"><?=Html::encode($this->title)?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?=Yii::$app->homeUrl?>"><?=Yii::$app->name?></a></li>
                            <li class="breadcrumb-item active"><?=Html::encode($this->title)?></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="courses-index">
            <div class="row">
                <div class="col-md-8">
                </div>
                <div class="col-md-4">
                   <?= Html::a('Create Courses', ['create'], ['class' => 'btn btn-success w-100']) ?>
                </div>
            </div>
           <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
           
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
                 
                 //'id',
                 [
                    'attribute' => 'category_id',
                    'value' => function ($data) {
                       if ($data->category) {
                          return $data->category->name_en;
                       }
                    },
                    'filter' => ArrayHelper::map(CourseCategory::find()->all(), 'id', 'name_en'),
                 ],
                 //'slug',
                 //'name_ru',
                 'name_en',
                 //'name_uz',
                 //'desc_ru:ntext',
                 //'desc_en:ntext',
                 //'desc_uz:ntext',
                 //'created_at',
                 [
                    'attribute' => 'updated_at',
                    'value' => function ($data) {
                       return date('d.m.Y H:i:s', $data->updated_at);
                    }
                 ],
                 [
                    'attribute' => 'lvl',
                    'value' => function ($data) {
                       return $data->lvl;
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
                       return $data->user->username;
                    },
                    'filter' => ArrayHelper::map(User::find()->joinWith('assignment')->where(['auth_assignment.item_name'=>'admin'])->all(), 'id', 'username')
                 ],
                 //'mentor_id',
                 //'preview_video_link',
                 [
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, Courses $model, $key, $index, $column) {
                       return Url::toRoute([$action, 'id' => $model->id]);
                    },
                    'template' => '{view} - {update} '
                 ],
              ],
           ]); ?>

        </div>
    </div>
    <!-- container-fluid -->
    
</div>
<!-- End Page-content -->

