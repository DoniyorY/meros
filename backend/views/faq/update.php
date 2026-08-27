<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Faq $model */

$this->title = 'Update FAQ #' . $model->id;
?>
<div class="page-content"><div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
        <h4 class="mb-sm-0"><?= Html::encode($this->title) ?></h4>
        <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="<?= Yii::$app->homeUrl ?>"><?= Html::encode(Yii::$app->name) ?></a></li><li class="breadcrumb-item"><a href="<?= Url::to(['index']) ?>">FAQ</a></li><li class="breadcrumb-item"><a href="<?= Url::to(['view', 'id' => $model->id]) ?>">#<?= Html::encode($model->id) ?></a></li><li class="breadcrumb-item active">Edit</li></ol>
    </div>
    <div class="mb-4"><h2 class="mb-1">Edit question</h2><p class="text-muted mb-0">Keep the question and its translations clear and consistent.</p></div>
    <?= $this->render('_form', ['model' => $model]) ?>
</div></div>
