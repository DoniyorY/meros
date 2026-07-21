<?php

use common\models\Gallery;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\GallerySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Galleries';
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
        <div class="gallery-index">

            <p>
               <?= Html::a('Create Gallery', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
           
           <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
           
           <?= GridView::widget([
              'dataProvider' => $dataProvider,
              'filterModel' => $searchModel,
              'columns' => [
                 ['class' => 'yii\grid\SerialColumn'],
                 
                 'id',
                 'page_id',
                 'image',
                 'created_at',
                 'updated_at',
                 //'user_id',
                 //'status',
                 [
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, Gallery $model, $key, $index, $column) {
                       return Url::toRoute([$action, 'id' => $model->id]);
                    }
                 ],
              ],
           ]); ?>


        </div>
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->

