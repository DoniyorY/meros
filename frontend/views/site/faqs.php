<?php

/** @var yii\web\View $this */
/** @var common\models\Faq[] $faqs */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\StringHelper;
use yii\helpers\HtmlPurifier;

$lang = Yii::$app->language;
$params = Yii::$app->params;
$pageSlug = Yii::$app->request->get('page', 'faq-students');
$isOrganisationPage = $pageSlug === 'faq-organisations';

$translate = static function (string $key, ?string $fallback = null) use ($params, $lang): string {
    if (isset($params[$key][$lang])) {
        return $params[$key][$lang];
    }

    if (isset($params[$key]['en'])) {
        return $params[$key]['en'];
    }

    return $fallback ?? $key;
};

$title = $isOrganisationPage
    ? $translate('faq_org', 'FAQ Organizations')
    : $translate('faq_students', 'FAQ - Students');

$this->title = $title;

$heroSubtitle = $isOrganisationPage
    ? [
        'ru' => 'Ответы для клиник, больниц и компаний, которые хотят развивать медицинский английский в команде.',
        'en' => 'Answers for clinics, hospitals, and companies developing Medical English across their teams.',
        'uz' => 'Jamoasida Medical English ko‘nikmalarini rivojlantirayotgan klinikalar, shifoxonalar va kompaniyalar uchun javoblar.',
    ]
    : [
        'ru' => 'Собрали ключевую информацию о программах, формате обучения, оплате и поддержке студентов Meros.',
        'en' => 'Key information about Meros programs, learning formats, payments, and student support in one place.',
        'uz' => 'Meros dasturlari, o‘qish formati, to‘lov va talabalarni qo‘llab-quvvatlash bo‘yicha asosiy ma’lumotlar.',
    ];

$audienceLabel = $isOrganisationPage
    ? ['ru' => 'Для организаций', 'en' => 'For organizations', 'uz' => 'Tashkilotlar uchun']
    : ['ru' => 'Для студентов', 'en' => 'For students', 'uz' => 'Talabalar uchun'];

$alternativePage = $isOrganisationPage ? 'faq-students' : 'faq-organisations';
$alternativeLabel = $isOrganisationPage
    ? $translate('faq_students', 'FAQ - Students')
    : $translate('faq_org', 'FAQ Organizations');

$contactTitle = [
    'ru' => 'Не нашли ответ?',
    'en' => 'Still have questions?',
    'uz' => 'Javob topa olmadingizmi?',
];
$contactText = [
    'ru' => 'Оставьте заявку, и команда Meros поможет подобрать программу или ответит на ваш вопрос.',
    'en' => 'Contact the Meros team and we will help you choose a program or answer your question.',
    'uz' => 'Meros jamoasi bilan bog‘laning — dastur tanlashda yordam beramiz yoki savolingizga javob qaytaramiz.',
];
$contactButton = [
    'ru' => 'Связаться с нами',
    'en' => 'Contact us',
    'uz' => 'Biz bilan bog‘lanish',
];

