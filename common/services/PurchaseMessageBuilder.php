<?php

declare(strict_types=1);

namespace common\services;

use yii\helpers\Html;

final class PurchaseMessageBuilder
{
   public static function build(string $language, string $name): string
   {
      $language = self::normalizeLanguage($language);
      $message = self::messages()[$language];

      $name = trim($name);
      if ($name === '') {
         $name = match ($language) {
            'uz' => 'foydalanuvchi',
            'en' => 'student',
            default => 'пользователь',
         };
      }

      $hello = str_replace(
         '{name}',
         Html::encode($name),
         $message['hello']
      );

      return '<b>' . $message['title'] . "</b>\n\n"
         . $hello . "\n\n"
         . $message['intro'] . "\n\n"
         . $message['salt'] . "\n\n"
         . '<b>' . $message['button'] . "</b>\n\n"
         . $message['note'] . "\n\n"
         . '<i>' . $message['footer'] . '</i>';
   }

   public static function courseButton(string $language): string
   {
      return match (self::normalizeLanguage($language)) {
         'uz' => 'Kurslarga o‘tish',
         'en' => 'Open courses',
         default => 'Перейти к курсам',
      };
   }

   public static function normalizeLanguage(string $language): string
   {
      $language = strtolower(substr(trim($language), 0, 2));

      return in_array($language, ['ru', 'uz', 'en'], true)
         ? $language
         : 'ru';
   }

   private static function messages(): array
   {
      return [
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
   }
}
