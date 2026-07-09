<?php

use yii\helpers\Html;

$lang = Yii::$app->language;
$t = static function ($key) use ($lang) {
   $copy = [
      'meta_description' => [
         'ru' => 'Meros для международных медицинских рекрутинговых проектов: оценка английского, подготовка к OET/IELTS/OSCE и сопровождение кандидатов.',
         'en' => 'Meros services for international healthcare recruitment: English assessment, OET/IELTS/OSCE preparation and candidate support.',
         'uz' => 'Xalqaro tibbiy rekruting uchun Meros xizmatlari: ingliz tilini baholash, OET/IELTS/OSCE tayyorgarligi va nomzodlarni qo‘llab-quvvatlash.',
      ],
      'meta_keywords' => [
         'ru' => 'healthcare recruitment, медицинский рекрутинг, OET, IELTS, OSCE, медицинский английский, Meros',
         'en' => 'healthcare recruitment, international healthcare recruitment, OET, IELTS, OSCE, Medical English, Meros',
         'uz' => 'tibbiy rekruting, xalqaro rekruting, OET, IELTS, OSCE, tibbiy ingliz tili, Meros',
      ],
      'page_title' => [
         'ru' => 'Международные проекты медицинского рекрутинга',
         'en' => 'International Healthcare Recruitment Projects',
         'uz' => 'Xalqaro tibbiy rekruting loyihalari',
      ],
      'hero_kicker' => [
         'ru' => 'Healthcare employers / Recruitment',
         'en' => 'Healthcare employers / Recruitment',
         'uz' => 'Healthcare employers / Recruitment',
      ],
      'hero_title' => [
         'ru' => 'Языковая подготовка для медицинских рекрутинговых компаний',
         'en' => 'Language support for healthcare recruitment companies',
         'uz' => 'Tibbiy rekruting kompaniyalari uchun til bo‘yicha yordam',
      ],
      'hero_text' => [
         'ru' => 'Помогаем понять уровень английского кандидатов, выбрать короткий путь до нужного балла и предложить работодателям понятную систему обучения и сопровождения.',
         'en' => 'Understand candidates’ English level, define the fastest route to required scores, and offer employers a clear training and support pathway.',
         'uz' => 'Nomzodlarning ingliz tili darajasini aniqlang, kerakli ballga eng tez yo‘lni belgilang va ish beruvchilarga aniq o‘quv hamda qo‘llab-quvvatlash yo‘lini taklif qiling.',
      ],
      'cta_primary' => [
         'ru' => 'Обсудить проект',
         'en' => 'Discuss a project',
         'uz' => 'Loyihani muhokama qilish',
      ],
      'cta_secondary' => [
         'ru' => 'Смотреть услуги',
         'en' => 'View services',
         'uz' => 'Xizmatlarni ko‘rish',
      ],
      'intro_kicker' => [
         'ru' => 'Партнёр по языку',
         'en' => 'Your language partner',
         'uz' => 'Til bo‘yicha hamkoringiz',
      ],
      'intro_title' => [
         'ru' => 'От первичной оценки до готовности к трудоустройству',
         'en' => 'From first assessment to recruitment readiness',
         'uz' => 'Dastlabki baholashdan ishga tayyorlikkacha',
      ],
      'intro_text' => [
         'ru' => 'Meros помогает рекрутинговым командам работать с врачами, медсёстрами, allied health professionals и care staff: оценивать уровень, планировать подготовку, отслеживать прогресс и поддерживать кандидатов перед переездом.',
         'en' => 'Meros helps recruitment teams support doctors, nurses, allied health professionals and care staff by assessing level, planning training, tracking progress and preparing candidates for relocation.',
         'uz' => 'Meros rekruting jamoalariga shifokorlar, hamshiralar, allied health professionals va care staff bilan ishlashda yordam beradi: darajani baholash, tayyorgarlikni rejalash, progressni kuzatish va ko‘chishga tayyorlash.',
      ],
      'services_kicker' => [
         'ru' => 'Что можно подключить',
         'en' => 'Services you can connect',
         'uz' => 'Ulanadigan xizmatlar',
      ],
      'services_title' => [
         'ru' => 'Гибкий набор решений для разных этапов рекрутинга',
         'en' => 'A flexible toolkit for every recruitment stage',
         'uz' => 'Rekrutingning har bosqichi uchun moslashuvchan yechimlar',
      ],
      'platform_kicker' => [
         'ru' => 'Платформа для партнёров',
         'en' => 'Partner learning platform',
         'uz' => 'Hamkorlar uchun o‘quv platforma',
      ],
      'platform_title' => [
         'ru' => 'Контроль прогресса и прозрачная отчётность',
         'en' => 'Progress visibility and clear reporting',
         'uz' => 'Progress nazorati va tushunarli hisobotlar',
      ],
      'platform_text' => [
         'ru' => 'Подключайте группы кандидатов, смотрите активность, результаты тестов, посещаемость занятий и готовьте отчёты для работодателей и внутренних команд.',
         'en' => 'Enrol candidate cohorts, review activity, test results and lesson attendance, then share concise reports with employers and internal teams.',
         'uz' => 'Nomzodlar guruhlarini ulang, faollik, test natijalari va darslarga qatnashni ko‘ring, ish beruvchilar va ichki jamoalar uchun hisobot ulashing.',
      ],
      'support_kicker' => [
         'ru' => 'Поддержка кандидатов',
         'en' => 'Candidate support',
         'uz' => 'Nomzodlarni qo‘llab-quvvatlash',
      ],
      'support_title' => [
         'ru' => 'Не только экзамен: язык для переезда и работы',
         'en' => 'More than exams: language for relocation and work',
         'uz' => 'Faqat imtihon emas: ko‘chish va ish uchun til',
      ],
      'support_text' => [
         'ru' => 'Курсы могут включать практику общения с пациентами и коллегами, адаптацию к новой системе здравоохранения, культурные темы, повседневный английский и подготовку к OSCE.',
         'en' => 'Programmes can include patient and colleague communication, orientation to a new healthcare system, cultural topics, everyday English and OSCE preparation.',
         'uz' => 'Dasturlarga bemor va hamkasblar bilan muloqot, yangi sog‘liqni saqlash tizimiga moslashuv, madaniy mavzular, kundalik ingliz tili va OSCE tayyorgarligi kirishi mumkin.',
      ],
      'final_title' => [
         'ru' => 'Запустите языковой поток для ваших кандидатов',
         'en' => 'Launch a language pathway for your candidates',
         'uz' => 'Nomzodlaringiz uchun til yo‘nalishini ishga tushiring',
      ],
      'final_text' => [
         'ru' => 'Расскажите о странах, профессиях, сроках и требуемых экзаменах — мы предложим структуру оценки, обучения и отчётности.',
         'en' => 'Tell us about countries, professions, timelines and required exams — we will suggest an assessment, training and reporting structure.',
         'uz' => 'Davlatlar, kasblar, muddatlar va kerakli imtihonlarni ayting — biz baholash, o‘qitish va hisobot tuzilmasini taklif qilamiz.',
      ],
   ];

   return $copy[$key][$lang] ?? $copy[$key]['en'] ?? $key;
};
$tList = static function ($key) use ($lang) {
   $copy = [
      'stats' => [
         'ru' => [['value' => 'OET', 'label' => 'подготовка'], ['value' => 'IELTS', 'label' => 'подготовка'], ['value' => 'OSCE', 'label' => 'support']],
         'en' => [['value' => 'OET', 'label' => 'preparation'], ['value' => 'IELTS', 'label' => 'preparation'], ['value' => 'OSCE', 'label' => 'support']],
         'uz' => [['value' => 'OET', 'label' => 'tayyorgarlik'], ['value' => 'IELTS', 'label' => 'tayyorgarlik'], ['value' => 'OSCE', 'label' => 'support']],
      ],
      'services' => [
         'ru' => [
            ['icon' => 'bi bi-graph-up-arrow', 'title' => 'Оценка уровня', 'text' => 'Онлайн-тестирование показывает текущий уровень английского и готовность к международным экзаменам.'],
            ['icon' => 'bi-mortarboard', 'title' => 'Подготовка к тестам', 'text' => 'Индивидуальные и групповые занятия под цели OET, IELTS и профессионального английского.'],
            ['icon' => 'bi-activity', 'title' => 'OSCE и клиническая коммуникация', 'text' => 'Цифровые материалы и практика сценариев помогают кандидатам увереннее проходить станции и общаться с пациентами.'],
            ['icon' => 'bi-airplane', 'title' => 'Адаптация перед переездом', 'text' => 'Культура, система здравоохранения, повседневные ситуации и коммуникация на новом рабочем месте.'],
         ],
         'en' => [
            ['icon' => 'bi bi-graph-up-arrow', 'title' => 'English level assessment', 'text' => 'Online testing clarifies current English level and readiness for international healthcare exams.'],
            ['icon' => 'bi-mortarboard', 'title' => 'Test preparation', 'text' => 'One-to-one and group programmes for OET, IELTS and professional Medical English goals.'],
            ['icon' => 'bi-activity', 'title' => 'OSCE and clinical communication', 'text' => 'Digital materials and scenario practice help candidates approach stations and patient interaction with confidence.'],
            ['icon' => 'bi-airplane', 'title' => 'Pre-arrival orientation', 'text' => 'Culture, healthcare systems, everyday situations and communication for a new workplace.'],
         ],
         'uz' => [
            ['icon' => 'bi bi-graph-up-arrow', 'title' => 'Ingliz tilini baholash', 'text' => 'Onlayn test hozirgi daraja va xalqaro tibbiy imtihonlarga tayyorlikni aniqlaydi.'],
            ['icon' => 'bi-mortarboard', 'title' => 'Testlarga tayyorgarlik', 'text' => 'OET, IELTS va professional tibbiy ingliz tili uchun individual hamda guruh dasturlari.'],
            ['icon' => 'bi-activity', 'title' => 'OSCE va klinik muloqot', 'text' => 'Raqamli materiallar va ssenariy mashqlari stansiyalar hamda bemor bilan muloqotda ishonch beradi.'],
            ['icon' => 'bi-airplane', 'title' => 'Ketishdan oldingi moslashuv', 'text' => 'Madaniyat, sog‘liqni saqlash tizimi, kundalik vaziyatlar va yangi ish joyidagi muloqot.'],
         ],
      ],
      'process' => [
         'ru' => [
            ['step' => '01', 'title' => 'Диагностика', 'text' => 'Определяем уровень, цель, сроки и риск-профиль каждого кандидата.'],
            ['step' => '02', 'title' => 'План обучения', 'text' => 'Формируем группы и маршруты по профессии, уровню и требуемому экзамену.'],
            ['step' => '03', 'title' => 'Прогресс и отчёты', 'text' => 'Отслеживаем результаты и помогаем принимать решения по готовности кандидатов.'],
         ],
         'en' => [
            ['step' => '01', 'title' => 'Diagnose', 'text' => 'Identify level, target, timeline and risk profile for each candidate.'],
            ['step' => '02', 'title' => 'Plan training', 'text' => 'Build cohorts and pathways around profession, level and required exam.'],
            ['step' => '03', 'title' => 'Report progress', 'text' => 'Track results and support decisions about candidate readiness.'],
         ],
         'uz' => [
            ['step' => '01', 'title' => 'Diagnostika', 'text' => 'Har bir nomzod darajasi, maqsadi, muddati va risk profilini aniqlaymiz.'],
            ['step' => '02', 'title' => 'O‘quv reja', 'text' => 'Kasb, daraja va kerakli imtihonga ko‘ra guruhlar va yo‘nalishlar tuzamiz.'],
            ['step' => '03', 'title' => 'Progress hisoboti', 'text' => 'Natijalarni kuzatamiz va nomzod tayyorligi bo‘yicha qarorlarni qo‘llab-quvvatlaymiz.'],
         ],
      ],
   ];

   return $copy[$key][$lang] ?? $copy[$key]['en'] ?? [];
};

