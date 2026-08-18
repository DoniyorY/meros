<?php

use yii\db\Migration;
use yii\db\Query;

final class m260818_153000_create_seo_meta_and_seed_course_categories extends Migration
{
   public function safeUp()
   {
      /*
       |--------------------------------------------------------------------------
       | COURSE CATEGORY: данные для карточек на главной
       |--------------------------------------------------------------------------
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
      
      $this->addColumn(
         '{{%course_category}}',
         'desc_ru',
         $this->string(500)->null()
      );
      
      $this->addColumn(
         '{{%course_category}}',
         'desc_en',
         $this->string(500)->null()
      );
      
      $this->addColumn(
         '{{%course_category}}',
         'desc_uz',
         $this->string(500)->null()
      );
      
      $this->addColumn(
         '{{%course_category}}',
         'home_image',
         $this->string(255)->null()
      );
      
      
      /*
       |--------------------------------------------------------------------------
       | SEO META
       |--------------------------------------------------------------------------
       |
       | Универсальная таблица:
       |
       | course_category + ID
       | course          + ID
       | post            + ID
       | event           + ID
       |
       */
      
      $this->createTable('{{%seo_meta}}', [
         'id' => $this->bigPrimaryKey()->unsigned(),
         
         'entity_type' => $this->string(50)->notNull(),
         
         /*
          * Полиморфная связь, поэтому FK здесь не ставим.
          */
         'entity_id' => $this->integer()->notNull(),
         
         
         /*
          * TITLE
          */
         'title_ru' => $this->string(255)->null(),
         'title_en' => $this->string(255)->null(),
         'title_uz' => $this->string(255)->null(),
         
         
         /*
          * META DESCRIPTION
          */
         'description_ru' => $this->text()->null(),
         'description_en' => $this->text()->null(),
         'description_uz' => $this->text()->null(),
         
         
         /*
          * H1
          */
         'h1_ru' => $this->string(255)->null(),
         'h1_en' => $this->string(255)->null(),
         'h1_uz' => $this->string(255)->null(),
         
         
         /*
          * Контент landing/category page.
          *
          * Можно хранить HTML.
          */
         'text_ru' => $this->text()->null(),
         'text_en' => $this->text()->null(),
         'text_uz' => $this->text()->null(),
         
         
         /*
          * OPTIONAL
          *
          * Если NULL — Seo component использует свои defaults.
          */
         'canonical' => $this->string(1000)->null(),
         'og_image' => $this->string(1000)->null(),
         'robots' => $this->string(255)->null(),
         
         'created_at' => $this->integer()->notNull(),
         'updated_at' => $this->integer()->notNull(),
      ]);
      
      
      /*
       * Одна SEO-запись на одну сущность.
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
         'idx-seo_meta-entity-type',
         '{{%seo_meta}}',
         'entity_type'
      );
      
      
      /*
       |--------------------------------------------------------------------------
       | INITIAL DATA
       |--------------------------------------------------------------------------
       */
      
      $this->seedMedicalEnglish();
      
      $this->seedOetIelts();
      
      $this->seedUniversityMaterials();
      
      $this->seedHealthcareEmployers();
   }
   
   
   /*
    |--------------------------------------------------------------------------
    | MEDICAL ENGLISH
    |--------------------------------------------------------------------------
    */
   
