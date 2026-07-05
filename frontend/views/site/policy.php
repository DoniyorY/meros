<?php
/** @var yii\web\View $this */

use yii\helpers\Html;

$privacy = Yii::$app->params['privacy_policy'] ?? [];
$lang = substr(Yii::$app->language ?: 'ru', 0, 2);
$lang = in_array($lang, ['ru', 'en', 'uz'], true) ? $lang : 'ru';

$t = static function (string $key) use ($privacy, $lang): string {
    return (string)($privacy[$key][$lang] ?? $privacy[$key]['ru'] ?? '');
};

$list = static function (string $key) use ($privacy, $lang): array {
    return $privacy[$key][$lang] ?? $privacy[$key]['ru'] ?? [];
};

$renderList = static function (array $items): string {
    $html = '<ul class="privacy-list">';
    foreach ($items as $item) {
        $html .= '<li>' . Html::encode($item) . '</li>';
    }
    return $html . '</ul>';
};

$this->title = $t('page_title');
?>

<section class="privacy-policy-page">
    <style>
        .privacy-policy-page {
            --privacy-bg: #f6f8fb;
            --privacy-card: #ffffff;
            --privacy-text: #1f2937;
            --privacy-muted: #6b7280;
            --privacy-border: #e5e7eb;
            --privacy-accent: #0f766e;
            color: var(--privacy-text);
            background: var(--privacy-bg);
            padding: 48px 0;
            line-height: 1.7;
        }
        .privacy-container { max-width: 1040px; margin: 0 auto; padding: 0 16px; }
        .privacy-hero {
            background: linear-gradient(135deg, rgba(15,118,110,.12), rgba(37,99,235,.08));
            border: 1px solid var(--privacy-border);
            border-radius: 24px;
            padding: 32px;
            margin-bottom: 24px;
        }
        .privacy-hero h1 { margin: 0 0 8px; font-size: clamp(28px, 4vw, 44px); line-height: 1.15; }
        .privacy-hero p { margin: 0; color: var(--privacy-muted); }
        .privacy-meta { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 18px; }
        .privacy-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: #fff; border: 1px solid var(--privacy-border); border-radius: 999px;
            padding: 8px 12px; font-size: 14px; color: var(--privacy-text);
        }
        .privacy-card {
            background: var(--privacy-card);
            border: 1px solid var(--privacy-border);
            border-radius: 20px;
            padding: 28px;
            margin-bottom: 18px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .04);
        }
        .privacy-card h2 { margin: 0 0 14px; font-size: 24px; line-height: 1.25; }
        .privacy-card h3 { margin: 20px 0 8px; font-size: 18px; }
        .privacy-card p { margin: 0 0 14px; }
        .privacy-list { margin: 10px 0 0; padding-left: 22px; }
        .privacy-list li { margin: 6px 0; }
        .privacy-contact {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
            margin-top: 16px;
        }
        .privacy-contact-item {
            border: 1px solid var(--privacy-border);
            border-radius: 16px;
            padding: 14px;
            background: #fafafa;
        }
        .privacy-contact-label { display: block; font-size: 13px; color: var(--privacy-muted); margin-bottom: 4px; }
        .privacy-final {
            border-left: 4px solid var(--privacy-accent);
            background: #ecfdf5;
            padding: 16px 18px;
            border-radius: 14px;
            margin-top: 16px;
        }
        @media (max-width: 576px) {
            .privacy-policy-page { padding: 28px 0; }
            .privacy-hero, .privacy-card { padding: 22px; border-radius: 18px; }
        }
    </style>

    <div class="privacy-container">
        <div class="privacy-hero">
            <h1><?= Html::encode($t('page_title')) ?></h1>
            <p><?= Html::encode($t('intro')) ?></p>
            <div class="privacy-meta">
                <span class="privacy-badge"><?= Html::encode($t('brand_name')) ?></span>
                <span class="privacy-badge"><?= Html::encode($t('effective_date_label')) ?>: <?= Html::encode($t('effective_date')) ?></span>
            </div>
        </div>

        <article class="privacy-card">
            <h2><?= Html::encode($t('section_1_title')) ?></h2>
            <p><?= Html::encode($t('section_1_intro')) ?></p>

            <h3><?= Html::encode($t('personal_data_title')) ?></h3>
            <?= $renderList($list('personal_data_items')) ?>

            <h3><?= Html::encode($t('education_data_title')) ?></h3>
            <?= $renderList($list('education_data_items')) ?>

            <h3><?= Html::encode($t('payment_data_title')) ?></h3>
            <?= $renderList($list('payment_data_items')) ?>

            <h3><?= Html::encode($t('technical_data_title')) ?></h3>
            <?= $renderList($list('technical_data_items')) ?>
        </article>

        <article class="privacy-card">
            <h2><?= Html::encode($t('section_2_title')) ?></h2>
            <p><?= Html::encode($t('section_2_intro')) ?></p>
            <?= $renderList($list('section_2_items')) ?>
        </article>

        <article class="privacy-card">
            <h2><?= Html::encode($t('section_3_title')) ?></h2>
            <p><?= Html::encode($t('section_3_p1')) ?></p>
            <p><?= Html::encode($t('section_3_p2')) ?></p>
            <?= $renderList($list('section_3_items')) ?>
            <p><?= Html::encode($t('section_3_p3')) ?></p>
        </article>

        <article class="privacy-card">
            <h2><?= Html::encode($t('section_4_title')) ?></h2>
            <p><?= Html::encode($t('section_4_p1')) ?></p>
            <p><?= Html::encode($t('section_4_p2')) ?></p>
        </article>

        <article class="privacy-card">
            <h2><?= Html::encode($t('section_5_title')) ?></h2>
            <p><?= Html::encode($t('section_5_p1')) ?></p>
            <p><?= Html::encode($t('section_5_p2')) ?></p>
        </article>

        <article class="privacy-card">
            <h2><?= Html::encode($t('section_6_title')) ?></h2>
            <p><?= Html::encode($t('section_6_intro')) ?></p>
            <?= $renderList($list('section_6_items')) ?>
            <p><?= Html::encode($t('section_6_p2')) ?></p>
        </article>

        <article class="privacy-card">
            <h2><?= Html::encode($t('section_7_title')) ?></h2>
            <p><?= Html::encode($t('section_7_intro')) ?></p>
            <?= $renderList($list('section_7_items')) ?>
            <p><?= Html::encode($t('section_7_p2')) ?></p>
        </article>

        <article class="privacy-card">
            <h2><?= Html::encode($t('section_8_title')) ?></h2>
            <p><?= Html::encode($t('section_8_intro')) ?></p>
            <?= $renderList($list('section_8_items')) ?>
            <p><?= Html::encode($t('section_8_p2')) ?></p>
        </article>

        <article class="privacy-card">
            <h2><?= Html::encode($t('section_9_title')) ?></h2>
            <p><?= Html::encode($t('section_9_p1')) ?></p>
        </article>

        <article class="privacy-card">
            <h2><?= Html::encode($t('section_10_title')) ?></h2>
            <p><?= Html::encode($t('section_10_p1')) ?></p>
        </article>

        <article class="privacy-card">
            <h2><?= Html::encode($t('section_11_title')) ?></h2>
            <p><?= Html::encode($t('section_11_p1')) ?></p>
            <div class="privacy-contact">
                <div class="privacy-contact-item">
                    <span class="privacy-contact-label"><?= Html::encode($t('brand_name')) ?></span>
                    <?= Html::encode($t('brand_name')) ?>
                </div>
                <div class="privacy-contact-item">
                    <span class="privacy-contact-label"><?= Html::encode($t('contact_email_label')) ?></span>
                    <a href="mailto:<?= Html::encode($t('contact_email')) ?>"><?= Html::encode($t('contact_email')) ?></a>
                </div>
                <div class="privacy-contact-item">
                    <span class="privacy-contact-label"><?= Html::encode($t('contact_website_label')) ?></span>
                    <?= Html::encode($t('contact_website')) ?>
                </div>
                <div class="privacy-contact-item">
                    <span class="privacy-contact-label"><?= Html::encode($t('contact_address_label')) ?></span>
                    <?= Html::encode($t('contact_address')) ?>
                </div>
            </div>
            <div class="privacy-final"><?= Html::encode($t('final_notice')) ?></div>
        </article>
    </div>
</section>
