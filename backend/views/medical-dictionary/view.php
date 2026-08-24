<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\MedicalDictionary $model */

$this->title = $model->name_en;
\yii\web\YiiAsset::register($this);
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
                            <li class="breadcrumb-item"><a href="<?=\yii\helpers\Url::to(['index'])?>"><?="Medical Dictionary"?></a></li>
                            <li class="breadcrumb-item active"><?=Html::encode($this->title)?></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        
        <div class="medical-dictionary-view">

            <h1><?= Html::encode($this->title) ?></h1>

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
                 'type',
                 'name_ru',
                 'name_en',
                 'name_uz',
                 'slug_ru',
                 'slug_en',
                 'slug_uz',
                 'desc_ru',
                 'desc_en',
                 'desc_uz',
                 'content_ru:html',
                 'content_en:html',
                 'content_uz:html',
                 'seo_title_ru',
                 'seo_title_en',
                 'seo_title_uz',
                 'seo_desc_ru',
                 'seo_desc_en',
                 'seo_desc_uz',
                 'created_at:datetime',
                 'updated_at:datetime',
                 'status',
              ],
           ]) ?>

        </div>
    </div>
</div>