   private function seedMedicalEnglish(): void
   {
      $this->seedCategory(
         slug: 'medical-english',
         
         sortOrder: 10,
         
         shortDescriptions: [
            'ru' =>
               'Профессиональный английский для врачей, медсестёр, фармацевтов, студентов и других специалистов здравоохранения.',
            
            'en' =>
               'Professional Medical English for doctors, nurses, pharmacists, students and other healthcare professionals.',
            
            'uz' =>
               'Shifokorlar, hamshiralar, farmatsevtlar, talabalar va boshqa tibbiyot mutaxassislari uchun kasbiy ingliz tili.',
         ],
         
         seo: [
            /*
             * RU
             */
            'title_ru' =>
               'Медицинский английский в Узбекистане — онлайн-курсы | Meros',
            
            'description_ru' =>
               'Курсы медицинского английского в Узбекистане для врачей, медсестёр, фармацевтов, студентов и специалистов здравоохранения.',
            
            'h1_ru' =>
               'Медицинский английский в Узбекистане',
            
            'text_ru' => <<<'HTML'
<p>
    <strong>Медицинский английский от Meros — это специализированные программы для врачей, медсестёр, фармацевтов, студентов медицинских вузов и других специалистов здравоохранения.</strong>
    Курсы помогают развивать профессиональный английский для учёбы, работы, общения с пациентами и коллегами, чтения медицинской литературы и работы в международной медицинской среде.
</p>

<p>
    Программы охватывают разные уровни подготовки — от базового профессионального английского до продвинутого уровня — и позволяют выбрать направление в зависимости от профессии и целей обучения.
    В каталоге представлены английский для врачей и медсестёр, английский для специалистов по уходу, фармацевтов и специалистов в области рентгенологии, а также отдельные программы по медицинской терминологии и грамматике.
</p>

<p>
    Для студентов, исследователей и специалистов, которым английский необходим для академической деятельности, доступны программы по академическому медицинскому английскому и написанию научных публикаций.
    Они помогают работать с медицинскими статьями и исследованиями, готовить академические тексты и увереннее использовать английский язык в профессиональной и образовательной среде.
</p>

<p>
    Обучение ориентировано на реальные задачи специалистов здравоохранения.
    В зависимости от выбранного курса рассматриваются общение с пациентами, симптомы и заболевания, диагностика и лечение, медицинские процедуры, больничная документация, профессиональная терминология, грамматика и научная коммуникация.
</p>

<p>
    Выберите подходящий курс медицинского английского Meros и развивайте языковые навыки, необходимые для современной медицинской практики, образования и профессионального роста.
</p>
HTML,
            
            
            /*
             * EN
             */
            'title_en' =>
               'Medical English Courses in Uzbekistan | Meros',
            
            'description_en' =>
               'Medical English courses in Uzbekistan for doctors, nurses, pharmacists, students and healthcare professionals.',
            
            'h1_en' =>
               'Medical English Courses in Uzbekistan',
            
            'text_en' => <<<'HTML'
<p>
    <strong>Meros provides specialised Medical English programmes for doctors, nurses, pharmacists, medical students and other healthcare professionals.</strong>
    The courses develop the professional English required for study, work, communication with patients and colleagues, reading medical literature and participation in international healthcare environments.
</p>

<p>
    Programmes cover different levels of professional English and allow learners to choose a course according to their profession and goals.
    The Medical English catalogue includes English for Doctors, English for Nurses, English for Care, English for Pharmacy and English for Radiography, together with dedicated courses in Medical Terminology and healthcare grammar.
</p>

<p>
    Students, researchers and healthcare professionals who use English in academic settings can also study Medical Academic English and scientific publication writing.
    These programmes develop skills for working with research articles, academic texts, presentations and professional communication.
</p>

<p>
    Learning is based on practical healthcare contexts.
    Depending on the selected course, topics may include patient communication, symptoms and conditions, diagnosis and treatment, medical procedures, hospital documentation, professional terminology, grammar and scientific communication.
</p>

<p>
    Explore Meros Medical English courses and choose the programme that matches your professional, academic and language goals.
</p>
HTML,
            
            
            /*
             * UZ
             */
            'title_uz' =>
               'O‘zbekistonda tibbiy ingliz tili kurslari | Meros',
            
            'description_uz' =>
               'Shifokorlar, hamshiralar, farmatsevtlar, tibbiyot talabalari va mutaxassislar uchun O‘zbekistonda tibbiy ingliz tili kurslari.',
            
            'h1_uz' =>
               'O‘zbekistonda tibbiy ingliz tili kurslari',
            
            'text_uz' => <<<'HTML'
<p>
    <strong>Meros shifokorlar, hamshiralar, farmatsevtlar, tibbiyot talabalari va boshqa sog‘liqni saqlash mutaxassislari uchun maxsus tibbiy ingliz tili dasturlarini taklif etadi.</strong>
    Kurslar o‘qish, ish faoliyati, bemorlar va hamkasblar bilan muloqot qilish, tibbiy adabiyotlarni o‘qish va xalqaro tibbiyot muhitida ishlash uchun zarur kasbiy ingliz tilini rivojlantiradi.
</p>

<p>
    Dasturlar turli tayyorgarlik darajalarini qamrab oladi va kasb hamda o‘quv maqsadlariga qarab mos kursni tanlash imkonini beradi.
    Yo‘nalishlar orasida shifokorlar, hamshiralar, parvarish sohasi mutaxassislari, farmatsevtlar va radiografiya mutaxassislari uchun ingliz tili, shuningdek tibbiy terminologiya va tibbiyot xodimlari uchun grammatika kurslari mavjud.
</p>

<p>
    Ingliz tilidan akademik faoliyatda foydalanadigan talabalar, tadqiqotchilar va mutaxassislar uchun tibbiy akademik ingliz tili hamda ilmiy maqolalar yozish bo‘yicha dasturlar ham mavjud.
    Ular ilmiy maqolalar, tadqiqotlar, akademik matnlar va professional muloqot bilan ishlash ko‘nikmalarini rivojlantiradi.
</p>

<p>
    Ta’lim sog‘liqni saqlash sohasidagi amaliy vaziyatlarga asoslanadi.
    Tanlangan kursga qarab bemorlar bilan muloqot, simptom va kasalliklar, tashxis va davolash, tibbiy amaliyotlar, shifoxona hujjatlari, kasbiy terminologiya, grammatika va ilmiy muloqot mavzulari o‘rganiladi.
</p>

<p>
    Meros tibbiy ingliz tili kurslari orasidan maqsadingizga mos dasturni tanlang va kasbiy hamda akademik faoliyat uchun zarur til ko‘nikmalarini rivojlantiring.
</p>
HTML,
         ]
      );
   }
   
   
   /*
    |--------------------------------------------------------------------------
    | OET + IELTS
    |--------------------------------------------------------------------------
    */
   
