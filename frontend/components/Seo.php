<?php

namespace frontend\components;

use Yii;
use yii\base\Component;

final class Seo extends Component
{
   /*
    |--------------------------------------------------------------------------
    | GENERAL
    |--------------------------------------------------------------------------
    */
   
   public array $languages = [
      'ru',
      'en',
      'uz',
   ];
   
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
    * Название бренда.
    */
   public string $siteName = 'Meros';
   
   public array $siteAlternateNames = [
      'Meros Edu',
      'Meros International Institute',
      'merosedu.uz',
   ];
   
   /**
    * Дефолтная OG-картинка.
    *
    * Позже желательно сделать отдельную 1200x630.
    */
   public string $defaultImage = '/logo.png';
   
   /**
    * Логотип организации для Schema.org.
    */
   public string $logo = '/logo.png';
   
   
   /*
    |--------------------------------------------------------------------------
    | SEO META MODEL
    |--------------------------------------------------------------------------
    |
    | Здесь может лежать SeoMeta ActiveRecord.
    |
    | Специально используем object, чтобы Seo component
    | не был жёстко связан с конкретным namespace модели.
    |
    */
   
   private ?object $meta = null;
   
   
   /*
    |--------------------------------------------------------------------------
    | NO INDEX ROUTES
    |--------------------------------------------------------------------------
    */
   
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
   
   
   /*
    |--------------------------------------------------------------------------
    | PUBLIC META API
    |--------------------------------------------------------------------------
    */
   
   /**
    * Передаёт SeoMeta текущей сущности.
    *
    * Пример:
    *
    * Yii::$app->seo->useMeta($category->seoMeta);
    */
   public function useMeta(?object $meta): self
   {
      $this->meta = $meta;
      
      return $this;
   }
   
   
   /**
    * Возвращает текущий SeoMeta.
    */
   public function getMeta(): ?object
   {
      return $this->meta;
   }
   
   
   /**
    * Проверяет, назначен ли SeoMeta.
    */
   public function hasMeta(): bool
   {
      return $this->meta !== null;
   }
   
   
   /**
    * Сбрасывает SeoMeta.
    */
   public function clearMeta(): self
   {
      $this->meta = null;
      
      return $this;
   }
   
   
   /*
    |--------------------------------------------------------------------------
    | H1
    |--------------------------------------------------------------------------
    |
    | H1 не является meta-тегом, поэтому отдаём его view отдельно.
    |
    */
   
   public function getH1(?string $fallback = null): string
   {
      $h1 = $this->metaValue(
         'h1',
         $this->currentLanguage()
      );
      
      return (string)(
         $h1
         ?? $fallback
         ?? ''
      );
   }
   
   
   /*
    |--------------------------------------------------------------------------
    | SEO / LANDING TEXT
    |--------------------------------------------------------------------------
    |
    | Возвращается HTML как есть.
    |
    | Поэтому во view не использовать Html::encode().
    |
    */
   
   public function getText(): string
   {
      return (string)(
         $this->metaValue(
            'text',
            $this->currentLanguage()
         )
         ?? ''
      );
   }
   
   
   /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */
   
