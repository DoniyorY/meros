<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Gallery $model */

$this->title = 'Update Gallery: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Galleries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
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
                            <li class="breadcrumb-item">
                                <a href="<?= Url::to(['view','id'=>$model->id]) ?>">
                                    <?=$model->id?>
                                </a>
                            </li>
                            <li class="breadcrumb-item active"><?= \yii\helpers\Html::encode($this->title) ?></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="gallery-update">

            <h1><?= Html::encode($this->title) ?></h1>
           
           <?= $this->render('_form', [
              'model' => $model,
           ]) ?>

        </div>
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->