   private function seedOetIelts(): void
   {
      $this->seedCategory(
         slug: 'oet-and-ielts-prep',
         
         sortOrder: 20,
         
         shortDescriptions: [
            'ru' =>
               'Подготовка к OET Medicine, OET Nursing и IELTS Academic с онлайн-курсами и индивидуальными занятиями.',
            
            'en' =>
               'Preparation for OET Medicine, OET Nursing and IELTS Academic with online courses and individual coaching.',
            
            'uz' =>
               'OET Medicine, OET Nursing va IELTS Academic imtihonlariga onlayn kurslar va individual darslar orqali tayyorgarlik.',
         ],
         
         seo: [
            /*
             * RU
             */
            'title_ru' =>
               'Подготовка к OET и IELTS в Узбекистане | Meros',
            
            'description_ru' =>
               'Подготовка к OET Medicine, OET Nursing и IELTS Academic в Узбекистане: онлайн-курсы и индивидуальные занятия Meros.',
            
            'h1_ru' =>
               'Подготовка к OET и IELTS в Узбекистане',
            
            'text_ru' => <<<'HTML'
<p>
    <strong>Meros предлагает программы подготовки к международным экзаменам OET и IELTS для медицинских специалистов, студентов и других кандидатов, которым английский необходим для учёбы, профессиональной регистрации или карьерного развития.</strong>
</p>

<p>
    Для врачей доступны программы подготовки к OET Medicine, включая Reach OET B Medicine и индивидуальные занятия.
    Для специалистов сестринского дела представлены Reach OET B Nursing и индивидуальная подготовка к OET Nursing.
</p>

<p>
    Направление IELTS включает онлайн-программу Reach IELTS и индивидуальную подготовку с преподавателем.
    Курсы помогают развивать языковые навыки, экзаменационные стратегии и уверенность, необходимые для достижения целевого результата.
</p>

<p>
    Выберите экзамен и формат подготовки, который соответствует вашим текущим знаниям, профессиональным задачам и планируемому результату.
</p>
HTML,
            
            
            /*
             * EN
             */
            'title_en' =>
               'OET and IELTS Preparation in Uzbekistan | Meros',
            
            'description_en' =>
               'OET Medicine, OET Nursing and IELTS Academic preparation in Uzbekistan with online courses and individual coaching from Meros.',
            
            'h1_en' =>
               'OET and IELTS Preparation in Uzbekistan',
            
            'text_en' => <<<'HTML'
<p>
    <strong>Meros provides preparation programmes for the international OET and IELTS examinations for healthcare professionals, students and other candidates who require English for study, professional registration or career development.</strong>
</p>

<p>
    Doctors can prepare through Reach OET B Medicine or individual OET Medicine coaching.
    Nursing professionals can choose Reach OET B Nursing or personalised OET Nursing preparation.
</p>

<p>
    IELTS preparation includes the Reach IELTS online programme and individual IELTS coaching.
    The courses focus on the language skills, exam techniques and strategies required to work towards the learner's target score.
</p>

<p>
    Choose the examination and study format that best matches your current level, professional plans and required result.
</p>
HTML,
            
            
            /*
             * UZ
             */
            'title_uz' =>
               'O‘zbekistonda OET va IELTSga tayyorgarlik | Meros',
            
            'description_uz' =>
               'OET Medicine, OET Nursing va IELTS Academic imtihonlariga O‘zbekistonda onlayn kurslar va individual darslar orqali tayyorgarlik.',
            
            'h1_uz' =>
               'O‘zbekistonda OET va IELTSga tayyorgarlik',
            
            'text_uz' => <<<'HTML'
<p>
    <strong>Meros tibbiyot mutaxassislari, talabalar va o‘qish, kasbiy ro‘yxatdan o‘tish yoki karyera rivoji uchun ingliz tili imtihoni natijasiga muhtoj nomzodlarga OET va IELTS xalqaro imtihonlariga tayyorgarlik dasturlarini taklif etadi.</strong>
</p>

<p>
    Shifokorlar Reach OET B Medicine dasturi yoki OET Medicine bo‘yicha individual tayyorgarlikni tanlashi mumkin.
    Hamshiralik mutaxassislari uchun Reach OET B Nursing va OET Nursing individual mashg‘ulotlari mavjud.
</p>

<p>
    IELTS yo‘nalishida Reach IELTS onlayn dasturi va o‘qituvchi bilan individual tayyorgarlik mavjud.
    Kurslar maqsadli natijaga erishish uchun zarur til ko‘nikmalari, imtihon texnikalari va strategiyalarini rivojlantirishga qaratilgan.
</p>

<p>
    Hozirgi bilim darajangiz, kasbiy rejalaringiz va kerakli natijaga mos imtihon hamda ta’lim formatini tanlang.
</p>
HTML,
         ]
      );
   }
   
   
   /*
    |--------------------------------------------------------------------------
    | UNIVERSITY MATERIALS
    |--------------------------------------------------------------------------
    */
   
