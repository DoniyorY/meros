<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\User $user */
/** @var common\models\Billing $billing */

$lang = substr((string) Yii::$app->language, 0, 2);
$campusLink = 'https://slc-campus.avallainmagnet.com/merosinternationalinstitute';
$messages = [
    'ru' => [
        'preheader' => 'Поздравляем с покупкой в Meros International Institute — доступ к курсам уже ждет вас.',
        'badge' => 'Покупка оформлена',
        'title' => 'Поздравляем с покупкой!',
        'hello' => 'Уважаемый(ая) {name},',
        'intro' => 'Поздравляем вас с покупкой и благодарим за доверие к Meros International Institute. Вы сделали важный шаг к развитию профессионального английского для медицины, OET и IELTS.',
        'salt' => 'Пусть это обучение станет для вас не просто курсом, а уверенным маршрутом к новым возможностям: больше практики, ясные цели и поддержка команды на каждом этапе.',
        'button' => 'Перейти к курсам',
        'fallback' => 'Если кнопка не открывается, скопируйте и вставьте эту ссылку в браузер:',
        'note' => 'Используйте ваш аккаунт Meros International Institute для входа на платформу обучения.',
        'support' => 'Если появятся вопросы по доступу или обучению, ответьте на это письмо — мы поможем.',
        'footer' => 'Meros International Institute — медицинский английский, OET и IELTS для специалистов здравоохранения.',
    ],
    'uz' => [
        'preheader' => 'Meros International Institute xaridingiz bilan tabriklaymiz — kurslarga kirish havolasi tayyor.',
        'badge' => 'Xarid tasdiqlandi',
        'title' => 'Xaridingiz bilan tabriklaymiz!',
        'hello' => 'Hurmatli {name},',
        'intro' => 'Meros International Institute’ni tanlaganingiz uchun rahmat va xaridingiz bilan tabriklaymiz. Siz tibbiyot ingliz tili, OET va IELTS bo‘yicha kasbiy rivojlanish sari muhim qadam tashladingiz.',
        'salt' => 'Ushbu ta’lim siz uchun oddiy kurs emas, balki yangi imkoniyatlarga olib boradigan ishonchli yo‘l bo‘lsin: ko‘proq amaliyot, aniq maqsadlar va har bosqichda jamoamiz ko‘magi.',
        'button' => 'Kurslarga o‘tish',
        'fallback' => 'Agar tugma ochilmasa, ushbu havolani brauzerga nusxa ko‘chirib joylashtiring:',
        'note' => 'Ta’lim platformasiga kirish uchun Meros International Institute akkauntingizdan foydalaning.',
        'support' => 'Kirish yoki o‘qish bo‘yicha savollar tug‘ilsa, ushbu xatga javob yozing — biz yordam beramiz.',
        'footer' => 'Meros International Institute — tibbiyot ingliz tili, OET va IELTS sog‘liqni saqlash mutaxassislari uchun.',
    ],
    'en' => [
        'preheader' => 'Congratulations on your Meros International Institute purchase — your course access is ready.',
        'badge' => 'Purchase confirmed',
        'title' => 'Congratulations on your purchase!',
        'hello' => 'Dear {name},',
        'intro' => 'Congratulations on your purchase, and thank you for choosing Meros International Institute. You have taken an important step toward developing professional English for medicine, OET and IELTS.',
        'salt' => 'May this learning journey become more than a course: a confident path toward new opportunities, with practical training, clear goals and our team’s support at every stage.',
        'button' => 'Go to courses',
        'fallback' => 'If the button does not work, copy and paste this link into your browser:',
        'note' => 'Use your Meros International Institute account to sign in to the learning platform.',
        'support' => 'If you have any questions about access or learning, reply to this email and we will help.',
        'footer' => 'Meros International Institute — Medical English, OET and IELTS for healthcare professionals.',
    ],
];
$t = $messages[$lang] ?? $messages['en'];
$name = trim((string) $user->fullname) ?: ($user->username ?: $user->email);
$logoUrl = Url::to('/logo-white.png', true);
?>
<div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;line-height:1px;">
    <?= Html::encode($t['preheader']) ?>
</div>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:0;padding:0;background:#f3f6fb;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <tr>
        <td align="center" style="padding:32px 16px;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:640px;background:#ffffff;border-radius:24px;overflow:hidden;box-shadow:0 16px 40px rgba(15,23,42,.10);">
                <tr>
                    <td style="padding:28px 32px;background:linear-gradient(135deg,#0f3d5e 0%,#1477a8 52%,#22b8cf 100%);">
                        <img src="<?= Html::encode($logoUrl) ?>" width="150" alt="Meros International Institute" style="display:block;max-width:150px;height:auto;margin-bottom:28px;">
                        <span style="display:inline-block;padding:8px 14px;border-radius:999px;background:rgba(255,255,255,.16);color:#ffffff;font-size:13px;font-weight:700;letter-spacing:.02em;">
                            <?= Html::encode($t['badge']) ?>
                        </span>
                        <h1 style="margin:18px 0 0;font-size:30px;line-height:1.22;color:#ffffff;font-weight:800;">
                            <?= Html::encode($t['title']) ?>
                        </h1>
                    </td>
                </tr>
                <tr>
                    <td style="padding:34px 32px 10px;">
                        <p style="margin:0 0 18px;font-size:18px;line-height:1.6;font-weight:700;color:#0f172a;">
                            <?= Html::encode(strtr($t['hello'], ['{name}' => $name])) ?>
                        </p>
                        <p style="margin:0 0 16px;font-size:16px;line-height:1.75;color:#475569;">
                            <?= Html::encode($t['intro']) ?>
                        </p>
                        <p style="margin:0;font-size:16px;line-height:1.75;color:#475569;">
                            <?= Html::encode($t['salt']) ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding:26px 32px;">
                        <a href="<?= Html::encode($campusLink) ?>" style="display:inline-block;background:#0f78a8;color:#ffffff;text-decoration:none;border-radius:14px;padding:16px 30px;font-size:16px;font-weight:800;box-shadow:0 10px 22px rgba(15,120,168,.28);">
                            <?= Html::encode($t['button']) ?>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 32px 28px;">
                        <div style="padding:18px;border-radius:16px;background:#f8fafc;border:1px solid #e2e8f0;">
                            <p style="margin:0 0 10px;font-size:13px;line-height:1.6;color:#64748b;">
                                <?= Html::encode($t['fallback']) ?>
                            </p>
                            <a href="<?= Html::encode($campusLink) ?>" style="font-size:13px;line-height:1.6;color:#0f78a8;word-break:break-all;text-decoration:underline;">
                                <?= Html::encode($campusLink) ?>
                            </a>
                        </div>
                        <p style="margin:18px 0 0;font-size:14px;line-height:1.7;color:#64748b;">
                            <?= Html::encode($t['note']) ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:22px 32px;background:#0f172a;">
                        <p style="margin:0 0 8px;font-size:14px;line-height:1.6;color:#cbd5e1;">
                            <?= Html::encode($t['support']) ?>
                        </p>
                        <p style="margin:0;font-size:12px;line-height:1.6;color:#94a3b8;">
                            <?= Html::encode($t['footer']) ?>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
