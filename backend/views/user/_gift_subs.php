<?php

use common\models\Billing;
use common\models\SubscriptionPlans;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var common\models\User $model
 */
$billing = new Billing();
?>

<div class="row">
    <div class="col text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Gift Subscription
        </button>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Gift Subscription</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
           <?php $form = ActiveForm::begin(['action' => Url::to(['user/gift-subscription']),'method' => 'post']); ?>
            <div class="modal-body">
               <?= Html::activeHiddenInput($billing, 'user_id',['value'=>$model->id]) ?>
               <?= $form->field($billing, 'subscription_id')->widget(Select2::classname(), [
                  'data' => ArrayHelper::map(SubscriptionPlans::find()->where(['status' => 1])->orderBy(['id' => SORT_ASC])->all(), 'id', 'name_en', 'courseName'),
                  'language' => 'en',
                  'options' => ['placeholder' => 'Select Subscription Plans'],
                  'pluginOptions' => [
                     'allowClear' => true,
                     'dropdownParent'=>'#exampleModal',
                  ],
               ]) ?>
            </div>
            <div class="modal-footer">
               <?= Html::button('Close', ['class' => 'btn btn-secondary', 'data-bs-dismiss' => 'modal']) ?>
               <?= Html::submitButton('Save', ['class' => 'btn btn-primary','data-confirm'=>'Are you sure you want to save?']) ?>
            </div>
           <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>