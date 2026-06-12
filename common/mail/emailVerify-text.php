<?php

/** @var yii\web\View $this */
/** @var common\models\User $user */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token, 'rer' => Yii::$app->request->referrer]);
?>
Hello <?= $user->fullname ?>,

Follow the link below to verify your email:

<?= $verifyLink ?>
