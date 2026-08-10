<?php

namespace frontend\components;

use Yii;
use yii\base\Component;

/** Centralized, safe defaults for metadata shared by every frontend page. */
final class Seo extends Component
{
   public array $languages = ['ru', 'en', 'uz'];
   public string $defaultImage = '/logo.png';
   public array $noIndexRoutes = [
      'site/error', 'site/login', 'site/signup', 'site/profile',
      'site/request-password-reset', 'site/reset-password',
      'site/resend-verification-email', 'site/verify-email',
      'courses/invoice', 'courses/no-subs',
      'payment/click-return', 'payment/payme-result',
   ];

   public function register(): void
   {
      $view = Yii::$app->view;
      $request = Yii::$app->request;
      $route = Yii::$app->controller->route;
      $title = trim((string) $view->title) ?: Yii::$app->name;
      $description = $view->params['seoDescription'] ?? $this->defaultDescription();
      $description = $this->truncate(preg_replace('/\s+/u', ' ', strip_tags((string) $description)), 160);
      $canonical = $view->params['canonical'] ?? strtok($request->absoluteUrl, '?');
      $image = $view->params['seoImage'] ?? $this->defaultImage;
      $image = preg_match('#^https?://#i', $image) ? $image : Yii::$app->urlManager->createAbsoluteUrl($image);
      $robots = in_array($route, $this->noIndexRoutes, true) ? 'noindex, nofollow' : 'index, follow, max-image-preview:large';

      $view->registerMetaTag(['name' => 'description', 'content' => $description], 'description');
      $view->registerMetaTag(['name' => 'robots', 'content' => $robots], 'robots');
      $view->registerLinkTag(['rel' => 'canonical', 'href' => $canonical], 'canonical');
      foreach ($this->languages as $language) {
         $view->registerLinkTag(['rel' => 'alternate', 'hreflang' => $language, 'href' => $this->languageUrl($canonical, $language)], 'alternate-' . $language);
      }
      $view->registerLinkTag(['rel' => 'alternate', 'hreflang' => 'x-default', 'href' => $this->languageUrl($canonical, 'en')], 'alternate-default');

      foreach ([
         ['property' => 'og:type', 'content' => $view->params['ogType'] ?? 'website'],
         ['property' => 'og:site_name', 'content' => Yii::$app->name],
         ['property' => 'og:title', 'content' => $title],
         ['property' => 'og:description', 'content' => $description],
         ['property' => 'og:url', 'content' => $canonical],
         ['property' => 'og:image', 'content' => $image],
         ['property' => 'og:locale', 'content' => str_replace('-', '_', Yii::$app->language)],
         ['name' => 'twitter:card', 'content' => 'summary_large_image'],
         ['name' => 'twitter:title', 'content' => $title],
         ['name' => 'twitter:description', 'content' => $description],
         ['name' => 'twitter:image', 'content' => $image],
      ] as $tag) {
         $view->registerMetaTag($tag);
      }

      $schema = [
         '@context' => 'https://schema.org',
         '@type' => 'EducationalOrganization',
         'name' => Yii::$app->name,
         'url' => Yii::$app->urlManager->createAbsoluteUrl('/'),
         'logo' => $image,
         'email' => Yii::$app->params['adminEmail'] ?? null,
         'telephone' => Yii::$app->params['phone'] ?? null,
      ];
      $view->params['seoSchema'] = array_filter($schema);
   }

   private function languageUrl(string $url, string $language): string
   {
      return preg_replace('#/(ru|en|uz)(?=/|$)#', '/' . $language, $url, 1) ?: $url;
   }

   private function defaultDescription(): string
   {
      return match (Yii::$app->language) {
         'ru' => 'Meros International Institute — международные образовательные программы и курсы английского языка для специалистов и организаций.',
         'uz' => 'Meros International Institute — mutaxassislar va tashkilotlar uchun xalqaro taʼlim dasturlari va ingliz tili kurslari.',
         default => 'Meros International Institute provides international education programmes and English courses for professionals and organisations.',
      };
   }

   private function truncate(string $value, int $length): string
   {
      return mb_strlen($value) <= $length ? $value : rtrim(mb_substr($value, 0, $length - 1)) . '…';
   }
}
