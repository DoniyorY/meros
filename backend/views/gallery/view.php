<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Gallery $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Galleries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
                                <a href="<?= Url::to(['index']) ?>">
                                    Galleries
                                </a>
                            </li>
                            <li class="breadcrumb-item active"><?= \yii\helpers\Html::encode($this->title) ?></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="gallery-view">

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
                 'page_id',
                 'image',
                 'created_at',
                 'updated_at',
                 'user_id',
                 'status',
              ],
           ]) ?>

        </div>
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->
