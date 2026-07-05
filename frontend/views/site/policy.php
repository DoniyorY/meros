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
