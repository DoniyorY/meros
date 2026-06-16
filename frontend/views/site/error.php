<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception $exception */

use yii\helpers\Html;
use yii\helpers\Url;

$lang = Yii::$app->language;
$params = Yii::$app->params;
$homeButtonText = $params['error_home_button'][$lang] ?? $params['error_home_button']['en'] ?? 'Home';

$this->title = $name;
?>
<div id="page-content" class="site-error-page">
    <div class="container">
        <section class="error-card text-center">
            <div class="error-card__icon" aria-hidden="true">
                <i class="fa fa-stethoscope"></i>
            </div>

            <p class="error-card__label"><?= Html::encode(Yii::$app->name) ?></p>
            <h1><?= Html::encode($this->title) ?></h1>

            <div class="error-card__message">
                <?= nl2br(Html::encode($message)) ?>
            </div>

            <a href="<?= Url::home() ?>" class="btn btn-primary btn-lg error-card__button">
                <?= Html::encode($homeButtonText) ?>
            </a>
        </section>
    </div>
</div>

<?php
$errorPageCss = <<<CSS
.site-error-page {
    padding: 70px 0 90px;
    background: linear-gradient(135deg, rgba(0, 120, 174, .08), rgba(255, 255, 255, 0) 55%);
}

.site-error-page .error-card {
    max-width: 760px;
    margin: 0 auto;
    padding: 55px 40px;
    border-radius: 24px;
    background: #fff;
    border: 1px solid rgba(0, 120, 174, .12);
    box-shadow: 0 22px 55px rgba(20, 53, 74, .12);
    position: relative;
    overflow: hidden;
}

.site-error-page .error-card:before {
    content: "";
    position: absolute;
    width: 220px;
    height: 220px;
    right: -90px;
    top: -90px;
    border-radius: 50%;
    background: rgba(0, 120, 174, .09);
}

.site-error-page .error-card__icon {
    width: 86px;
    height: 86px;
    margin: 0 auto 24px;
    border-radius: 50%;
    background: #0078ae;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    box-shadow: 0 14px 30px rgba(0, 120, 174, .24);
}

.site-error-page .error-card__label {
    margin-bottom: 10px;
    color: #0078ae;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
}

.site-error-page h1 {
    margin-bottom: 20px;
    color: #233746;
    font-size: 42px;
    font-weight: 700;
}

.site-error-page .error-card__message {
    max-width: 560px;
    margin: 0 auto 32px;
    color: #65717a;
    font-size: 18px;
    line-height: 1.65;
}

.site-error-page .error-card__button {
    padding: 13px 34px;
    border-radius: 999px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
}

@media (max-width: 575.98px) {
    .site-error-page {
        padding: 45px 0 65px;
    }

    .site-error-page .error-card {
        padding: 40px 24px;
        border-radius: 18px;
    }

    .site-error-page h1 {
        font-size: 30px;
    }

    .site-error-page .error-card__message {
        font-size: 16px;
    }
}
CSS;
$this->registerCss($errorPageCss);
?>
