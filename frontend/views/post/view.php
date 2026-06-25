<?php

use yii\helpers\Html;
use yii\helpers\Url;

$params = Yii::$app->params;
$lang = Yii::$app->language;
$t = static function ($key) use ($params, $lang) {
    return $params[$key][$lang] ?? $params[$key]['en'] ?? $key;
};
$base = Yii::$app->request->baseUrl;
$this->title = $model->{"name_$lang"} ?: $model->name_en;
?>

<div class="container">
    <ol class="breadcrumb flex-wrap">
        <li class="breadcrumb-item"><a href="<?= Yii::$app->homeUrl ?>"><?= Html::encode($t('home')) ?></a></li>
        <li class="breadcrumb-item"><a href="<?= Url::to(['index']) ?>"><?= Html::encode($t('news_page_title')) ?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
    </ol>
</div>

<div id="page-content" class="meros-modern-page meros-news-page">
    <section class="meros-section reveal-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8 col-12">
                    <article class="meros-detail-card">
                        <div class="meros-detail-image">
                            <img src="<?= Html::encode("$base/uploads/posts/$model->image") ?>"
                                 class="img-fluid"
                                 alt="<?= Html::encode($this->title) ?>">
                            <span><?= date('d.m.Y', $model->created_at) ?></span>
                        </div>
                        <div class="meros-detail-body">
                            <span class="meros-kicker"><?= Html::encode($t('news_page_title')) ?></span>
                            <h1><?= Html::encode($this->title) ?></h1>
                            <div class="meros-detail-content">
                                <?= $model->{"content_$lang"} ?>
                            </div>
                        </div>
                    </article>

                    <section id="related-articles" class="meros-related-section reveal-section">
                        <div class="meros-section-heading">
                            <span class="meros-kicker"><?= Html::encode($t('continue_reading')) ?></span>
                            <h2><?= Html::encode($t('related_news')) ?></h2>
                        </div>
                        <div class="row g-4">
                            <?php foreach ($related as $item): ?>
                                <div class="col-md-6 col-12">
                                    <article class="meros-news-card h-100">
                                        <a class="meros-news-image" href="<?= Url::to(['view', 'id' => $item->id]) ?>">
                                            <img src="<?= Html::encode("$base/uploads/posts/$item->image") ?>"
                                                 alt="<?= Html::encode($item->{"name_$lang"}) ?>"
                                                 loading="lazy">
                                            <span><?= date('d.m.Y', $item->created_at) ?></span>
                                        </a>
                                        <div class="meros-news-body">
                                            <h3>
                                                <a href="<?= Url::to(['view', 'id' => $item->id]) ?>">
                                                    <?= Html::encode($item->{"name_$lang"}) ?>
                                                </a>
                                            </h3>
                                            <a href="<?= Url::to(['view', 'id' => $item->id]) ?>" class="meros-link"><?= Html::encode($t('read_more')) ?></a>
                                        </div>
                                    </article>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                </div>

                <div class="col-lg-4 col-12">
                    <aside class="meros-news-sidebar-card reveal-section">
                        <span class="meros-kicker"><?= Html::encode($t('related')) ?></span>
                        <h2><?= Html::encode($t('related_news')) ?></h2>
                        <div class="meros-mini-news-list">
                            <?php foreach ($related as $item): ?>
                                <article>
                                    <time><?= date('d.m.Y', $item->created_at) ?></time>
                                    <a href="<?= Url::to(['view', 'id' => $item->id]) ?>">
                                        <?= Html::encode($item->{"name_$lang"}) ?>
                                    </a>
                                </article>
                            <?php endforeach; ?>
                        </div>
                        <a href="<?= Url::to(['index']) ?>" class="meros-link"><?= Html::encode($t('all_news')) ?></a>
                    </aside>
                </div>
            </div>
        </div>
    </section>
</div>
