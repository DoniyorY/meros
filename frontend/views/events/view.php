<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Events $model */
/** @var common\models\Events[] $related */

$params = Yii::$app->params;
$lang = Yii::$app->language;
$t = static function ($key) use ($params, $lang) {
    return $params[$key][$lang] ?? $params[$key]['en'] ?? $key;
};
$base = Yii::$app->request->baseUrl;
$title = $model->{"name_$lang"} ?: $model->name_en;
$image = $model->image ? "$base/uploads/events/$model->image" : "$base/img/event-img-01.jpg";
$this->title = $title;

$videoUrl = null;
if ($model->video_link) {
    $parts = parse_url($model->video_link);
    if (!empty($parts['host']) && str_contains($parts['host'], 'youtu.be')) {
        $videoUrl = 'https://www.youtube.com/embed/' . trim($parts['path'] ?? '', '/');
    } elseif (!empty($parts['query'])) {
        parse_str($parts['query'], $query);
        if (!empty($query['v'])) {
            $videoUrl = 'https://www.youtube.com/embed/' . $query['v'];
        }
    }
}
?>

<div class="container">
    <ol class="breadcrumb flex-wrap">
        <li class="breadcrumb-item"><a href="<?= Yii::$app->homeUrl ?>"><?= Html::encode($t('home')) ?></a></li>
        <li class="breadcrumb-item"><a href="<?= Url::to(['index']) ?>"><?= Html::encode($t('events_page_title')) ?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
    </ol>
</div>

<div id="page-content" class="meros-modern-page meros-events-page">
    <section class="meros-section reveal-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8 col-12">
                    <article class="meros-detail-card">
                        <div class="meros-detail-image">
                            <img src="<?= Html::encode($image) ?>" class="img-fluid" alt="<?= Html::encode($title) ?>">
                            <span><?= date('d.m.Y', $model->created_at) ?></span>
                        </div>
                        <div class="meros-detail-body">
                            <span class="meros-kicker"><?= Html::encode($t('event_kicker')) ?></span>
                            <h1><?= Html::encode($title) ?></h1>
                            <p class="meros-muted"><span class="fa fa-calendar"></span> <?= date('d.m.Y', $model->created_at) ?></p>
                            <div class="meros-detail-content">
                                <?= $model->{"content_$lang"} ?: $model->content_en ?>
                            </div>
                        </div>
                    </article>

                    <?php if ($model->video_link): ?>
                        <section class="meros-related-section reveal-section">
                            <div class="meros-section-heading">
                                <span class="meros-kicker"><?= Html::encode($t('video')) ?></span>
                                <h2>YouTube</h2>
                            </div>
                            <div class="meros-detail-card">
                                <div class="ratio ratio-16x9">
                                    <?php if ($videoUrl): ?>
                                        <iframe src="<?= Html::encode($videoUrl) ?>" title="<?= Html::encode($title) ?>" allowfullscreen loading="lazy"></iframe>
                                    <?php else: ?>
                                        <a href="<?= Html::encode($model->video_link) ?>" target="_blank" rel="noopener" class="meros-link"><?= Html::encode($t('open_video_youtube')) ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </section>
                    <?php endif; ?>

                    <?php if (!empty($related)): ?>
                        <section class="meros-related-section reveal-section">
                            <div class="meros-section-heading">
                                <span class="meros-kicker"><?= Html::encode($t('continue_exploring')) ?></span>
                                <h2><?= Html::encode($t('related_events')) ?></h2>
                            </div>
                            <div class="row g-4">
                                <?php foreach ($related as $event): ?>
                                    <?php $eventTitle = $event->{"name_$lang"} ?: $event->name_en; ?>
                                    <div class="col-md-6 col-12">
                                        <article class="meros-news-card h-100">
                                            <a class="meros-news-image" href="<?= Url::to(['view', 'id' => $event->id]) ?>">
                                                <img src="<?= Html::encode($event->image ? "$base/uploads/events/$event->image" : "$base/img/event-img-01.jpg") ?>" alt="<?= Html::encode($eventTitle) ?>" loading="lazy">
                                                <span><?= date('d.m.Y', $event->created_at) ?></span>
                                            </a>
                                            <div class="meros-news-body">
                                                <h3><a href="<?= Url::to(['view', 'id' => $event->id]) ?>"><?= Html::encode($eventTitle) ?></a></h3>
                                                <a href="<?= Url::to(['view', 'id' => $event->id]) ?>" class="meros-link"><?= Html::encode($t('view_details')) ?></a>
                                            </div>
                                        </article>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>
                </div>

                <div class="col-lg-4 col-12">
                    <aside class="meros-news-sidebar-card reveal-section">
                        <span class="meros-kicker"><?= Html::encode($t('events_page_title')) ?></span>
                        <h2><?= Html::encode($t('latest_events')) ?></h2>
                        <div class="meros-mini-news-list">
                            <?php foreach ($related as $event): ?>
                                <?php $eventTitle = $event->{"name_$lang"} ?: $event->name_en; ?>
                                <article>
                                    <time><?= date('d.m.Y', $event->created_at) ?></time>
                                    <a href="<?= Url::to(['view', 'id' => $event->id]) ?>"><?= Html::encode($eventTitle) ?></a>
                                </article>
                            <?php endforeach; ?>
                        </div>
                        <a href="<?= Url::to(['index']) ?>" class="meros-link"><?= Html::encode($t('all_events')) ?></a>
                    </aside>
                </div>
            </div>
        </div>
    </section>
</div>
