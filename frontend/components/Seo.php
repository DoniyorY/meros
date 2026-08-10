<?php

namespace frontend\components;

use Yii;
use yii\base\Component;

final class Seo extends Component
{
   public array $languages = ['ru', 'en', 'uz'];
   
   public array $ogLocales = [
      'ru' => 'ru_RU',
      'en' => 'en_US',
      'uz' => 'uz_UZ',
   ];
   
   /**
    * Канонический production-домен.
    */
   public string $siteUrl = 'https://merosedu.uz';
   
   /**
    * Именно название сайта/бренда.
    */
   public string $siteName = 'Meros';
   
   public array $siteAlternateNames = [
      'Meros Edu',
      'Meros International Institute',
      'merosedu.uz',
   ];
   
   /**
    * В идеале заменить на отдельную OG-картинку 1200x630.
    */
   public string $defaultImage = '/logo.png';
   
   /**
    * Логотип организации.
    * НЕ путать с OG-картинкой страницы.
    */
   public string $logo = '/logo.png';
   
   public array $noIndexRoutes = [
      'site/error',
      'site/login',
      'site/signup',
      'site/profile',
      'site/request-password-reset',
      'site/reset-password',
      'site/resend-verification-email',
      'site/verify-email',
      
      'courses/invoice',
      'courses/no-subs',
      
      'payment/click-return',
      'payment/payme-result',
   ];
   