   public function register(): void
   {
      $view = Yii::$app->view;
      $route = Yii::$app->controller->route;
      
      $language = $this->currentLanguage();
      
      
      /*
       |--------------------------------------------------------------------------
       | TITLE
       |--------------------------------------------------------------------------
       |
       | Приоритет:
       |
       | 1. View::$params['seoTitle']
       | 2. SeoMeta::title_{lang}
       | 3. View::$title
       | 4. Meros
       |
       */
      
      $title = trim(
         (string)(
            $view->params['seoTitle']
            ?? $this->metaValue(
            'title',
            $language
         )
            ?? $view->title
            ?? ''
         )
      );
      
      if ($title === '') {
         $title = $this->siteName;
      }
      
      /*
       * На внутренних страницах автоматически добавляем бренд.
       *
       * Например:
       *
       * English for Doctors
       *
       * ->
       *
       * English for Doctors | Meros
       *
       * Если SeoMeta уже содержит Meros,
       * второй раз бренд не добавится.
       */
      if (
         $route !== 'site/index'
         && mb_stripos(
            $title,
            $this->siteName
         ) === false
      ) {
         $title .= ' | ' . $this->siteName;
      }
      
      $view->title = $title;
      
      
      /*
       |--------------------------------------------------------------------------
       | DESCRIPTION
       |--------------------------------------------------------------------------
       |
       | Приоритет:
       |
       | 1. View params
       | 2. SeoMeta
       | 3. Default description
       |
       */
      
      $description =
         $view->params['seoDescription']
         ?? $this->metaValue(
         'description',
         $language
      )
         ?? $this->defaultDescription();
      
      $description = preg_replace(
         '/\s+/u',
         ' ',
         strip_tags(
            (string)$description
         )
      );
      
      $description = trim(
         (string)$description
      );
      
      /*
       * Это не жёсткое SEO-ограничение.
       *
       * Просто не позволяем случайно
       * засунуть сюда пол-страницы.
       */
      $description = $this->truncate(
         $description,
         180
      );
      
      
      /*
       |--------------------------------------------------------------------------
       | CANONICAL
       |--------------------------------------------------------------------------
       */
      
      $canonical =
         $view->params['canonical']
         ?? $this->metaValue('canonical')
         ?? $this->currentCanonicalUrl();
      
      $canonical = $this->absoluteUrl(
         $canonical
      );
      
      
      /*
       |--------------------------------------------------------------------------
       | IMAGE
       |--------------------------------------------------------------------------
       */
      
      $image =
         $view->params['seoImage']
         ?? $this->metaValue('og_image')
         ?? $this->defaultImage;
      
      $image = $this->absoluteUrl(
         $image
      );
      
      $imageAlt =
         $view->params['seoImageAlt']
         ?? $title;
      
      
      /*
       |--------------------------------------------------------------------------
       | INDEXING
       |--------------------------------------------------------------------------
       |
       | noIndexRoutes имеет высший приоритет.
       |
       | Таким образом администратор случайно не сможет
       | сделать invoice/indexable через seo_meta.
       |
       */
      
      $noIndex =
         ($view->params['seoNoIndex'] ?? false)
         || in_array(
            $route,
            $this->noIndexRoutes,
            true
         );
      
      if ($noIndex) {
         
         $robots =
            'noindex, nofollow';
         
      } else {
         
         $robots =
            $view->params['seoRobots']
            ?? $this->metaValue('robots')
            ?? 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1';
      }
      
      
      /*
       |--------------------------------------------------------------------------
       | SCHEMA PAGE TYPE
       |--------------------------------------------------------------------------
       |
       | Категория курсов автоматически является CollectionPage.
       |
       */
      
      if (
         empty($view->params['schemaPageType'])
         && $this->metaValue('entity_type') === 'course_category'
      ) {
         $view->params['schemaPageType'] =
            'CollectionPage';
      }
      
      
      /*
       |--------------------------------------------------------------------------
       | STANDARD SEO
       |--------------------------------------------------------------------------
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
       |--------------------------------------------------------------------------
       | CANONICAL
       |--------------------------------------------------------------------------
       */
      
      $view->registerLinkTag([
         'rel' => 'canonical',
         'href' => $canonical,
      ], 'seo-canonical');
      
      
      /*
       |--------------------------------------------------------------------------
       | HREFLANG
       |--------------------------------------------------------------------------
       |
       | Если slug разных языковых версий отличается:
       |
       * $this->params['seoAlternates'] = [
       *     'ru' => '...',
       *     'en' => '...',
       *     'uz' => '...',
       * ];
       |
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
         
         $href = $this->absoluteUrl(
            $href
         );
         
         $alternateUrls[$alternateLanguage] =
            $href;
         
         $view->registerLinkTag([
            'rel' => 'alternate',
            'hreflang' => $alternateLanguage,
            'href' => $href,
         ], 'seo-alternate-' . $alternateLanguage);
      }
      
      
      /*
       |--------------------------------------------------------------------------
       | X-DEFAULT
       |--------------------------------------------------------------------------
       */
      
      $xDefault =
         $view->params['seoXDefault']
         ?? (
         $alternateUrls['en']
         ?? $this->siteUrl . '/'
      );
      
      $xDefault = $this->absoluteUrl(
         $xDefault
      );
      
      $view->registerLinkTag([
         'rel' => 'alternate',
         'hreflang' => 'x-default',
         'href' => $xDefault,
      ], 'seo-alternate-default');
      
      
      /*
       |--------------------------------------------------------------------------
       | OPEN GRAPH
       |--------------------------------------------------------------------------
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
            'seo-' . str_replace(
               ':',
               '-',
               $name
            )
         );
      }
      
      
      /*
       |--------------------------------------------------------------------------
       | OG IMAGE SIZE
       |--------------------------------------------------------------------------
       |
       | Для страницы можно передать:
       |
       * $this->params['seoImageWidth'] = 1200;
       * $this->params['seoImageHeight'] = 630;
       |
       */
      
      if (!empty($view->params['seoImageWidth'])) {
         
         $view->registerMetaTag([
            'property' => 'og:image:width',
            'content' =>
               (string)$view->params['seoImageWidth'],
         ], 'seo-og-image-width');
      }
      
      if (!empty($view->params['seoImageHeight'])) {
         
         $view->registerMetaTag([
            'property' => 'og:image:height',
            'content' =>
               (string)$view->params['seoImageHeight'],
         ], 'seo-og-image-height');
      }
      
      if (!empty($view->params['seoImageType'])) {
         
         $view->registerMetaTag([
            'property' => 'og:image:type',
            'content' =>
               $view->params['seoImageType'],
         ], 'seo-og-image-type');
      }
      
      
      /*
       |--------------------------------------------------------------------------
       | OG LOCALE ALTERNATES
       |--------------------------------------------------------------------------
       */
      
      foreach ($this->languages as $alternateLanguage) {
         
         if ($alternateLanguage === $language) {
            continue;
         }
         
         if (
            !isset(
               $this->ogLocales[$alternateLanguage]
            )
         ) {
            continue;
         }
         
         $view->registerMetaTag([
            'property' => 'og:locale:alternate',
            'content' =>
               $this->ogLocales[$alternateLanguage],
         ], 'seo-og-locale-' . $alternateLanguage);
      }
      
      
      /*
       |--------------------------------------------------------------------------
       | TWITTER / X
       |--------------------------------------------------------------------------
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
            'seo-' . str_replace(
               ':',
               '-',
               $tag['name']
            )
         );
      }
      
      
      /*
       * params.php:
       *
       * 'twitterSite' => '@meros...',
       */
      if (!empty(Yii::$app->params['twitterSite'])) {
         
         $view->registerMetaTag([
            'name' => 'twitter:site',
            'content' =>
               Yii::$app->params['twitterSite'],
         ], 'seo-twitter-site');
      }
      
      
      /*
       |--------------------------------------------------------------------------
       | STRUCTURED DATA
       |--------------------------------------------------------------------------
       |
       | На noindex страницах Schema не генерируем.
       |
       */
      
      if (!$noIndex) {
         
         $extraSchema =
            $view->params['seoSchema']
            ?? [];
         
         $view->params['seoSchema'] =
            $this->buildSchema(
               title: $title,
               description: $description,
               canonical: $canonical,
               image: $image,
               language: $language,
               extraSchema: $extraSchema,
            );
         
      } else {
         
         unset(
            $view->params['seoSchema']
         );
      }
   }
   
   
   /*
    |--------------------------------------------------------------------------
    | SCHEMA
    |--------------------------------------------------------------------------
    */
   
