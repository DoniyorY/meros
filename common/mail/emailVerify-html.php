<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token, 'rer' => Yii::$app->request->referrer]);
?>
<div class="verify-email">
    <p>Hello <?= Html::encode($user->fullname) ?>,</p>

    <p>Thank you for your registration. Follow the link below to verify your email:</p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
</div>