   public function register(): void
   {
      $view = Yii::$app->view;
      $request = Yii::$app->request;
      $route = Yii::$app->controller->route;
      
      $language = $this->currentLanguage();
      
      /*
       * TITLE
       */
      $title = trim((string)$view->title);
      
      if ($title === '') {
         $title = $this->siteName;
      }
      
      /*
       * На внутренних страницах автоматически добавляем бренд.
       *
       * Reach OET B Medicine
       * ->
       * Reach OET B Medicine | Meros
       */
      if (
         $route !== 'site/index'
         && mb_stripos($title, $this->siteName) === false
      ) {
         $title .= ' | ' . $this->siteName;
      }
      
      $view->title = $title;
      
      /*
       * DESCRIPTION
       */
      $description =
         $view->params['seoDescription']
         ?? $this->defaultDescription();
      
      $description = preg_replace(
         '/\s+/u',
         ' ',
         strip_tags((string)$description)
      );
      
      $description = trim($description);
      
      /*
       * 160 — не магическое SEO-число.
       * Просто не даём случайно засунуть сюда пол-страницы.
       */
      $description = $this->truncate($description, 180);
      
      /*
       * CANONICAL
       */
      $canonical =
         $view->params['canonical']
         ?? $this->currentCanonicalUrl();
      
      $canonical = $this->absoluteUrl($canonical);
      
      /*
       * IMAGE
       */
      $image =
         $view->params['seoImage']
         ?? $this->defaultImage;
      
      $image = $this->absoluteUrl($image);
      
      $imageAlt =
         $view->params['seoImageAlt']
         ?? $title;
      
      /*
       * INDEXING
       */
      $noIndex =
         ($view->params['seoNoIndex'] ?? false)
         || in_array($route, $this->noIndexRoutes, true);
      
      $robots = $view->params['seoRobots']
         ?? (
         $noIndex
            ? 'noindex, nofollow'
            : 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1'
         );
      
      /*
       * STANDARD SEO
       */
      $view->registerMetaTag([
         'name' => 'description',
         'content' => $description,
      ], 'seo-description');
      
      $view->registerMetaTag([
         'name' => 'robots',
         'content' => $robots,
      ], 'seo-robots');
      
      /*
       * CANONICAL
       */
      $view->registerLinkTag([
         'rel' => 'canonical',
         'href' => $canonical,
      ], 'seo-canonical');
      
      /*
       * HREFLANG
       *
       * Если у языковых версий разные slug,
       * можно передать:
       *
       * $this->params['seoAlternates'] = [
       *     'ru' => '...',
       *     'en' => '...',
       *     'uz' => '...',
       * ];
       */
      $customAlternates =
         $view->params['seoAlternates']
         ?? [];
      
      $alternateUrls = [];
      
      foreach ($this->languages as $alternateLanguage) {
         $href =
            $customAlternates[$alternateLanguage]
            ?? $this->languageUrl(
            $canonical,
            $alternateLanguage
         );
         
         $href = $this->absoluteUrl($href);
         
         $alternateUrls[$alternateLanguage] = $href;
         
         $view->registerLinkTag([
            'rel' => 'alternate',
            'hreflang' => $alternateLanguage,
            'href' => $href,
         ], 'seo-alternate-' . $alternateLanguage);
      }
      
      /*
       * x-default.
       *
       * Если / является выбором языка,
       * лучше передать:
       *
       * $this->params['seoXDefault'] = 'https://merosedu.uz/';
       */
      $xDefault =
         $view->params['seoXDefault']
         ?? ($alternateUrls['en'] ?? $this->siteUrl . '/');
      
      $view->registerLinkTag([
         'rel' => 'alternate',
         'hreflang' => 'x-default',
         'href' => $xDefault,
      ], 'seo-alternate-default');
      
      /*
       * OPEN GRAPH
       */
      $ogType =
         $view->params['ogType']
         ?? 'website';
      
      $ogLocale =
         $this->ogLocales[$language]
         ?? 'en_US';
      
      $ogTags = [
         [
            'property' => 'og:type',
            'content' => $ogType,
         ],
         [
            'property' => 'og:site_name',
            'content' => $this->siteName,
         ],
         [
            'property' => 'og:title',
            'content' => $title,
         ],
         [
            'property' => 'og:description',
            'content' => $description,
         ],
         [
            'property' => 'og:url',
            'content' => $canonical,
         ],
         [
            'property' => 'og:image',
            'content' => $image,
         ],
         [
            'property' => 'og:image:secure_url',
            'content' => $image,
         ],
         [
            'property' => 'og:image:alt',
            'content' => $imageAlt,
         ],
         [
            'property' => 'og:locale',
            'content' => $ogLocale,
         ],
      ];
      
      foreach ($ogTags as $tag) {
         $name = $tag['property'];
         
         $view->registerMetaTag(
            $tag,
            'seo-' . str_replace(':', '-', $name)
         );
      }
      
      /*
       * OG IMAGE SIZE
       *
       * Для страницы можно передать:
       *
       * $this->params['seoImageWidth'] = 1200;
       * $this->params['seoImageHeight'] = 630;
       */
      if (!empty($view->params['seoImageWidth'])) {
         $view->registerMetaTag([
            'property' => 'og:image:width',
            'content' => (string)$view->params['seoImageWidth'],
         ], 'seo-og-image-width');
      }
      
      if (!empty($view->params['seoImageHeight'])) {
         $view->registerMetaTag([
            'property' => 'og:image:height',
            'content' => (string)$view->params['seoImageHeight'],
         ], 'seo-og-image-height');
      }
      
      if (!empty($view->params['seoImageType'])) {
         $view->registerMetaTag([
            'property' => 'og:image:type',
            'content' => $view->params['seoImageType'],
         ], 'seo-og-image-type');
      }
      
      /*
       * OG LOCALE ALTERNATES
       */
      foreach ($this->languages as $alternateLanguage) {
         if ($alternateLanguage === $language) {
            continue;
         }
         
         if (!isset($this->ogLocales[$alternateLanguage])) {
            continue;
         }
         
         $view->registerMetaTag([
            'property' => 'og:locale:alternate',
            'content' => $this->ogLocales[$alternateLanguage],
         ], 'seo-og-locale-' . $alternateLanguage);
      }
      
      /*
       * TWITTER / X
       */
      foreach ([
                  [
                     'name' => 'twitter:card',
                     'content' => 'summary_large_image',
                  ],
                  [
                     'name' => 'twitter:title',
                     'content' => $title,
                  ],
                  [
                     'name' => 'twitter:description',
                     'content' => $description,
                  ],
                  [
                     'name' => 'twitter:image',
                     'content' => $image,
                  ],
                  [
                     'name' => 'twitter:image:alt',
                     'content' => $imageAlt,
                  ],
               ] as $tag) {
         $view->registerMetaTag(
            $tag,
            'seo-' . str_replace(':', '-', $tag['name'])
         );
      }
      
      /*
       * Если есть официальный X/Twitter:
       *
       * params.php:
       * 'twitterSite' => '@meros...',
       */
      if (!empty(Yii::$app->params['twitterSite'])) {
         $view->registerMetaTag([
            'name' => 'twitter:site',
            'content' => Yii::$app->params['twitterSite'],
         ], 'seo-twitter-site');
      }
      
      /*
       * STRUCTURED DATA
       *
       * На noindex страницах Schema нам особо не нужна.
       */
      if (!$noIndex) {
         $extraSchema =
            $view->params['seoSchema']
            ?? [];
         
         $view->params['seoSchema'] = $this->buildSchema(
            title: $title,
            description: $description,
            canonical: $canonical,
            image: $image,
            language: $language,
            extraSchema: $extraSchema,
         );
      } else {
         unset($view->params['seoSchema']);
      }
   }
   
