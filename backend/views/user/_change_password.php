<?php
/**
 * @var \common\models\ChangePass $changePass
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin([
   'action' => ['change-pass', 'id' => $model->id],
   'method' => 'post',
]); ?>

<div class="row g-2">
   
   <div class="col-lg-4">
      <?= $form->field($changePass, 'old_password')
         ->passwordInput([
            'placeholder' => 'Enter current password',
         ]) ?>
   </div>
   
   <div class="col-lg-4">
      <?= $form->field($changePass, 'new_password')
         ->passwordInput([
            'placeholder' => 'Enter new password',
         ]) ?>
   </div>
   
   <div class="col-lg-4">
      <?= $form->field($changePass, 'confirm_password')
         ->passwordInput([
            'placeholder' => 'Confirm password',
         ]) ?>
   </div>
   
   <div class="col-lg-12">
      <div class="mb-3">
         <?= Html::a(
            'Reset Password!!',
            ['reset-password', 'id' => $model->id],
            [
               'class' => 'link-primary text-decoration-underline',
               'data-confirm' => 'Are you sure that you want to reset your password?',
               'data-method' => 'post',
            ]
         ) ?>
      </div>
   </div>
   
   <div class="col-lg-12">
      <div class="text-end">
         <?= Html::submitButton('Change Password', [
            'class' => 'btn btn-primary',
         ]) ?>
      </div>
   </div>

</div>

<?php ActiveForm::end(); ?>
