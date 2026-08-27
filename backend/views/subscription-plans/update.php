<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\SubscriptionPlans $model */

$this->title = 'Update subscription plan';
?>
<div class="page-content"><div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent"><h4 class="mb-sm-0"><?= Html::encode($this->title) ?></h4><ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="<?= Yii::$app->homeUrl ?>"><?= Html::encode(Yii::$app->name) ?></a></li><li class="breadcrumb-item"><a href="<?= Url::to(['index']) ?>">Subscription plans</a></li><li class="breadcrumb-item"><a href="<?= Url::to(['view', 'id' => $model->id]) ?>"><?= Html::encode($model->name_en) ?></a></li><li class="breadcrumb-item active">Edit</li></ol></div>
    <div class="mb-4"><h2 class="mb-1"><?= Html::encode($model->name_en) ?></h2><p class="text-muted mb-0">Update pricing, access duration and translations.</p></div>
    <?= $this->render('_form', ['model' => $model]) ?>
</div></div>
