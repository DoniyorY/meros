<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\User $user */

$lang = substr((string) Yii::$app->language, 0, 2);
$messages = [
    'ru' => [
        'preheader' => 'Сбросьте пароль для вашего аккаунта Meros International Institute.',
        'badge' => 'Сброс пароля',
        'title' => 'Восстановление доступа к аккаунту',
        'hello' => 'Здравствуйте, {name}!',
        'intro' => 'Мы получили запрос на сброс пароля. Нажмите кнопку ниже, чтобы создать новый пароль и продолжить обучение.',
        'button' => 'Сбросить пароль',
        'fallback' => 'Если кнопка не открывается, скопируйте и вставьте эту ссылку в браузер:',
        'note' => 'Если вы не запрашивали сброс пароля, просто проигнорируйте это письмо — ваш текущий пароль останется без изменений.',
        'support' => 'Нужна помощь? Ответьте на это письмо, и наша команда вам поможет.',
        'footer' => 'Meros International Institute — медицинский английский, OET и IELTS для специалистов здравоохранения.',
    ],
    'uz' => [
        'preheader' => 'Meros International Institute akkauntingiz parolini tiklang.',
        'badge' => 'Parolni tiklash',
        'title' => 'Akkauntga kirishni tiklash',
        'hello' => 'Assalomu alaykum, {name}!',
        'intro' => 'Parolni tiklash bo‘yicha so‘rov oldik. Yangi parol yaratish va o‘qishni davom ettirish uchun quyidagi tugmani bosing.',
        'button' => 'Parolni tiklash',
        'fallback' => 'Agar tugma ochilmasa, ushbu havolani brauzerga nusxa ko‘chirib joylashtiring:',
        'note' => 'Agar parolni tiklashni so‘ramagan bo‘lsangiz, bu xatni e’tiborsiz qoldiring — joriy parolingiz o‘zgarmaydi.',
        'support' => 'Yordam kerakmi? Ushbu xatga javob yozing — jamoamiz sizga yordam beradi.',
        'footer' => 'Meros International Institute — tibbiyot ingliz tili, OET va IELTS sog‘liqni saqlash mutaxassislari uchun.',
    ],
    'en' => [
        'preheader' => 'Reset the password for your Meros International Institute account.',
        'badge' => 'Password reset',
        'title' => 'Restore access to your account',
        'hello' => 'Hello, {name}!',
        'intro' => 'We received a request to reset your password. Click the button below to create a new password and continue learning.',
        'button' => 'Reset password',
        'fallback' => 'If the button does not work, copy and paste this link into your browser:',
        'note' => 'If you did not request a password reset, you can safely ignore this email — your current password will remain unchanged.',
        'support' => 'Need help? Reply to this email and our team will assist you.',
        'footer' => 'Meros International Institute — Medical English, OET and IELTS for healthcare professionals.',
    ],
];
$t = $messages[$lang] ?? $messages['en'];
$name = trim((string) $user->fullname) ?: ($user->username ?: $user->email);
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
$logoUrl = Url::to('/logo.png', true);
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
                        <p style="margin:0;font-size:16px;line-height:1.75;color:#475569;">
                            <?= Html::encode($t['intro']) ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding:26px 32px;">
                        <a href="<?= Html::encode($resetLink) ?>" style="display:inline-block;background:#0f78a8;color:#ffffff;text-decoration:none;border-radius:14px;padding:16px 30px;font-size:16px;font-weight:800;box-shadow:0 10px 22px rgba(15,120,168,.28);">
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
                            <a href="<?= Html::encode($resetLink) ?>" style="font-size:13px;line-height:1.6;color:#0f78a8;word-break:break-all;text-decoration:underline;">
                                <?= Html::encode($resetLink) ?>
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