$this->registerCss(<<<CSS
.meros-faq-page .meros-faq-hero-card { padding: clamp(28px, 4vw, 52px); }
.meros-faq-page .meros-faq-stats { display: grid; gap: 16px; grid-template-columns: repeat(2, minmax(0, 1fr)); margin-top: 26px; }
.meros-faq-page .meros-faq-stat { background: rgba(255,255,255,.78); border: 1px solid var(--meros-border); border-radius: 22px; padding: 20px; }
.meros-faq-page .meros-faq-stat strong { color: var(--meros-primary-dark); display: block; font-size: clamp(26px, 4vw, 42px); line-height: 1; }
.meros-faq-page .meros-faq-stat span { color: var(--meros-muted); display: block; font-weight: 700; margin-top: 8px; }
.meros-faq-page .meros-faq-switch { align-items: center; display: flex; flex-wrap: wrap; gap: 12px; justify-content: space-between; margin-bottom: 28px; }
.meros-faq-page .meros-faq-switch .btn { border-radius: 999px; font-weight: 800; padding: 12px 20px; }
.meros-faq-page .meros-faq-list-card { padding: clamp(22px, 3vw, 34px); }
.meros-faq-page .meros-faq-empty { background: #fff; border: 1px dashed var(--meros-border); border-radius: 22px; color: var(--meros-muted); padding: 32px; text-align: center; }
.meros-faq-page .meros-faq-contact { align-items: center; background: linear-gradient(135deg, var(--meros-primary-dark), var(--meros-primary)); border-radius: 28px; box-shadow: var(--meros-shadow); color: #fff; display: flex; flex-wrap: wrap; gap: 22px; justify-content: space-between; padding: clamp(26px, 4vw, 42px); }
.meros-faq-page .meros-faq-contact h2, .meros-faq-page .meros-faq-contact p { color: #fff; margin: 0; }
.meros-faq-page .meros-faq-contact p { opacity: .86; }
@media (max-width: 575.98px) { .meros-faq-page .meros-faq-stats { grid-template-columns: 1fr; } }
CSS);

?>

<div id="page-content" class="meros-modern-page meros-content-page meros-faq-page">
    <section class="meros-section meros-page-hero reveal-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="meros-about-card meros-faq-hero-card">
                        <span class="meros-kicker"><?= Html::encode($audienceLabel[$lang] ?? $audienceLabel['en']) ?></span>
                        <h1><?= Html::encode($title) ?></h1>
                        <div class="meros-hero-copy">
                            <?= Html::encode($heroSubtitle[$lang] ?? $heroSubtitle['en']) ?>
                        </div>
                        <div class="meros-faq-stats" aria-label="FAQ summary">
                            <div class="meros-faq-stat">
                                <strong><?= count($faqs) ?></strong>
                                <span><?= Html::encode($translate('faq', 'FAQ')) ?></span>
                            </div>
                            <div class="meros-faq-stat">
                                <strong>24/7</strong>
                                <span><?= Html::encode($translate('student_support', 'Student support')) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="meros-page-hero-image">
                        <img src="<?= Html::encode(Yii::$app->request->baseUrl . '/images/meros_hospital.jpg') ?>" alt="<?= Html::encode($title) ?>" loading="eager">
                        <div class="meros-floating-badge">
                            <strong>FAQ</strong>
                            <span><?= Html::encode($audienceLabel[$lang] ?? $audienceLabel['en']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="meros-section reveal-section">
        <div class="container">
            <div class="meros-faq-switch">
                <div>
                    <span class="meros-kicker"><?= Html::encode($translate('faq', 'FAQ')) ?></span>
                    <h2><?= Html::encode($title) ?></h2>
                </div>
                <a class="btn btn-outline-primary" href="<?= Url::to(['site/faq', 'page' => $alternativePage]) ?>">
                    <?= Html::encode($alternativeLabel) ?>
                </a>
            </div>

            <div class="meros-faq-card meros-faq-list-card">
                <?php if (!empty($faqs)): ?>
                    <div class="accordion meros-accordion" id="faq-page-accordion">
                        <?php foreach ($faqs as $index => $faq): ?>
                            <?php
                            $faqId = 'faq-item-' . (int) $faq->id;
                            $question = $faq->{"question_$lang"} ?: $faq->question_en;
                            $answer = $faq->{"answer_$lang"} ?: $faq->answer_en;
                            ?>
                            <div class="accordion-item meros-accordion-item">
                                <h3 class="accordion-header" id="<?= $faqId ?>-heading">
                                    <button class="accordion-button <?= $index === 0 ? '' : 'collapsed' ?>" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#<?= $faqId ?>-collapse"
                                            aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>"
                                            aria-controls="<?= $faqId ?>-collapse">
                                        <?= Html::encode($question) ?>
                                    </button>
                                </h3>
                                <div id="<?= $faqId ?>-collapse"
                                     class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>"
                                     aria-labelledby="<?= $faqId ?>-heading"
                                     data-bs-parent="#faq-page-accordion">
                                    <div class="accordion-body">
                                        <?= HtmlPurifier::process(nl2br(StringHelper::truncateWords($answer, 500, '', false))) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="meros-faq-empty">
                        <?= Html::encode($translate('no_results_found', 'No FAQ items have been added yet.')) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="meros-section reveal-section">
        <div class="container">
            <div class="meros-faq-contact">
                <div>
                    <h2><?= Html::encode($contactTitle[$lang] ?? $contactTitle['en']) ?></h2>
                    <p><?= Html::encode($contactText[$lang] ?? $contactText['en']) ?></p>
                </div>
                <a class="btn btn-light meros-primary-btn" href="<?= Url::to(['site/contact']) ?>">
                    <?= Html::encode($contactButton[$lang] ?? $contactButton['en']) ?>
                </a>
            </div>
        </div>
    </section>
</div>
