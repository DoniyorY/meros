<?php

/** @var \common\models\User $user */
/** @var string $code */
/** @var string $language */
/** @var int $expiresMinutes */

$name = trim((string)($user->fullname ?? ''));

$messages = [
   'ru' => [
      'hello' => $name !== '' ? 'Здравствуйте, ' . $name . '!' : 'Здравствуйте!',
      'intro' => 'Код подключения Telegram:',
      'expires' => "Код действует {$expiresMinutes} минут.",
      'ignore' => 'Если вы не запрашивали подключение Telegram, проигнорируйте это письмо.',
   ],
   'uz' => [
      'hello' => $name !== '' ? 'Assalomu alaykum, ' . $name . '!' : 'Assalomu alaykum!',
      'intro' => 'Telegram ulash kodi:',
      'expires' => "Kod {$expiresMinutes} daqiqa amal qiladi.",
      'ignore' => 'Agar Telegram ulanishini so‘ramagan bo‘lsangiz, ushbu xatni e’tiborsiz qoldiring.',
   ],
   'en' => [
      'hello' => $name !== '' ? 'Hello, ' . $name . '!' : 'Hello!',
      'intro' => 'Telegram connection code:',
      'expires' => "The code is valid for {$expiresMinutes} minutes.",
      'ignore' => 'If you did not request a Telegram connection, ignore this email.',
   ],
];

$message = $messages[$language] ?? $messages['ru'];

echo $message['hello'] . "\n\n";
echo $message['intro'] . "\n";
echo $code . "\n\n";
echo $message['expires'] . "\n";
echo $message['ignore'] . "\n";
