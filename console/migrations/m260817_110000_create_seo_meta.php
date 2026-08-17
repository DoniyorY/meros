<?php

use yii\db\Migration;
use yii\db\Query;

class m260817_110000_create_seo_meta extends Migration
{
   public function safeUp()
   {
      /*
       |--------------------------------------------------------------------------
       | COURSE CATEGORY
       |--------------------------------------------------------------------------
       |
       | Нужно для section "Направления обучения" на главной.
       |
       */
      
      $this->addColumn(
         '{{%course_category}}',
         'show_on_home',
         $this->boolean()
            ->notNull()
            ->defaultValue(0)
      );
      
      $this->addColumn(
         '{{%course_category}}',
         'sort_order',
         $this->integer()
            ->notNull()
            ->defaultValue(100)
      );
      
      
      /*
       |--------------------------------------------------------------------------
       | SEO META
       |--------------------------------------------------------------------------
       */
      
      $this->createTable('{{%seo_meta}}', [
         'id' => $this->bigPrimaryKey()->unsigned(),
         
         /*
          * course
          * course_category
          * post
          * event
          * и т.д.
          */
         'entity_type' => $this->string(50)->notNull(),
         
         /*
          * ID записи из соответствующей таблицы.
          *
          * FK здесь специально НЕ ставим,
          * потому что связь полиморфная.
          */
         'entity_id' => $this->integer()->notNull(),
         
         
         /*
          |--------------------------------------------------------------------------
          | TITLE
          |--------------------------------------------------------------------------
          */
         
         'title_ru' => $this->string(255)->null(),
         'title_en' => $this->string(255)->null(),
         'title_uz' => $this->string(255)->null(),
         
         
         /*
          |--------------------------------------------------------------------------
          | META DESCRIPTION
          |--------------------------------------------------------------------------
          */
         
         'description_ru' => $this->text()->null(),
         'description_en' => $this->text()->null(),
         'description_uz' => $this->text()->null(),
         
         
         /*
          |--------------------------------------------------------------------------
          | H1
          |--------------------------------------------------------------------------
          |
          | Может отличаться от SEO title.
          |
          */
         
         'h1_ru' => $this->string(255)->null(),
         'h1_en' => $this->string(255)->null(),
         'h1_uz' => $this->string(255)->null(),
         
         
         /*
          |--------------------------------------------------------------------------
          | LANDING / SEO CONTENT
          |--------------------------------------------------------------------------
          |
          | HTML разрешаем.
          |
          | Именно сюда можно положить SEO-текст страницы категории.
          |
          */
         
         'text_ru' => $this->text()->null(),
         'text_en' => $this->text()->null(),
         'text_uz' => $this->text()->null(),
         
         
         /*
          |--------------------------------------------------------------------------
          | OPTIONAL SEO
          |--------------------------------------------------------------------------
          */
         
         'canonical' => $this->string(1000)->null(),
         
         'og_image' => $this->string(1000)->null(),
         
         /*
          * Например:
          *
          * index, follow, max-image-preview:large
          *
          * или:
          *
          * noindex, follow
          */
         'robots' => $this->string(255)->null(),
         
         
         /*
          |--------------------------------------------------------------------------
          | TIMESTAMPS
          |--------------------------------------------------------------------------
          */
         
         'created_at' => $this->integer()->notNull(),
         'updated_at' => $this->integer()->notNull(),
      ]);
      
      
      /*
       * У одной сущности может быть только одна SEO-запись.
       *
       * course_category + 3
       * course + 15
       */
      $this->createIndex(
         'uidx-seo_meta-entity',
         '{{%seo_meta}}',
         [
            'entity_type',
            'entity_id',
         ],
         true
      );
      
      $this->createIndex(
         'idx-seo_meta-entity_type',
         '{{%seo_meta}}',
         'entity_type'
      );
      
      
      /*
       |--------------------------------------------------------------------------
       | INITIAL SEO DATA
       |--------------------------------------------------------------------------
       |
       | Medical English уже можем создать сразу.
       |
       */
      
      $medicalEnglish = (new Query())
         ->select([
            'id',
            'slug',
         ])
         ->from('{{%course_category}}')
         ->where([
            'slug' => 'medical-english',
         ])
         ->one();
      
      
      if ($medicalEnglish) {
         
         $categoryId = (int)$medicalEnglish['id'];
         
         /*
          * Показываем категорию на главной.
          */
         $this->update(
            '{{%course_category}}',
            [
               'show_on_home' => 1,
               'sort_order' => 10,
            ],
            [
               'id' => $categoryId,
            ]
         );
         
         
         /*
          * Первоначальная SEO-конфигурация.
          */
         $this->insert('{{%seo_meta}}', [
            
            'entity_type' => 'course_category',
            
            'entity_id' => $categoryId,
            
            
            /*
             * RU
             */
            'title_ru' =>
               'Медицинский английский в Узбекистане — онлайн-курсы | Meros',
            
            'description_ru' =>
               'Курсы медицинского английского в Узбекистане для врачей, медсестёр, фармацевтов, студентов-медиков и специалистов здравоохранения.',
            
            'h1_ru' =>
               'Медицинский английский в Узбекистане',
            
            'text_ru' =>
               '<p>'
               . 'Meros предлагает профессиональные курсы медицинского английского '
               . 'в Узбекистане для врачей, медицинских сестёр, фармацевтов, '
               . 'студентов медицинских вузов и других специалистов здравоохранения.'
               . '</p>'
               . '<p>'
               . 'Программы помогают развивать медицинскую терминологию, '
               . 'профессиональный английский, навыки общения с пациентами '
               . 'и коллегами, а также готовят специалистов к работе '
               . 'в международной медицинской среде.'
               . '</p>',
            
            
            /*
             * EN
             */
            'title_en' =>
               'Medical English Courses in Uzbekistan | Meros',
            
            'description_en' =>
               'Online Medical English courses in Uzbekistan for doctors, nurses, pharmacists, medical students and healthcare professionals.',
            
            'h1_en' =>
               'Medical English Courses in Uzbekistan',
            
            'text_en' =>
               '<p>'
               . 'Meros provides professional Medical English courses in Uzbekistan '
               . 'for doctors, nurses, pharmacists, medical students and other '
               . 'healthcare professionals.'
               . '</p>'
               . '<p>'
               . 'Our programmes develop medical terminology, professional '
               . 'communication skills and the English needed to communicate '
               . 'with patients and colleagues in international healthcare settings.'
               . '</p>',
            
            
            /*
             * UZ
             */
            'title_uz' =>
               'O‘zbekistonda tibbiy ingliz tili kurslari | Meros',
            
            'description_uz' =>
               'Shifokorlar, hamshiralar, farmatsevtlar, tibbiyot talabalari va sog‘liqni saqlash mutaxassislari uchun tibbiy ingliz tili kurslari.',
            
            'h1_uz' =>
               'O‘zbekistonda tibbiy ingliz tili kurslari',
            
            'text_uz' =>
               '<p>'
               . 'Meros O‘zbekistonda shifokorlar, hamshiralar, farmatsevtlar, '
               . 'tibbiyot talabalari va boshqa sog‘liqni saqlash mutaxassislari '
               . 'uchun professional tibbiy ingliz tili kurslarini taklif etadi.'
               . '</p>'
               . '<p>'
               . 'Dasturlar tibbiy terminologiya, professional muloqot '
               . 'va xalqaro tibbiyot muhitida bemorlar hamda hamkasblar '
               . 'bilan ingliz tilida ishlash ko‘nikmalarini rivojlantiradi.'
               . '</p>',
            
            
            /*
             * Пустое значение = Seo component
             * сам построит canonical.
             */
            'canonical' => null,
            
            /*
             * Позже поставим нормальную OG картинку.
             */
            'og_image' => null,
            
            'robots' =>
               'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1',
            
            'created_at' => time(),
            
            'updated_at' => time(),
         ]);
      }
   }
   
   
   public function safeDown()
   {
      $this->dropTable('{{%seo_meta}}');
      
      $this->dropColumn(
         '{{%course_category}}',
         'sort_order'
      );
      
      $this->dropColumn(
         '{{%course_category}}',
         'show_on_home'
      );
   }
}