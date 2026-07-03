<?php

/** @var \common\models\User $user */
/** @var string $code */
/** @var string $language */
/** @var int $expiresMinutes */

use yii\helpers\Html;

$name = trim((string)($user->fullname ?? ''));

$messages = [
   'ru' => [
      'title' => 'Подключение Telegram',
      'hello' => $name !== '' ? 'Здравствуйте, ' . $name . '!' : 'Здравствуйте!',
      'intro' => 'Введите этот код в Telegram-боте Meros International Institute:',
      'expires' => "Код действует {$expiresMinutes} минут.",
      'ignore' => 'Если вы не запрашивали подключение Telegram, просто проигнорируйте это письмо.',
   ],
   'uz' => [
      'title' => 'Telegram’ni ulash',
      'hello' => $name !== '' ? 'Assalomu alaykum, ' . $name . '!' : 'Assalomu alaykum!',
      'intro' => 'Ushbu kodni Meros International Institute Telegram botiga kiriting:',
      'expires' => "Kod {$expiresMinutes} daqiqa amal qiladi.",
      'ignore' => 'Agar Telegram ulanishini so‘ramagan bo‘lsangiz, ushbu xatni e’tiborsiz qoldiring.',
   ],
   'en' => [
      'title' => 'Connect Telegram',
      'hello' => $name !== '' ? 'Hello, ' . $name . '!' : 'Hello!',
      'intro' => 'Enter this code in the Meros International Institute Telegram bot:',
      'expires' => "The code is valid for {$expiresMinutes} minutes.",
      'ignore' => 'If you did not request a Telegram connection, you can ignore this email.',
   ],
];

$message = $messages[$language] ?? $messages['ru'];
?>
<div style="font-family:Arial,sans-serif;max-width:620px;margin:0 auto;color:#222;line-height:1.55">
   <h2><?= Html::encode($message['title']) ?></h2>
   <p><?= Html::encode($message['hello']) ?></p>
   <p><?= Html::encode($message['intro']) ?></p>
   <div style="font-size:32px;font-weight:700;letter-spacing:8px;padding:18px 22px;background:#f3f5f7;border-radius:10px;text-align:center">
      <?= Html::encode($code) ?>
   </div>
   <p><?= Html::encode($message['expires']) ?></p>
   <p style="color:#666"><?= Html::encode($message['ignore']) ?></p>
</div>
