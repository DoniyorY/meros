<?php

/** @var yii\web\View $this */
/** @var common\models\User $user */

$lang = substr((string) Yii::$app->language, 0, 2);
$messages = [
    'ru' => [
        'title' => 'Добро пожаловать в Meros International Institute!',
        'hello' => 'Здравствуйте, {name}!',
        'intro' => 'Спасибо за регистрацию. Подтвердите ваш email, чтобы получить доступ к личному кабинету и материалам курса.',
        'button' => 'Ссылка для подтверждения email:',
        'note' => 'Если вы не регистрировались на сайте Meros International Institute, просто проигнорируйте это письмо.',
        'footer' => 'Meros International Institute — медицинский английский, OET и IELTS для специалистов здравоохранения.',
    ],
    'uz' => [
        'title' => 'Meros International Institute’ga xush kelibsiz!',
        'hello' => 'Assalomu alaykum, {name}!',
        'intro' => 'Ro‘yxatdan o‘tganingiz uchun rahmat. Shaxsiy kabinet va kurs materiallariga kirish uchun emailingizni tasdiqlang.',
        'button' => 'Emailni tasdiqlash havolasi:',
        'note' => 'Agar Meros International Institute saytida ro‘yxatdan o‘tmagan bo‘lsangiz, bu xatni e’tiborsiz qoldiring.',
        'footer' => 'Meros International Institute — tibbiyot ingliz tili, OET va IELTS sog‘liqni saqlash mutaxassislari uchun.',
    ],
    'en' => [
        'title' => 'Welcome to Meros International Institute!',
        'hello' => 'Hello, {name}!',
        'intro' => 'Thank you for registering. Confirm your email to access your account and course materials.',
        'button' => 'Email verification link:',
        'note' => 'If you did not register on the Meros International Institute website, you can safely ignore this email.',
        'footer' => 'Meros International Institute — Medical English, OET and IELTS for healthcare professionals.',
    ],
];
$t = $messages[$lang] ?? $messages['en'];
$name = trim((string) $user->fullname) ?: ($user->username ?: $user->email);
$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token, 'rer' => Yii::$app->request->referrer]);
?>
<?= $t['title'] ?>

<?= strtr($t['hello'], ['{name}' => $name]) ?>

<?= $t['intro'] ?>

<?= $t['button'] ?>
<?= $verifyLink ?>

<?= $t['note'] ?>

<?= $t['footer'] ?>
