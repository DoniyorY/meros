<?php

use common\models\CourseCategory;
use common\widgets\Alert;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\CourseCategorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Course Categories';
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
       <?= Alert::widget() ?>
        <div class="course-category-index">
            <div class="row">
                <div class="col-md-8">
                    <h1><?= Html::encode($this->title) ?></h1>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                        Create Course Category
                    </button>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="createCategoryModalLabel">New Category</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                           <?= $this->render('_form', ['model' => new CourseCategory()]) ?>
                        </div>
                    </div>
                </div>
            </div>
           
           
           <?= GridView::widget([
              'dataProvider' => $dataProvider,
              'filterModel' => $searchModel,
              'columns' => [
                 ['class' => 'yii\grid\SerialColumn'],
                 
                 //'id',
                 //'slug',
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
                 [
                    'attribute' => 'user_id',
                    'value' => function ($data) {
                       return $data->user->username;
                    },
                    'filter'=>ArrayHelper::map(\common\models\User::find()->leftJoin('auth_assignment','user_id=id')->where(['!=','auth_assignment.item_name','guest'])->asArray()->all(), 'id', 'username')
                 ],
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
                    'urlCreator' => function ($action, CourseCategory $model, $key, $index, $column) {
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

