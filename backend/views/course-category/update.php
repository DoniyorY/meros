<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\CourseCategory $model */

$this->title = 'Update Course Category: ' . $model->name_en;
$this->params['breadcrumbs'][] = ['label' => 'Course Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
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
                            <li class="breadcrumb-item"><a href="<?=Url::to(['index'])?>">Course Categories</a></li>
                            <li class="breadcrumb-item"><a href="<?=Url::to(['view','id'=>$model->id])?>"><?=Html::encode($model->name_en)?></a></li>
                            <li class="breadcrumb-item active"><?=Html::encode($this->title)?></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="course-category-update">
            <h1><?= Html::encode($this->title) ?></h1>
           <?= $this->render('_form', [
              'model' => $model,
           ]) ?>
        </div>
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->