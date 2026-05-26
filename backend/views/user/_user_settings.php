<?php
/**
 * @var $model \common\models\User
 */

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
$currentPermission = \common\models\AuthAssignment::findOne(['user_id' => $model->id]);
?>

<?php $form= ActiveForm::begin(['action'=>['update','id'=>$model->id],'options'=>['enctype'=>'multipart/form-data']])?>

<div class="row">
    <div class="col-4">
        <?=$form->field($model,'fullname')->textInput()?>
    </div>
    <div class="col-4">
        <?=$form->field($model,'email')->textInput()?>
    </div>
    <div class="col-4">
        <?=$form->field($model,'phone')->textInput()?>
    </div>
    <div class="col-4 mt-1">
        <?=$form->field($model,'imageFile')->fileInput()?>
    </div>
    <div class="col-4 mt-1">
        <?=$form->field($model,'address')->textInput()?>
    </div>
    <div class="col-4">
        <div class="form-group">
            <label for="permissions">User Permissions</label>
            <?=\yii\helpers\Html::dropDownList('User[permission]',$currentPermission,ArrayHelper::map(\common\models\AuthItem::find()->all(),'name','name'),['class'=>'form-control','prompt'=>'Select permission','id'=>'permission'])?>
        </div>
    </div>
    <div class="col-12 mt-4">
        <?=\yii\helpers\Html::submitButton('Update',['class'=>'btn btn-success w-100'])?>
    </div>
</div>

<?php ActiveForm::end()?>