   private function buildSchema(
      string $title,
      string $description,
      string $canonical,
      string $image,
      string $language,
      array $extraSchema = [],
   ): array {
      $siteUrl =
         rtrim($this->siteUrl, '/')
         . '/';
      
      $organizationId =
         $siteUrl . '#organization';
      
      $websiteId =
         $siteUrl . '#website';
      
      $logo =
         $this->absoluteUrl(
            $this->logo
         );
      
      
      /*
       * Публичные контактные данные.
       *
       * Не используем adminEmail.
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
      
      
      /*
       |--------------------------------------------------------------------------
       | ORGANIZATION
       |--------------------------------------------------------------------------
       */
      
      $organization = [
         '@type' =>
            'EducationalOrganization',
         
         '@id' =>
            $organizationId,
         
         'name' =>
            $this->siteName,
         
         'alternateName' => [
            'Meros International Institute',
            'Meros Edu',
         ],
         
         'url' =>
            $siteUrl,
         
         'logo' => [
            '@type' => 'ImageObject',
            'url' => $logo,
            'contentUrl' => $logo,
         ],
         
         'description' =>
            $this->defaultDescription(),
         
         'email' =>
            $email,
         
         'telephone' =>
            $telephone,
         
         'sameAs' =>
            $sameAs,
      ];
      
      
      /*
       |--------------------------------------------------------------------------
       | ADDRESS
       |--------------------------------------------------------------------------
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
       *
       */
      
      if (!empty($addressData)) {
         
         $organization['address'] =
            array_merge(
               [
                  '@type' =>
                     'PostalAddress',
               ],
               $addressData
            );
      }
      
      
      /*
       |--------------------------------------------------------------------------
       | CONTACT POINT
       |--------------------------------------------------------------------------
       */
      
      if ($email || $telephone) {
         
         $organization['contactPoint'] = [
            '@type' => 'ContactPoint',
            'email' => $email,
            'telephone' => $telephone,
         ];
      }
      
      
      /*
       |--------------------------------------------------------------------------
       | GRAPH
       |--------------------------------------------------------------------------
       */
      
      $graph = [
         $this->clean(
            $organization
         ),
      ];
      
      
      /*
       |--------------------------------------------------------------------------
       | WEBSITE
       |--------------------------------------------------------------------------
       |
       | Добавляем на homepage.
       |
       */
      
      if ($this->isHomePage()) {
         
         $graph[] = [
            '@type' =>
               'WebSite',
            
            '@id' =>
               $websiteId,
            
            'url' =>
               $siteUrl,
            
            'name' =>
               $this->siteName,
            
            'alternateName' =>
               $this->siteAlternateNames,
            
            'publisher' => [
               '@id' =>
                  $organizationId,
            ],
         ];
      }
      
      
      /*
       |--------------------------------------------------------------------------
       | CURRENT PAGE
       |--------------------------------------------------------------------------
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
            '@id' =>
               $websiteId,
         ],
         
         'about' => [
            '@id' =>
               $organizationId,
         ],
         
         'primaryImageOfPage' => [
            '@type' =>
               'ImageObject',
            
            'url' =>
               $image,
         ],
      ];
      
      
      /*
       |--------------------------------------------------------------------------
       | EXTRA SCHEMA
       |--------------------------------------------------------------------------
       |
       | Course
       | Article
       | Event
       | Person
       | BreadcrumbList
       | ...
       |
       */
      
      foreach (
         $this->normalizeSchemaNodes(
            $extraSchema
         )
         as $node
      ) {
         $graph[] = $node;
      }
      
      
      return [
         '@context' =>
            'https://schema.org',
         
         '@graph' =>
            array_map(
               fn(array $node) =>
               $this->clean($node),
               
               $graph
            ),
      ];
   }
   
   
   /*
    |--------------------------------------------------------------------------
    | NORMALIZE EXTRA SCHEMA
    |--------------------------------------------------------------------------
    */
   
