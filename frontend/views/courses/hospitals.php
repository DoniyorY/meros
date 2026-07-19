<?php

use yii\helpers\Html;

$params = Yii::$app->params;
$lang = Yii::$app->language;
$t = static function ($key) use ($lang) {
   $copy = [
      'meta_description' => [
         'ru' => 'B2B-программы медицинского английского Meros для больниц, клиник и сетей здравоохранения',
         'en' => 'Meros B2B Medical English programmes for hospitals, clinics and healthcare networks',
         'uz' => 'Shifoxonalar, klinikalar va sog‘liqni saqlash tarmoqlari uchun Meros B2B tibbiy ingliz tili dasturlari',
      ],
      'meta_keywords' => [
         'ru' => 'медицинский английский для больниц, английский для клиник, healthcare employers, Meros',
         'en' => 'Medical English for hospitals, English for clinics, healthcare employers, Meros',
         'uz' => 'shifoxonalar uchun tibbiy ingliz tili, klinikalar uchun ingliz tili, Meros',
      ],
      'page_title' => [
         'ru' => 'Медицинский английский для больниц и клиник',
         'en' => 'Medical English for Hospitals and Clinics',
         'uz' => 'Shifoxonalar va klinikalar uchun tibbiy ingliz tili',
      ],
      'hero_kicker' => [
         'ru' => 'Healthcare employers / Hospitals',
         'en' => 'Healthcare employers / Hospitals',
         'uz' => 'Healthcare employers / Hospitals',
      ],
      'hero_title' => [
         'ru' => 'Английский для команд больниц, клиник и медицинских центров',
         'en' => 'English for hospital, clinic and medical centre teams',
         'uz' => 'Shifoxona, klinika va tibbiyot markazlari jamoalari uchun ingliz tili',
      ],
      'hero_text' => [
         'ru' => 'Обучайте врачей, медсестёр, регистратуру и сервисные команды медицинскому английскому, который нужен для международных пациентов, безопасной коммуникации и качественного сервиса.',
         'en' => 'Train doctors, nurses, reception and service teams in Medical English for international patients, safer communication and stronger patient experience.',
         'uz' => 'Shifokorlar, hamshiralar, registratura va servis jamoalarini xalqaro bemorlar, xavfsiz muloqot va sifatli xizmat uchun zarur tibbiy ingliz tiliga o‘qiting.',
      ],
      'cta_primary' => [
         'ru' => 'Запросить корпоративную консультацию',
         'en' => 'Request a corporate consultation',
         'uz' => 'Korporativ konsultatsiya so‘rash',
      ],
      'cta_secondary' => [
         'ru' => 'Посмотреть направления',
         'en' => 'View pathways',
         'uz' => 'Yo‘nalishlarni ko‘rish',
      ],
      'platform_kicker' => [
         'ru' => 'Для работодателей в здравоохранении',
         'en' => 'For healthcare employers',
         'uz' => 'Sog‘liqni saqlash ish beruvchilari uchun',
      ],
      'platform_title' => [
         'ru' => 'Запуск без сложной методической подготовки',
         'en' => 'Launch without heavy curriculum work',
         'uz' => 'Murakkab metodik tayyorgarliksiz ishga tushirish',
      ],
      'platform_text' => [
         'ru' => 'Готовые курсы, сценарии для клиники, контроль прогресса и гибкий доступ для сменных графиков сотрудников.',
         'en' => 'Ready courses, clinic-ready scenarios, progress visibility and flexible access for shift-based staff schedules.',
         'uz' => 'Tayyor kurslar, klinika uchun ssenariylar, progress nazorati va smenali ish jadvali uchun moslashuvchan kirish.',
      ],
      'challenge_kicker' => [
         'ru' => 'Зачем это клинике',
         'en' => 'Why clinics need it',
         'uz' => 'Nega klinikaga kerak',
      ],
      'challenge_title' => [
         'ru' => 'Пациентский опыт зависит от понятной коммуникации',
         'en' => 'Patient experience depends on clear communication',
         'uz' => 'Bemor tajribasi aniq muloqotga bog‘liq',
      ],
      'challenge_text' => [
         'ru' => 'Иностранный пациент оценивает не только лечение, но и запись, объяснение процедур, согласие, инструкции после приёма и поддержку на каждом этапе визита.',
         'en' => 'International patients judge not only treatment, but booking, procedure explanations, consent, aftercare instructions and support throughout the visit.',
         'uz' => 'Xalqaro bemor nafaqat davolanishni, balki yozilish, protsedura tushuntirishlari, rozilik, keyingi parvarish ko‘rsatmalari va tashrif davomidagi yordamni ham baholaydi.',
      ],
      'pathways_kicker' => [
         'ru' => 'Направления обучения',
         'en' => 'Training pathways',
         'uz' => 'O‘quv yo‘nalishlari',
      ],
      'pathways_title' => [
         'ru' => 'Материалы под разные роли в больнице',
         'en' => 'Materials for different hospital roles',
         'uz' => 'Shifoxonadagi turli rollar uchun materiallar',
      ],
      'about_kicker' => [
         'ru' => 'О программе',
         'en' => 'About the programme',
         'uz' => 'Dastur haqida',
      ],
      'roles_kicker' => [
         'ru' => 'Кого подключать',
         'en' => 'Who to enrol',
         'uz' => 'Kimlarni ulash kerak',
      ],
      'roles_title' => [
         'ru' => 'Единая языковая база для всей клиники',
         'en' => 'One language foundation for the whole clinic',
         'uz' => 'Butun klinika uchun yagona til bazasi',
      ],
      'final_title' => [
         'ru' => 'Подготовьте команду к работе с международными пациентами',
         'en' => 'Prepare your team for international patients',
         'uz' => 'Jamoangizni xalqaro bemorlar bilan ishlashga tayyorlang',
      ],
      'final_text' => [
         'ru' => 'Расскажите о количестве сотрудников, ролях и целях клиники — мы предложим программу внедрения и формат доступа.',
         'en' => 'Tell us about staff numbers, roles and clinic goals — we will recommend a rollout plan and access format.',
         'uz' => 'Xodimlar soni, rollar va klinika maqsadlarini ayting — biz joriy etish rejasi va kirish formatini tavsiya qilamiz.',
      ],
   ];

   return $copy[$key][$lang] ?? $copy[$key]['en'] ?? $key;
};
$tList = static function ($key) use ($lang) {
   $copy = [
      'challenge_cards' => [
         'ru' => [
            ['icon' => 'bi-person-check', 'title' => 'Клинические консультации', 'text' => 'Язык для жалоб, анамнеза, объяснения диагноза и плана лечения.'],
            ['icon' => 'bi-activity', 'title' => 'Безопасные процедуры', 'text' => 'Инструкции, согласие, подготовка пациента и коммуникация во время процедур.'],
            ['icon' => 'bi-chat-dots', 'title' => 'Сервис и регистратура', 'text' => 'Запись, навигация, платежи, сопровождение и ответы на частые вопросы.'],
         ],
         'en' => [
            ['icon' => 'bi-person-check', 'title' => 'Clinical consultations', 'text' => 'Language for symptoms, history taking, diagnosis explanation and treatment plans.'],
            ['icon' => 'bi-activity', 'title' => 'Safer procedures', 'text' => 'Instructions, consent, patient preparation and communication during procedures.'],
            ['icon' => 'bi-chat-dots', 'title' => 'Service and reception', 'text' => 'Booking, navigation, payments, patient support and answers to common questions.'],
         ],
         'uz' => [
            ['icon' => 'bi-person-check', 'title' => 'Klinik konsultatsiyalar', 'text' => 'Shikoyatlar, anamnez, tashxis va davolash rejasini tushuntirish tili.'],
            ['icon' => 'bi-activity', 'title' => 'Xavfsiz protseduralar', 'text' => 'Ko‘rsatmalar, rozilik, bemorni tayyorlash va protsedura paytidagi muloqot.'],
            ['icon' => 'bi-chat-dots', 'title' => 'Servis va registratura', 'text' => 'Yozilish, yo‘naltirish, to‘lovlar, bemorni kuzatish va savollarga javoblar.'],
         ],
      ],
      'pathways' => [
         'ru' => [
            ['title' => 'Врачи и специалисты', 'level' => 'B1-C1', 'text' => 'Консультации, клинические случаи, объяснение рисков и междисциплинарная коммуникация.'],
            ['title' => 'Медсёстры', 'level' => 'A2-B2', 'text' => 'Уход, инструкции пациенту, передача смены, наблюдение и документация.'],
            ['title' => 'Регистратура и call-center', 'level' => 'A2-B1', 'text' => 'Запись, маршрутизация пациента, подтверждения, перенос визитов и базовая поддержка.'],
            ['title' => 'Медицинский туризм', 'level' => 'B1-B2', 'text' => 'Сопровождение иностранных пациентов, описание услуг, пакеты лечения и aftercare.'],
         ],
         'en' => [
            ['title' => 'Doctors and specialists', 'level' => 'B1-C1', 'text' => 'Consultations, clinical cases, risk explanation and multidisciplinary communication.'],
            ['title' => 'Nurses', 'level' => 'A2-B2', 'text' => 'Care, patient instructions, handovers, observation and documentation.'],
            ['title' => 'Reception and call centre', 'level' => 'A2-B1', 'text' => 'Booking, patient routing, confirmations, rescheduling and basic support.'],
            ['title' => 'Medical tourism', 'level' => 'B1-B2', 'text' => 'International patient support, service descriptions, treatment packages and aftercare.'],
         ],
         'uz' => [
            ['title' => 'Shifokorlar va mutaxassislar', 'level' => 'B1-C1', 'text' => 'Konsultatsiyalar, klinik holatlar, xavflarni tushuntirish va jamoalararo muloqot.'],
            ['title' => 'Hamshiralar', 'level' => 'A2-B2', 'text' => 'Parvarish, bemorga ko‘rsatmalar, smena topshirish, kuzatuv va hujjatlar.'],
            ['title' => 'Registratura va call-center', 'level' => 'A2-B1', 'text' => 'Yozish, bemorni yo‘naltirish, tasdiqlash, vaqtni ko‘chirish va asosiy yordam.'],
            ['title' => 'Tibbiy turizm', 'level' => 'B1-B2', 'text' => 'Xalqaro bemorlarni qo‘llab-quvvatlash, xizmatlar, davolash paketlari va aftercare.'],
         ],
      ],
      'roles' => [
         'ru' => [
            ['role' => 'Руководство клиники', 'text' => 'Получает масштабируемую программу обучения и понятную картину прогресса команд.'],
            ['role' => 'HR и L&D', 'text' => 'Могут подключать группы по отделениям, сменам и приоритетным ролям.'],
            ['role' => 'Сотрудники', 'text' => 'Практикуют язык, который сразу применим в приёме, отделении и коммуникации с пациентом.'],
         ],
         'en' => [
            ['role' => 'Clinic leadership', 'text' => 'Gets a scalable training programme and clear visibility of team progress.'],
            ['role' => 'HR and L&D', 'text' => 'Can enrol groups by department, shift pattern and priority role.'],
            ['role' => 'Staff', 'text' => 'Practise language that applies immediately at reception, on the ward and with patients.'],
         ],
         'uz' => [
            ['role' => 'Klinika rahbariyati', 'text' => 'Kengaytiriladigan o‘quv dasturi va jamoa progressining aniq ko‘rinishini oladi.'],
            ['role' => 'HR va L&D', 'text' => 'Guruhlarni bo‘lim, smena va ustuvor rol bo‘yicha ulashi mumkin.'],
            ['role' => 'Xodimlar', 'text' => 'Registratura, bo‘lim va bemor bilan muloqotda darhol qo‘llanadigan tilni mashq qiladi.'],
         ],
      ],
   ];

   return $copy[$key][$lang] ?? $copy[$key]['en'] ?? [];
};
$limitText = static function ($text, $limit = 180) {
   $text = trim(strip_tags((string)$text));
   if (function_exists('mb_strlen') && function_exists('mb_substr')) {
      return mb_strlen($text, 'UTF-8') > $limit ? mb_substr($text, 0, $limit, 'UTF-8') . '...' : $text;
   }
   return strlen($text) > $limit ? substr($text, 0, $limit) . '...' : $text;
};

