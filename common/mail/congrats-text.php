<?php

/** @var yii\web\View $this */
/** @var common\models\User $user */
/** @var common\models\Billing $billing */

$lang = substr((string) Yii::$app->language, 0, 2);
$campusLink = 'https://slc-campus.avallainmagnet.com/merosinternationalinstitute';
$messages = [
    'ru' => [
        'title' => 'Поздравляем с покупкой!',
        'hello' => 'Уважаемый(ая) {name},',
        'intro' => 'Поздравляем вас с покупкой и благодарим за доверие к Meros International Institute. Вы сделали важный шаг к развитию профессионального английского для медицины, OET и IELTS.',
        'salt' => 'Пусть это обучение станет для вас не просто курсом, а уверенным маршрутом к новым возможностям: больше практики, ясные цели и поддержка команды на каждом этапе.',
        'button' => 'Чтобы получить доступ к курсам, перейдите по ссылке:',
        'note' => 'Используйте ваш аккаунт Meros International Institute для входа на платформу обучения.',
        'footer' => 'Meros International Institute — медицинский английский, OET и IELTS для специалистов здравоохранения.',
    ],
    'uz' => [
        'title' => 'Xaridingiz bilan tabriklaymiz!',
        'hello' => 'Hurmatli {name},',
        'intro' => 'Meros International Institute’ni tanlaganingiz uchun rahmat va xaridingiz bilan tabriklaymiz. Siz tibbiyot ingliz tili, OET va IELTS bo‘yicha kasbiy rivojlanish sari muhim qadam tashladingiz.',
        'salt' => 'Ushbu ta’lim siz uchun oddiy kurs emas, balki yangi imkoniyatlarga olib boradigan ishonchli yo‘l bo‘lsin: ko‘proq amaliyot, aniq maqsadlar va har bosqichda jamoamiz ko‘magi.',
        'button' => 'Kurslarga kirish uchun quyidagi havolaga o‘ting:',
        'note' => 'Ta’lim platformasiga kirish uchun Meros International Institute akkauntingizdan foydalaning.',
        'footer' => 'Meros International Institute — tibbiyot ingliz tili, OET va IELTS sog‘liqni saqlash mutaxassislari uchun.',
    ],
    'en' => [
        'title' => 'Congratulations on your purchase!',
        'hello' => 'Dear {name},',
        'intro' => 'Congratulations on your purchase, and thank you for choosing Meros International Institute. You have taken an important step toward developing professional English for medicine, OET and IELTS.',
        'salt' => 'May this learning journey become more than a course: a confident path toward new opportunities, with practical training, clear goals and our team’s support at every stage.',
        'button' => 'To access your courses, follow this link:',
        'note' => 'Use your Meros International Institute account to sign in to the learning platform.',
        'footer' => 'Meros International Institute — Medical English, OET and IELTS for healthcare professionals.',
    ],
];
$t = $messages[$lang] ?? $messages['en'];
$name = trim((string) $user->fullname) ?: ($user->username ?: $user->email);
?>
<?= $t['title'] ?>

<?= strtr($t['hello'], ['{name}' => $name]) ?>

<?= $t['intro'] ?>

<?= $t['salt'] ?>

<?= $t['button'] ?>
<?= $campusLink ?>

<?= $t['note'] ?>

<?= $t['footer'] ?>