   private function buildSchema(
      string $title,
      string $description,
      string $canonical,
      string $image,
      string $language,
      array  $extraSchema = [],
   ): array
   {
      $siteUrl = rtrim($this->siteUrl, '/') . '/';
      
      $organizationId =
         $siteUrl . '#organization';
      
      $websiteId =
         $siteUrl . '#website';
      
      $logo =
         $this->absoluteUrl($this->logo);
      
      /*
       * Не используем adminEmail.
       * В Schema должен попадать публичный контакт.
       */
      $email =
         Yii::$app->params['contactEmail']
         ?? null;
      
      $telephone =
         Yii::$app->params['phone']
         ?? null;
      
      $sameAs =
         Yii::$app->params['socialLinks']
         ?? [];
      
      $addressData =
         Yii::$app->params['seoAddress']
         ?? [];
      
      $organization = [
         '@type' => 'EducationalOrganization',
         
         '@id' => $organizationId,
         
         'name' => $this->siteName,
         
         'alternateName' => [
            'Meros International Institute',
            'Meros Edu',
         ],
         
         'url' => $siteUrl,
         
         'logo' => [
            '@type' => 'ImageObject',
            'url' => $logo,
            'contentUrl' => $logo,
         ],
         
         'description' => $this->defaultDescription(),
         
         'email' => $email,
         
         'telephone' => $telephone,
         
         'sameAs' => $sameAs,
      ];
      
      /*
       * ADDRESS
       *
       * params.php:
       *
       * 'seoAddress' => [
       *     'streetAddress' => '...',
       *     'addressLocality' => 'Samarkand',
       *     'addressRegion' => 'Samarkand',
       *     'postalCode' => '...',
       *     'addressCountry' => 'UZ',
       * ],
       */
      if (!empty($addressData)) {
         $organization['address'] = array_merge([
            '@type' => 'PostalAddress',
         ], $addressData);
      }
      
      /*
       * CONTACT POINT
       */
      if ($email || $telephone) {
         $organization['contactPoint'] = [
            '@type' => 'ContactPoint',
            'email' => $email,
            'telephone' => $telephone,
         ];
      }
      
      $graph = [
         $this->clean($organization),
      ];
      
      /*
       * WebSite нужен Google именно на корне домена.
       */
      if ($this->isRootPage()) {
         $graph[] = [
            '@type' => 'WebSite',
            
            '@id' => $websiteId,
            
            'url' => $siteUrl,
            
            'name' => $this->siteName,
            
            'alternateName' => $this->siteAlternateNames,
            
            'publisher' => [
               '@id' => $organizationId,
            ],
         ];
      }
      
      /*
       * Текущая страница.
       */
      $graph[] = [
         '@type' =>
            Yii::$app->view->params['schemaPageType']
            ?? 'WebPage',
         
         '@id' =>
            $canonical . '#webpage',
         
         'url' =>
            $canonical,
         
         'name' =>
            $title,
         
         'description' =>
            $description,
         
         'inLanguage' =>
            $language,
         
         'isPartOf' => [
            '@id' => $websiteId,
         ],
         
         'about' => [
            '@id' => $organizationId,
         ],
         
         'primaryImageOfPage' => [
            '@type' => 'ImageObject',
            'url' => $image,
         ],
      ];
      
      /*
       * Course / Article / Event / Person /
       * BreadcrumbList и т.д.
       */
      foreach ($this->normalizeSchemaNodes($extraSchema) as $node) {
         $graph[] = $node;
      }
      
      return [
         '@context' => 'https://schema.org',
         '@graph' => array_map(
            fn(array $node) => $this->clean($node),
            $graph
         ),
      ];
   }
   