   private function normalizeSchemaNodes(
      array $schema
   ): array {
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
         unset(
            $schema['@context']
         );
         
         return [
            $schema,
         ];
      }
      
      
      /*
       * Массив объектов Schema.
       */
      $result = [];
      
      foreach ($schema as $node) {
         
         if (!is_array($node)) {
            continue;
         }
         
         unset(
            $node['@context']
         );
         
         $result[] = $node;
      }
      
      return $result;
   }
   
   
   /*
    |--------------------------------------------------------------------------
    | CANONICAL
    |--------------------------------------------------------------------------
    */
   
   private function currentCanonicalUrl(): string
   {
      $path =
         parse_url(
            Yii::$app->request->url,
            PHP_URL_PATH
         )
            ?: '/';
      
      return $this->absoluteUrl(
         $path
      );
   }
   
   
   /*
    |--------------------------------------------------------------------------
    | LANGUAGE URL
    |--------------------------------------------------------------------------
    */
   
   private function languageUrl(
      string $url,
      string $language
   ): string {
      $path =
         parse_url(
            $url,
            PHP_URL_PATH
         )
            ?: '/';
      
      
      /*
       * URL уже содержит язык.
       *
       * /ru/about
       *
       * ->
       *
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
          * Языка ещё нет.
          *
          * /about
          *
          * ->
          *
          * /en/about
          */
         $path =
            '/' . $language
            . (
            $path === '/'
               ? '/'
               : '/' . ltrim(
                  $path,
                  '/'
               )
            );
      }
      
      return rtrim(
            $this->siteUrl,
            '/'
         ) . $path;
   }
   
   
   /*
    |--------------------------------------------------------------------------
    | ABSOLUTE URL
    |--------------------------------------------------------------------------
    */
   
   private function absoluteUrl(
      string $url
   ): string {
      if (
         preg_match(
            '#^https?://#i',
            $url
         )
      ) {
         return $url;
      }
      
      if (
         $url === ''
         || $url === '/'
      ) {
         return rtrim(
               $this->siteUrl,
               '/'
            )
            . '/';
      }
      
      return rtrim(
            $this->siteUrl,
            '/'
         )
         . '/'
         . ltrim(
            $url,
            '/'
         );
   }
   
   
   /*
    |--------------------------------------------------------------------------
    | CURRENT LANGUAGE
    |--------------------------------------------------------------------------
    */
   
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
         )[0]
         ?? 'en';
      
      return in_array(
         $language,
         $this->languages,
         true
      )
         ? $language
         : 'en';
   }
   
   
   /*
    |--------------------------------------------------------------------------
    | HOME PAGE
    |--------------------------------------------------------------------------
    |
    | Используем route, а не URL.
    |
    | Поэтому работает одинаково для:
    |
    | /
    | /ru
    | /en
    | /uz
    |
    */
   
   private function isHomePage(): bool
   {
      return Yii::$app->controller->route
         === 'site/index';
   }
   
   
   /*
    |--------------------------------------------------------------------------
    | DEFAULT DESCRIPTION
    |--------------------------------------------------------------------------
    */
   
   private function defaultDescription(): string
   {
      return match (
      $this->currentLanguage()
      ) {
         'ru' =>
         'Meros — международный образовательный институт в Узбекистане: медицинский английский, подготовка к OET и профессиональное обучение.',
         
         'uz' =>
         'Meros — Oʻzbekistondagi xalqaro taʼlim instituti: tibbiy ingliz tili, OET tayyorgarligi va professional taʼlim dasturlari.',
         
         default =>
         'Meros is an international education institute in Uzbekistan offering Medical English, OET preparation and professional education programmes.',
      };
   }
   
   
   /*
    |--------------------------------------------------------------------------
    | META VALUE
    |--------------------------------------------------------------------------
    |
    | $this->metaValue('title', 'ru')
    |
    | ->
    |
    | title_ru
    |
    |
    | $this->metaValue('canonical')
    |
    | ->
    |
    | canonical
    |
    */
   
   private function metaValue(
      string $field,
      ?string $language = null
   ): mixed {
      if ($this->meta === null) {
         return null;
      }
      
      if ($language !== null) {
         $field .= '_' . $language;
      }
      
      try {
         
         $value =
            $this->meta->{$field};
         
      } catch (\Throwable) {
         
         return null;
      }
      
      
      /*
       * Пустая строка = значения нет.
       *
       * Тогда Seo component продолжит fallback.
       */
      if (is_string($value)) {
         
         $value = trim(
            $value
         );
         
         return $value !== ''
            ? $value
            : null;
      }
      
      return $value;
   }
   
   
   /*
    |--------------------------------------------------------------------------
    | TRUNCATE
    |--------------------------------------------------------------------------
    */
   
   private function truncate(
      string $value,
      int $length
   ): string {
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
         )
         . '…';
   }
   
   
   /*
    |--------------------------------------------------------------------------
    | CLEAN SCHEMA
    |--------------------------------------------------------------------------
    */
   
   private function clean(
      array $data
   ): array {
      foreach (
         $data
         as $key => $value
      ) {
         if (is_array($value)) {
            
            $value =
               $this->clean(
                  $value
               );
         }
         
         if (
            $value === null
            || $value === ''
            || $value === []
         ) {
            unset(
               $data[$key]
            );
            
            continue;
         }
         
         $data[$key] =
            $value;
      }
      
      return $data;
   }
}