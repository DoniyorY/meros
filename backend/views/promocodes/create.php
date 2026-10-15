<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PromocodesCreateForm $model */

$this->title = 'Создание промокодов';
$this->params['breadcrumbs'][] = ['label' => 'Промокоды', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">
    <div class="container-fluid promocodes-create">
        <div class="card">
            <div class="card-body">
                <h1><?= Html::encode($this->title) ?></h1>

                <?= $this->render('_form', ['model' => $model]) ?>
            </div>
        </div>
    </div>
</div>