   private function seedUniversityMaterials(): void
   {
      $this->seedCategory(
         slug: 'university-materials',
         
         sortOrder: 30,
         
         shortDescriptions: [
            'ru' =>
               'Программы медицинского английского для университетов, школ и образовательных организаций.',
            
            'en' =>
               'Medical English programmes for universities, schools and educational organisations.',
            
            'uz' =>
               'Universitetlar, maktablar va ta’lim tashkilotlari uchun tibbiy ingliz tili dasturlari.',
         ],
         
         seo: [
            'title_ru' =>
               'Медицинский английский для университетов и школ | Meros',
            
            'description_ru' =>
               'Программы медицинского английского Meros для университетов, школ и образовательных организаций.',
            
            'h1_ru' =>
               'Медицинский английский для университетов и школ',
            
            'text_ru' => <<<'HTML'
<p>
    <strong>Направление Meros для университетов и школ объединяет программы медицинского английского, предназначенные для образовательных организаций.</strong>
</p>

<p>
    Здесь представлены решения для учебных заведений, которым необходимо включить профессиональный медицинский английский в образовательный процесс.
</p>

<p>
    Ознакомьтесь с доступными программами Meros для университетов и школ и выберите подходящее направление обучения.
</p>
HTML,
            
            
            'title_en' =>
               'Medical English for Universities and Schools | Meros',
            
            'description_en' =>
               'Meros Medical English programmes for universities, schools and educational organisations.',
            
            'h1_en' =>
               'Medical English for Universities and Schools',
            
            'text_en' => <<<'HTML'
<p>
    <strong>The Meros University Materials section brings together Medical English programmes intended for universities, schools and educational organisations.</strong>
</p>

<p>
    It provides a dedicated route for institutions looking to incorporate profession-focused Medical English into their educational programmes.
</p>

<p>
    Explore the available Meros programmes for universities and schools and choose the option that matches your institution's needs.
</p>
HTML,
            
            
            'title_uz' =>
               'Universitetlar va maktablar uchun tibbiy ingliz tili | Meros',
            
            'description_uz' =>
               'Universitetlar, maktablar va ta’lim tashkilotlari uchun Meros tibbiy ingliz tili dasturlari.',
            
            'h1_uz' =>
               'Universitetlar va maktablar uchun tibbiy ingliz tili',
            
            'text_uz' => <<<'HTML'
<p>
    <strong>Merosning universitetlar va maktablar uchun yo‘nalishi ta’lim tashkilotlariga mo‘ljallangan tibbiy ingliz tili dasturlarini bir joyda taqdim etadi.</strong>
</p>

<p>
    Ushbu yo‘nalish professional tibbiy ingliz tilini o‘quv jarayoniga kiritishni rejalashtirayotgan ta’lim muassasalari uchun mo‘ljallangan.
</p>

<p>
    Universitetlar va maktablar uchun mavjud Meros dasturlari bilan tanishing va tashkilotingizga mos ta’lim yo‘nalishini tanlang.
</p>
HTML,
         ]
      );
   }
   
   
   /*
    |--------------------------------------------------------------------------
    | HEALTHCARE EMPLOYERS
    |--------------------------------------------------------------------------
    */
   
