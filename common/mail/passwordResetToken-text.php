<?php

/** @var yii\web\View $this */
/** @var common\models\User $user */

$lang = substr((string) Yii::$app->language, 0, 2);
$messages = [
    'ru' => [
        'title' => 'Восстановление доступа к аккаунту',
        'hello' => 'Здравствуйте, {name}!',
        'intro' => 'Мы получили запрос на сброс пароля. Перейдите по ссылке ниже, чтобы создать новый пароль и продолжить обучение.',
        'button' => 'Ссылка для сброса пароля:',
        'note' => 'Если вы не запрашивали сброс пароля, просто проигнорируйте это письмо — ваш текущий пароль останется без изменений.',
        'footer' => 'Meros International Institute — медицинский английский, OET и IELTS для специалистов здравоохранения.',
    ],
    'uz' => [
        'title' => 'Akkauntga kirishni tiklash',
        'hello' => 'Assalomu alaykum, {name}!',
        'intro' => 'Parolni tiklash bo‘yicha so‘rov oldik. Yangi parol yaratish va o‘qishni davom ettirish uchun quyidagi havolaga o‘ting.',
        'button' => 'Parolni tiklash havolasi:',
        'note' => 'Agar parolni tiklashni so‘ramagan bo‘lsangiz, bu xatni e’tiborsiz qoldiring — joriy parolingiz o‘zgarmaydi.',
        'footer' => 'Meros International Institute — tibbiyot ingliz tili, OET va IELTS sog‘liqni saqlash mutaxassislari uchun.',
    ],
    'en' => [
        'title' => 'Restore access to your account',
        'hello' => 'Hello, {name}!',
        'intro' => 'We received a request to reset your password. Follow the link below to create a new password and continue learning.',
        'button' => 'Password reset link:',
        'note' => 'If you did not request a password reset, you can safely ignore this email — your current password will remain unchanged.',
        'footer' => 'Meros International Institute — Medical English, OET and IELTS for healthcare professionals.',
    ],
];
$t = $messages[$lang] ?? $messages['en'];
$name = trim((string) $user->fullname) ?: ($user->username ?: $user->email);
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<?= $t['title'] ?>

<?= strtr($t['hello'], ['{name}' => $name]) ?>

<?= $t['intro'] ?>

<?= $t['button'] ?>
<?= $resetLink ?>

<?= $t['note'] ?>

<?= $t['footer'] ?>
