<?php

use yii\helpers\Html;
use yii\helpers\Url;

$params = Yii::$app->params;
$lang = Yii::$app->language;
$t = static function ($key) use ($params, $lang) {
    return $params[$key][$lang] ?? $params[$key]['en'] ?? $key;
};
$base = Yii::$app->request->baseUrl;
$this->title = $t('news_page_title');
?>

<div class="container">
    <ol class="breadcrumb flex-wrap">
        <li class="breadcrumb-item"><a href="<?= Yii::$app->homeUrl ?>"><?= Html::encode($t('home')) ?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
    </ol>
</div>

<div id="page-content" class="meros-modern-page meros-news-page">
    <section class="meros-section meros-news-hero reveal-section">
        <div class="container">
            <div class="meros-section-heading text-center">
                <span class="meros-kicker"><?= Html::encode($t('news_kicker')) ?></span>
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
        </div>
    </section>

    <section class="meros-section reveal-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8 col-12">
                    <div class="row g-4">
                        <?php foreach ($posts as $post): ?>
                            <div class="col-md-6 col-12">
                                <article class="meros-news-card h-100">
                                    <a class="meros-news-image" href="<?= Url::to(['view', 'id' => $post->id]) ?>">
                                        <img src="<?= Html::encode("$base/uploads/posts/$post->image") ?>"
                                             alt="<?= Html::encode($post->{"name_$lang"}) ?>"
                                             loading="lazy">
                                        <span><?= date('d.m.Y', $post->created_at) ?></span>
                                    </a>
                                    <div class="meros-news-body">
                                        <h3>
                                            <a href="<?= Url::to(['view', 'id' => $post->id]) ?>">
                                                <?= Html::encode($post->{"name_$lang"}) ?>
                                            </a>
                                        </h3>
                                        <p><?= Html::encode(strip_tags($post->{"desc_$lang"})) ?></p>
                                        <a href="<?= Url::to(['view', 'id' => $post->id]) ?>" class="meros-link"><?= Html::encode($t('read_more')) ?></a>
                                    </div>
                                </article>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="col-lg-4 col-12">
                    <aside class="meros-news-sidebar-card reveal-section">
                        <span class="meros-kicker"><?= Html::encode($t('search')) ?></span>
                        <h2><?= Html::encode($t('search_news')) ?></h2>
                        <div class="input-group meros-search-box">
                            <input type="text" class="form-control" placeholder="<?= Html::encode($t('search_news_placeholder')) ?>">
                            <button type="submit" class="btn meros-primary-btn"><i class="fa fa-angle-right"></i></button>
                        </div>
                        <p><?= Html::encode($t('search_news_hint')) ?></p>
                    </aside>

                    <aside class="meros-news-sidebar-card reveal-section mt-4">
                        <span class="meros-kicker"><?= Html::encode($t('categories')) ?></span>
                        <h2><?= Html::encode($t('categories')) ?></h2>
                        <ul class="list-unstyled meros-sidebar-links">
                            <li><a href="#"><?= Html::encode($t('university_news')) ?></a></li>
                        </ul>
                    </aside>
                </div>
            </div>
        </div>
    </section>
</div>