   private function seedHealthcareEmployers(): void
   {
      $this->seedCategory(
         slug: 'healthcare-employers',
         
         sortOrder: 40,
         
         shortDescriptions: [
            'ru' =>
               'Образовательные решения Meros для больниц и организаций, работающих с медицинскими специалистами.',
            
            'en' =>
               'Meros education solutions for hospitals and organisations working with healthcare professionals.',
            
            'uz' =>
               'Kasalxonalar va tibbiyot mutaxassislari bilan ishlaydigan tashkilotlar uchun Meros ta’lim yechimlari.',
         ],
         
         seo: [
            'title_ru' =>
               'Английский для медицинских организаций и работодателей | Meros',
            
            'description_ru' =>
               'Образовательные решения Meros для больниц, медицинских работодателей и компаний по подбору персонала в сфере здравоохранения.',
            
            'h1_ru' =>
               'Решения для медицинских организаций и работодателей',
            
            'text_ru' => <<<'HTML'
<p>
    <strong>Meros предлагает отдельное направление для организаций, работающих в сфере здравоохранения и с медицинскими специалистами.</strong>
</p>

<p>
    В разделе представлены решения для больниц и компаний по подбору персонала в сфере здравоохранения.
    Они позволяют организациям перейти непосредственно к программам и услугам, предназначенным для их профессиональных задач.
</p>

<p>
    Выберите подходящее направление Meros для вашей медицинской организации или команды.
</p>
HTML,
            
            
            'title_en' =>
               'Medical English for Healthcare Employers | Meros',
            
            'description_en' =>
               'Meros education solutions for hospitals, healthcare employers and healthcare recruitment companies.',
            
            'h1_en' =>
               'Solutions for Healthcare Employers',
            
            'text_en' => <<<'HTML'
<p>
    <strong>Meros provides a dedicated section for organisations working in healthcare and with healthcare professionals.</strong>
</p>

<p>
    The section includes solutions for hospitals and healthcare recruitment companies, giving organisations direct access to programmes intended for their professional needs.
</p>

<p>
    Explore the available Meros options and choose the direction that best matches your organisation or healthcare team.
</p>
HTML,
            
            
            'title_uz' =>
               'Tibbiyot tashkilotlari va ish beruvchilar uchun ingliz tili | Meros',
            
            'description_uz' =>
               'Kasalxonalar, sog‘liqni saqlash sohasi ish beruvchilari va tibbiy kadrlarni yollash kompaniyalari uchun Meros ta’lim yechimlari.',
            
            'h1_uz' =>
               'Tibbiyot tashkilotlari va ish beruvchilar uchun yechimlar',
            
            'text_uz' => <<<'HTML'
<p>
    <strong>Meros sog‘liqni saqlash sohasida va tibbiyot mutaxassislari bilan ishlaydigan tashkilotlar uchun alohida yo‘nalishni taklif etadi.</strong>
</p>

<p>
    Ushbu bo‘limda kasalxonalar hamda sog‘liqni saqlash sohasidagi xodimlarni yollash kompaniyalari uchun yo‘nalishlar mavjud.
    Tashkilotlar o‘z kasbiy ehtiyojlariga mos dastur va xizmatlarga to‘g‘ridan-to‘g‘ri o‘tishi mumkin.
</p>

<p>
    Tibbiyot tashkilotingiz yoki jamoangiz uchun mos Meros yo‘nalishini tanlang.
</p>
HTML,
         ]
      );
   }
   
   
   /*
    |--------------------------------------------------------------------------
    | SEED HELPER
    |--------------------------------------------------------------------------
    */
   
