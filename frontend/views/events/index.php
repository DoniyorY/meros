<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Events[] $events */

$params = Yii::$app->params;
$lang = Yii::$app->language;
$t = static function ($key) use ($params, $lang) {
    return $params[$key][$lang] ?? $params[$key]['en'] ?? $key;
};
$base = Yii::$app->request->baseUrl;
$this->title = $t('events_page_title');
?>


<div id="page-content" class="meros-modern-page meros-events-page">
    <section class="meros-section meros-news-hero reveal-section">
        <div class="container">
            <div class="meros-section-heading text-center">
                <span class="meros-kicker"><?= Html::encode($t('events_kicker')) ?></span>
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
        </div>
    </section>

    <section class="meros-section meros-events reveal-section">
        <div class="container">
            <div class="row g-4">
                <?php foreach ($events as $event): ?>
                    <?php
                    $title = $event->{"name_$lang"} ?: $event->name_en;
                    $description = $event->{"desc_$lang"} ?: $event->desc_en;
                    $image = $event->image ? "$base/uploads/events/$event->image" : "$base/img/event-img-01.jpg";
                    ?>
                    <div class="col-lg-6 col-12">
                        <article class="meros-event-card h-100">
                            <a class="meros-event-image" href="<?= Url::to(['view', 'id' => $event->id]) ?>">
                                <img src="<?= Html::encode($image) ?>" alt="<?= Html::encode($title) ?>" loading="lazy">
                                <span class="meros-event-date"><strong><?= date('d', $event->created_at) ?></strong><?= date('M', $event->created_at) ?></span>
                            </a>
                            <div class="meros-event-body">
                                <h3><a href="<?= Url::to(['view', 'id' => $event->id]) ?>"><?= Html::encode($title) ?></a></h3>
                                <p class="meros-muted"><span class="fa fa-calendar"></span> <?= date('d.m.Y', $event->created_at) ?></p>
                                <p><?= Html::encode(strip_tags($description)) ?></p>
                                <?php if ($event->video_link): ?>
                                    <p class="meros-muted"><span class="fa fa-youtube-play"></span> <?= Html::encode($t('events_youtube_available')) ?></p>
                                <?php endif; ?>
                                <a href="<?= Url::to(['view', 'id' => $event->id]) ?>" class="meros-link"><?= Html::encode($t('view_details')) ?></a>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($events)): ?>
                    <div class="col-12">
                        <article class="meros-news-sidebar-card text-center">
                            <span class="meros-kicker"><?= Html::encode($t('coming_soon')) ?></span>
                            <h2><?= Html::encode($t('events_empty_title')) ?></h2>
                            <p><?= Html::encode($t('events_empty_text')) ?></p>
                        </article>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>
