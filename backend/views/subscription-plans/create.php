<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\SubscriptionPlans $model */

$this->title = 'Create subscription plan';
?>
<div class="page-content"><div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent"><h4 class="mb-sm-0"><?= Html::encode($this->title) ?></h4><ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="<?= Yii::$app->homeUrl ?>"><?= Html::encode(Yii::$app->name) ?></a></li><li class="breadcrumb-item"><a href="<?= Url::to(['index']) ?>">Subscription plans</a></li><li class="breadcrumb-item active">Create</li></ol></div>
    <div class="mb-4"><h2 class="mb-1">Create a subscription plan</h2><p class="text-muted mb-0">Configure the course, localized name, price and access duration.</p></div>
    <?= $this->render('_form', ['model' => $model]) ?>
</div></div>
