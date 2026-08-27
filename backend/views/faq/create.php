<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Faq $model */
/** @var common\models\Faq[] $models */

$this->title = 'Create FAQ';
?>
<div class="page-content"><div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
        <h4 class="mb-sm-0"><?= Html::encode($this->title) ?></h4>
        <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="<?= Yii::$app->homeUrl ?>"><?= Html::encode(Yii::$app->name) ?></a></li><li class="breadcrumb-item"><a href="<?= Url::to(['index']) ?>">FAQ</a></li><li class="breadcrumb-item active">Create</li></ol>
    </div>
    <div class="mb-4"><h2 class="mb-1">Create frequently asked questions</h2><p class="text-muted mb-0">Add one or several translated questions to the selected course.</p></div>
    <?= $this->render('_form', ['model' => $model, 'models' => $models]) ?>
</div></div>
