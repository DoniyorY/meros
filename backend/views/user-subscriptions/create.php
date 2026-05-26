<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\UserSubscriptions $model */

$this->title = 'Create User Subscriptions';
$this->params['breadcrumbs'][] = ['label' => 'User Subscriptions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-subscriptions-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
