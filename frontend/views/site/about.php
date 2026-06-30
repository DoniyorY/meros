<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

function translate($key)
{
   $lang = Yii::$app->language;
   return Yii::$app->params[$key][$lang];
}
$this->title = translate('about_meros_international_institute');
$base = Yii::$app->request->baseUrl;
$lang = Yii::$app->language;
$galleryImages = range(1, 4);

?>

<div id="page-content" class="meros-modern-page meros-content-page meros-about-page">
    <section class="meros-section meros-page-hero reveal-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="meros-kicker"><?= Html::encode(translate('medical_english_institute')) ?></span>
                    <h1><?= Html::encode($this->title) ?></h1>
                    <div class="meros-hero-copy">
                        <?= translate('about_short') ?>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="meros-page-hero-image">
                        <img src="<?= "$base/images/meros_hospital.jpg" ?>" alt="Meros International Hospital" loading="eager">
                        <div class="meros-floating-badge">
                            <strong>Meros</strong>
                            <span><?= Html::encode(translate('international_institute')) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="meros-section reveal-section">
        <div class="container">
            <div class="row g-4 align-items-start">
                <div class="col-lg-8">
                    <div class="meros-about-card meros-story-card">
                        <span class="meros-kicker"><?= Html::encode(translate('about_meros')) ?></span>
                        <h2><?= translate('about_meros') ?></h2>
                        <?= translate('about_content') ?>
                    </div>
                </div>
                <div class="col-lg-4">
                    <aside class="meros-news-sidebar-card h-100">
                        <span class="meros-kicker"><?= translate('latest_news') ?></span>
                        <h2><?= translate('news') ?></h2>
                        <div class="meros-mini-news-list">
                            <?php foreach ($posts as $item): ?>
                                <article>
                                    <time datetime="<?= date('Y-m-d', $item->created_at) ?>"><?= date('d.m.Y', $item->created_at) ?></time>
                                    <a href="<?= Url::to(['post/view', 'id' => $item->id]) ?>">
                                        <?= Html::encode($item->{"name_$lang"}) ?>
                                    </a>
                                </article>
                            <?php endforeach; ?>
                        </div>
                        <a href="<?= Url::to(['post/index']) ?>" class="meros-link"><?= translate('all_news') ?></a>
                    </aside>
                </div>
            </div>
        </div>
    </section>

    <section class="meros-section reveal-section">
        <div class="container">
            <div class="meros-section-heading text-center">
                <span class="meros-kicker"><?= Html::encode(translate('campus_life')) ?></span>
                <h2><?= translate('gallery') ?></h2>
            </div>
            <div class="meros-gallery-grid">
                <?php foreach ($galleryImages as $imageNumber): ?>
                    <?php $image = sprintf('image-%02d.jpg', $imageNumber); ?>
                    <a href="<?= "$base/img/gallery-big-image.jpg" ?>" class="image-popup meros-gallery-item">
                        <img src="<?= "$base/img/$image" ?>" alt="<?= Html::encode(translate('meros_gallery_image')) ?> <?= $imageNumber ?>" loading="lazy">
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-4">
                <a href="<?= Url::to(['site/about']) ?>" class="btn btn-primary meros-primary-btn"><?= translate('go_to_gallery') ?></a>
            </div>
        </div>
    </section>
</div>
