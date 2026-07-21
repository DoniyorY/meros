<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Posts $model */

$this->title = $model->name_en;
\yii\web\YiiAsset::register($this);
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
                            <li class="breadcrumb-item">
                                <a href="<?= Url::to(['index']) ?>"><?= "News" ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= \yii\helpers\Html::encode($this->title) ?></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="posts-view">
            <p>
               <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
               <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                  'class' => 'btn btn-danger',
                  'data' => [
                     'confirm' => 'Are you sure you want to delete this item?',
                     'method' => 'post',
                  ],
               ]) ?>
            </p>
           
           <?= DetailView::widget([
              'model' => $model,
              'attributes' => [
                 'id',
                 'category_id',
                 'name_ru',
                 'name_en',
                 'name_uz',
                 'desc_ru:ntext',
                 'desc_en:ntext',
                 'desc_uz:ntext',
                 'content_ru:html',
                 'content_en:html',
                 'content_uz:html',
                 'created_at',
                 'updated_at',
                 'status',
                 'user_id',
                 'image',
              ],
           ]) ?>

        </div>
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->