   private function normalizeSchemaNodes(array $schema): array
   {
      if (!$schema) {
         return [];
      }
      
      /*
       * Уже полноценный @graph.
       */
      if (
         isset($schema['@graph'])
         && is_array($schema['@graph'])
      ) {
         return $schema['@graph'];
      }
      
      /*
       * Один объект Schema.
       */
      if (
         isset($schema['@type'])
         || isset($schema['@id'])
      ) {
         unset($schema['@context']);
         
         return [$schema];
      }
      
      /*
       * Массив объектов.
       */
      $result = [];
      
      foreach ($schema as $node) {
         if (!is_array($node)) {
            continue;
         }
         
         unset($node['@context']);
         
         $result[] = $node;
      }
      
      return $result;
   }
   
   private function currentCanonicalUrl(): string
   {
      $path =
         parse_url(
            Yii::$app->request->url,
            PHP_URL_PATH
         )
            ?: '/';
      
      return $this->absoluteUrl($path);
   }
   
   private function languageUrl(
      string $url,
      string $language
   ): string
   {
      $path =
         parse_url($url, PHP_URL_PATH)
            ?: '/';
      
      /*
       * Уже есть язык.
       *
       * /ru/about
       * ->
       * /en/about
       */
      if (
         preg_match(
            '#^/(ru|en|uz)(?=/|$)#',
            $path
         )
      ) {
         $path = preg_replace(
            '#^/(ru|en|uz)(?=/|$)#',
            '/' . $language,
            $path,
            1
         );
      } else {
         /*
          * Языка нет.
          *
          * /about
          * ->
          * /en/about
          */
         $path =
            '/' . $language
            . (
            $path === '/'
               ? '/'
               : '/' . ltrim($path, '/')
            );
      }
      
      return rtrim($this->siteUrl, '/')
         . $path;
   }
   
   private function absoluteUrl(string $url): string
   {
      if (
         preg_match(
            '#^https?://#i',
            $url
         )
      ) {
         return $url;
      }
      
      if ($url === '' || $url === '/') {
         return rtrim($this->siteUrl, '/') . '/';
      }
      
      return rtrim($this->siteUrl, '/')
         . '/'
         . ltrim($url, '/');
   }
   
   private function currentLanguage(): string
   {
      $language =
         strtolower(
            (string)Yii::$app->language
         );
      
      $language =
         preg_split(
            '/[-_]/',
            $language
         )[0] ?? 'en';
      
      return in_array(
         $language,
         $this->languages,
         true
      )
         ? $language
         : 'en';
   }
   
   private function isRootPage(): bool
   {
      $path =
         parse_url(
            Yii::$app->request->url,
            PHP_URL_PATH
         )
            ?: '/';
      
      return $path === '/';
   }
   
   private function defaultDescription(): string
   {
      return match ($this->currentLanguage()) {
         'ru' =>
         'Meros — международный образовательный институт в Узбекистане: медицинский английский, подготовка к OET и профессиональное обучение.',
         
         'uz' =>
         'Meros — Oʻzbekistondagi xalqaro taʼlim instituti: tibbiy ingliz tili, OET tayyorgarligi va professional taʼlim dasturlari.',
         
         default =>
         'Meros is an international education institute in Uzbekistan offering Medical English, OET preparation and professional education programmes.',
      };
   }
   
   private function truncate(
      string $value,
      int    $length
   ): string
   {
      if (
         mb_strlen($value)
         <= $length
      ) {
         return $value;
      }
      
      return rtrim(
            mb_substr(
               $value,
               0,
               $length - 1
            )
         ) . '…';
   }
   
   private function clean(array $data): array
   {
      foreach ($data as $key => $value) {
         if (is_array($value)) {
            $value =
               $this->clean($value);
         }
         
         if (
            $value === null
            || $value === ''
            || $value === []
         ) {
            unset($data[$key]);
            
            continue;
         }
         
         $data[$key] = $value;
      }
      
      return $data;
   }
}