$this->registerMetaTag(['name' => 'description', 'content' => $t('meta_description')]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $t('meta_keywords')]);
$this->title = $t('page_title');

$base = Yii::$app->request->baseUrl;
$courseName = $courses->{"name_$lang"} ?: $courses->name_en;
$this->params['hideBreadcrumbs'] = true;
$courseDescription = $courses->{"desc_$lang"} ?: $courses->desc_en;
$heroImage = $courses->image ? "$base/uploads/courses/$courses->image" : "$base/images/meros_hospital.jpg";
$courseIcon = $courses->course_icons ? "$base/uploads/course_icons/$courses->course_icons" : "$base/slc_logo_white.png";
$consultationSubject = rawurlencode($t('page_title'));
$challengeCards = $tList('challenge_cards');
$pathways = $tList('pathways');
$roles = $tList('roles');
function translate($key)
{
    $params = Yii::$app->params;
    $lang = Yii::$app->language;
 return $params[$key][$lang] ?? $params[$key]['en'];
}
?>


<section id="course-banner" class="meros-course-hero meros-hospitals-hero reveal-section" aria-label="<?= Html::encode($t('page_title')) ?>">
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
                     <a href="#hospital-pathways" class="btn btn-outline-light btn-lg rounded-pill px-4"><?= Html::encode($t('cta_secondary')) ?></a>
                  </div>
               </div>
            </div>
            <div class="col-lg-5 col-12">
               <div class="meros-hospitals-card reveal-section">
                  <span class="meros-kicker"><?= Html::encode($t('platform_kicker')) ?></span>
                  <h3><?= Html::encode($t('platform_title')) ?></h3>
                  <p><?= Html::encode($t('platform_text')) ?></p>
                  <div class="row g-3 mt-2">
                     <div class="col-6"><div class="meros-hospitals-stat"><strong>24/7</strong><span><?=(translate('access'))?></span></div></div>
                     <div class="col-6"><div class="meros-hospitals-stat"><strong>A2-C1</strong><span><?=translate('level')?></span></div></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<div id="page-content" class="meros-modern-page meros-course-page meros-hospitals-page">
   <section class="meros-section reveal-section">
      <div class="container">
         <div class="row g-4 align-items-center">
            <div class="col-lg-5">
               <span class="meros-kicker"><?= Html::encode($t('challenge_kicker')) ?></span>
               <h2><?= Html::encode($t('challenge_title')) ?></h2>
               <p><?= Html::encode($t('challenge_text')) ?></p>
            </div>
            <div class="col-lg-7">
               <div class="row g-4">
                  <?php foreach ($challengeCards as $card): ?>
                     <div class="col-md-4"><div class="meros-hospitals-card"><div class="meros-hospitals-icon bi <?= Html::encode($card['icon']) ?>"></div><h3><?= Html::encode($card['title']) ?></h3><p><?= Html::encode($card['text']) ?></p></div></div>
                  <?php endforeach; ?>
               </div>
            </div>
         </div>
      </div>
   </section>

   <section id="hospital-pathways" class="meros-section reveal-section">
      <div class="container">
         <div class="text-center meros-section-heading"><span class="meros-kicker"><?= Html::encode($t('pathways_kicker')) ?></span><h2><?= Html::encode($t('pathways_title')) ?></h2></div>
         <div class="row g-4">
            <?php foreach ($pathways as $item): ?>
               <div class="col-lg-3 col-md-6"><article class="meros-hospitals-card meros-hospitals-pathway"><span class="meros-hospitals-level"><?= Html::encode($item['level']) ?></span><h3><?= Html::encode($item['title']) ?></h3><p><?= Html::encode($limitText($item['text'])) ?></p></article></div>
            <?php endforeach; ?>
         </div>
      </div>
   </section>

   <section class="meros-section reveal-section">
      <div class="container">
         <div class="row g-5 align-items-center">
            <div class="col-lg-6"><div class="meros-about-card"><span class="meros-kicker"><?= Html::encode($t('about_kicker')) ?></span><h2><?= Html::encode($courseName) ?></h2><?= $courseDescription ?></div></div>
            <div class="col-lg-6">
               <div class="meros-section-heading"><span class="meros-kicker"><?= Html::encode($t('roles_kicker')) ?></span><h2><?= Html::encode($t('roles_title')) ?></h2></div>
               <table class="meros-hospitals-table"><tbody>
                  <?php foreach ($roles as $role): ?>
                     <tr><td><?= Html::encode($role['role']) ?></td><td><?= Html::encode($role['text']) ?></td></tr>
                  <?php endforeach; ?>
               </tbody></table>
            </div>
         </div>
      </div>
   </section>

   <section class="meros-section reveal-section">
      <div class="container"><div class="meros-hospitals-cta text-center"><span class="meros-kicker"><?= Html::encode($t('hero_kicker')) ?></span><h2><?= Html::encode($t('final_title')) ?></h2><p class="mx-auto mb-4 meros-hospitals-cta-text"><?= Html::encode($t('final_text')) ?></p><a href="mailto:info@merosedu.uz?subject=<?= $consultationSubject ?>" class="btn btn-light btn-lg rounded-pill px-5"><?= Html::encode($t('cta_primary')) ?></a></div></div>
   </section>
</div>