   private function seedCategory(
      string $slug,
      int $sortOrder,
      array $shortDescriptions,
      array $seo
   ): void {
      $category = (new Query())
         ->select([
            'id',
            'slug',
         ])
         ->from('{{%course_category}}')
         ->where([
            'slug' => $slug,
            'status' => 1,
         ])
         ->one();
      
      /*
       * Если на какой-то базе категории нет,
       * миграция не должна упасть целиком.
       */
      if (!$category) {
         echo "Course category '{$slug}' not found. SEO seed skipped.\n";
         return;
      }
      
      $categoryId = (int)$category['id'];
      
      /*
       * Карточка на главной.
       */
      $this->update(
         '{{%course_category}}',
         [
            'show_on_home' => 1,
            
            'sort_order' =>
               $sortOrder,
            
            'desc_ru' =>
               $shortDescriptions['ru'] ?? null,
            
            'desc_en' =>
               $shortDescriptions['en'] ?? null,
            
            'desc_uz' =>
               $shortDescriptions['uz'] ?? null,
         ],
         [
            'id' => $categoryId,
         ]
      );
      
      
      /*
       * SEO
       */
      $this->insert(
         '{{%seo_meta}}',
         array_merge(
            [
               'entity_type' =>
                  'course_category',
               
               'entity_id' =>
                  $categoryId,
               
               /*
                * NULL = берём defaults
                * из Seo component.
                */
               'canonical' =>
                  null,
               
               'og_image' =>
                  null,
               
               'robots' =>
                  null,
               
               'created_at' =>
                  time(),
               
               'updated_at' =>
                  time(),
            ],
            $seo
         )
      );
   }
   
   
   public function safeDown()
   {
      $this->dropTable('{{%seo_meta}}');
      
      $this->dropColumn(
         '{{%course_category}}',
         'home_image'
      );
      
      $this->dropColumn(
         '{{%course_category}}',
         'desc_uz'
      );
      
      $this->dropColumn(
         '{{%course_category}}',
         'desc_en'
      );
      
      $this->dropColumn(
         '{{%course_category}}',
         'desc_ru'
      );
      
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