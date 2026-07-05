<?php

/** @var yii\web\View $this */
/** @var common\models\Mentors[] $mentors */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$params = Yii::$app->params;
$lang = Yii::$app->language;
$base = Yii::$app->request->baseUrl;

$t = static function (string $key, string $fallback) use ($params, $lang): string {
    return $params[$key][$lang] ?? $params[$key]['en'] ?? $fallback;
};

$teamT = static function (string $key) use ($params, $lang): string {
    return $params['team_page'][$key][$lang] ?? $params['team_page'][$key]['en'] ?? $key;
};

$this->title = $t('meet_the_team', '');

?>

<div id="page-content" class="meros-modern-page meros-content-page meros-team-page">
    <section class="meros-section meros-page-hero reveal-section">
        <div class="container">
            <div class="meros-team-hero-card">
                <span class="meros-kicker text-white"><?= Html::encode($t('medical_english_institute', '')) ?></span>
                <h1><?= Html::encode($this->title) ?></h1>
                <p><?= Html::encode($teamT('hero_intro')) ?></p>
            </div>
        </div>
    </section>

    <section class="meros-section reveal-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">
                    <div class="meros-about-card">
                        <span class="meros-kicker"><?= Html::encode($t('about_meros', '')) ?></span>
                        <h2><?= Html::encode($t('meet_the_team', '')) ?></h2>
                        <ul class="meros-team-intro-list">
                            <li><span class="fa fa-check"></span><span><?= Html::encode($teamT('benefit_1')) ?></span></li>
                            <li><span class="fa fa-check"></span><span><?= Html::encode($teamT('benefit_2')) ?></span></li>
                            <li><span class="fa fa-check"></span><span><?= Html::encode($teamT('benefit_3')) ?></span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="meros-section reveal-section">
        <div class="container">
            <div class="meros-section-heading text-center">
                <span class="meros-kicker"><?= Html::encode($this->title) ?></span>
                <h2><?= Html::encode($t('meet_the_team', '')) ?></h2>
            </div>

            <?php if (!empty($mentors)): ?>
                <div class="meros-team-grid">
                    <?php foreach ($mentors as $mentor): ?>
                        <?php
                        $modalId = 'mentor-modal-' . (int)$mentor->id;
                        $name = $mentor->fullname ?: $teamT('mentor_name_fallback');
                        $descAttribute = "desc_$lang";
                        $description = $mentor->{$descAttribute} ?: $mentor->desc_en ?: $mentor->desc_ru ?: $mentor->desc_uz ?: '';
                        $avatar = $mentor->avatar ? "$base/uploads/mentors/avatar/$mentor->avatar" : "$base/img/profile-avatar.jpg";
                        ?>
                        <article class="meros-mentor-card">
                            <button type="button" class="meros-mentor-photo-btn" data-bs-toggle="modal" data-bs-target="#<?= Html::encode($modalId) ?>" aria-label="<?= Html::encode($name) ?>">
                                <img class="meros-mentor-photo" src="<?= Html::encode($avatar) ?>" alt="<?= Html::encode($name) ?>" loading="lazy">
                            </button>
                            <h3 class="meros-mentor-name"><?= Html::encode($name) ?></h3>
                            <button type="button" class="meros-mentor-readmore" data-bs-toggle="modal" data-bs-target="#<?= Html::encode($modalId) ?>">
                                <?= Html::encode($teamT('read_more')) ?>
                            </button>
                        </article>

                        <div class="modal fade meros-mentor-modal" id="<?= Html::encode($modalId) ?>" tabindex="-1" aria-labelledby="<?= Html::encode($modalId) ?>-label" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title fs-5" id="<?= Html::encode($modalId) ?>-label"><?= Html::encode($name) ?></h2>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= Html::encode($teamT('close')) ?>"></button>
                                    </div>
                                    <div class="modal-body p-4 p-lg-5">
                                        <div class="row g-4 align-items-start">
                                            <div class="col-md-4">
                                                <img class="meros-mentor-modal-photo" src="<?= Html::encode($avatar) ?>" alt="<?= Html::encode($name) ?>" loading="lazy">
                                            </div>
                                            <div class="col-md-8">
                                                <h3><?= Html::encode($name) ?></h3>
                                                <div class="meros-mentor-description">
                                                    <?= $description ? HtmlPurifier::process($description) : Html::tag('p', Html::encode($teamT('mentor_fallback'))) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="meros-team-empty">
                    <h3><?= Html::encode($t('meet_the_team', '')) ?></h3>
                    <p class="mb-0"><?= Html::encode($teamT('empty')) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>