$this->registerMetaTag(['name' => 'description', 'content' => $t('meta_description')]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $t('meta_keywords')]);
$this->title = $t('page_title');

$base = Yii::$app->request->baseUrl;
$courseName = $courses->{"name_$lang"} ?: $courses->name_en;
$this->params['breadcrumbs'][] = $courseName;
$courseDescription = $courses->{"desc_$lang"} ?: $courses->desc_en;
$heroImage = $courses->image ? "$base/uploads/courses/$courses->image" : "$base/images/meros_hospital.jpg";
$courseIcon = $courses->course_icons ? "$base/uploads/course_icons/$courses->course_icons" : "$base/slc_logo_white.png";
$consultationSubject = rawurlencode($t('page_title'));
$stats = $tList('stats');
$services = $tList('services');
$process = $tList('process');
?>


<section id="course-banner" class="meros-course-hero meros-recruit-hero reveal-section" aria-label="<?= Html::encode($t('page_title')) ?>">
   <div class="position-relative meros-course-hero-bg" style="background-image: url(<?= Html::encode($heroImage) ?>)">
      <div class="container h-100">
         <div class="row h-100 align-items-center g-5">
            <div class="col-lg-7 col-12">
               <div class="course-banner-caption meros-course-caption text-start w-100 px-3">
                  <img src="<?= Html::encode($courseIcon) ?>" alt="Meros" class="mb-4">
                  <span class="meros-kicker"><?= Html::encode($t('hero_kicker')) ?></span>
                  <h1 class="course-banner-subtitle mb-4"><?= Html::encode($t('hero_title')) ?></h1>
                  <h2 class="mb-4"><?= Html::encode($courseName) ?></h2>
                  <p class="text-white fs-5 mb-4"><?= Html::encode($t('hero_text')) ?></p>
                  <div class="d-flex flex-wrap gap-3">
                     <a href="mailto:info@merosedu.uz?subject=<?= $consultationSubject ?>" class="btn btn-primary btn-lg meros-primary-btn"><?= Html::encode($t('cta_primary')) ?></a>
                     <a href="#recruit-services" class="btn btn-outline-light btn-lg rounded-pill px-4"><?= Html::encode($t('cta_secondary')) ?></a>
                  </div>
               </div>
            </div>
            <div class="col-lg-5 col-12">
               <div class="meros-recruit-card reveal-section">
                  <span class="meros-kicker"><?= Html::encode($t('platform_kicker')) ?></span>
                  <h3><?= Html::encode($t('platform_title')) ?></h3>
                  <p><?= Html::encode($t('platform_text')) ?></p>
                  <div class="row g-3 mt-2">
                     <?php foreach ($stats as $item): ?>
                        <div class="col-4"><div class="meros-recruit-stat"><strong><?= Html::encode($item['value']) ?></strong><span><?= Html::encode($item['label']) ?></span></div></div>
                     <?php endforeach; ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<div id="page-content" class="meros-modern-page meros-course-page meros-recruit-page">
   <section class="meros-section reveal-section">
      <div class="container">
         <div class="row g-5 align-items-center">
            <div class="col-lg-6">
               <span class="meros-kicker"><?= Html::encode($t('intro_kicker')) ?></span>
               <h2><?= Html::encode($t('intro_title')) ?></h2>
               <p><?= Html::encode($t('intro_text')) ?></p>
            </div>
            <div class="col-lg-6"><div class="meros-about-card"><h2><?= Html::encode($courseName) ?></h2><?= ($courseDescription == '-')?'':$courseDescription ?></div></div>
         </div>
      </div>
   </section>

   <section id="recruit-services" class="meros-section reveal-section">
      <div class="container">
         <div class="text-center meros-section-heading"><span class="meros-kicker"><?= Html::encode($t('services_kicker')) ?></span><h2><?= Html::encode($t('services_title')) ?></h2></div>
         <div class="row g-4">
            <?php foreach ($services as $service): ?>
               <div class="col-lg-3 col-md-6"><article class="meros-recruit-card meros-recruit-service"><div class="meros-recruit-icon bi <?= Html::encode($service['icon']) ?>"></div><h3><?= Html::encode($service['title']) ?></h3><p><?= Html::encode($service['text']) ?></p></article></div>
            <?php endforeach; ?>
         </div>
      </div>
   </section>

   <section class="meros-section reveal-section">
      <div class="container">
         <div class="meros-recruit-highlight">
            <div class="row g-5 align-items-center">
               <div class="col-lg-5"><span class="meros-kicker"><?= Html::encode($t('support_kicker')) ?></span><h2><?= Html::encode($t('support_title')) ?></h2><p><?= Html::encode($t('support_text')) ?></p></div>
               <div class="col-lg-7">
                  <div class="row g-4">
                     <?php foreach ($process as $step): ?>
                        <div class="col-12"><div class="meros-recruit-card meros-recruit-step"><div class="meros-recruit-step-number"><?= Html::encode($step['step']) ?></div><div><h3><?= Html::encode($step['title']) ?></h3><p class="mb-0"><?= Html::encode($step['text']) ?></p></div></div></div>
                     <?php endforeach; ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>

   <section class="meros-section reveal-section">
      <div class="container"><div class="meros-recruit-cta text-center"><span class="meros-kicker"><?= Html::encode($t('hero_kicker')) ?></span><h2><?= Html::encode($t('final_title')) ?></h2><p class="mx-auto mb-4 meros-recruit-cta-text"><?= Html::encode($t('final_text')) ?></p><a href="mailto:info@merosedu.uz?subject=<?= $consultationSubject ?>" class="btn btn-light btn-lg rounded-pill px-5"><?= Html::encode($t('cta_primary')) ?></a></div></div>
   </section>
</div>
