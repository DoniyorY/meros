<?php

/** @var yii\web\View $this */
/** @var \frontend\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = Yii::$app->params['contact_us'][Yii::$app->language] ?? 'Contact Us';
$params = Yii::$app->params;
$lang = Yii::$app->language;
function translate($key)
{
    $lang = Yii::$app->language;
        return Yii::$app->params[$key][$lang];
}

?>
<!-- Breadcrumb -->
<div class="container">
    <ol class="breadcrumb flex-wrap">
        <li class="breadcrumb-item"><a href="<?= Yii::$app->homeUrl ?>"><?= translate('home') ?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
    </ol>
</div>
<!-- end Breadcrumb -->

<div id="page-content" class="meros-modern-page meros-content-page meros-contact-page">
    <section class="meros-section meros-page-hero reveal-section">
        <div class="container">
            <div class="meros-section-heading text-center">
                <span class="meros-kicker">Get in touch</span>
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="meros-contact-card h-100">
                        <i class="fa fa-map-marker"></i>
                        <h3><?= Yii::$app->name ?></h3>
                        <p><?= translate('address_footer') ?></p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="meros-contact-card h-100">
                        <i class="fa fa-phone"></i>
                        <h3>Phone</h3>
                        <p><a href="tel:<?= Html::encode($params['phone']) ?>"><?= Html::encode($params['phone']) ?></a></p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="meros-contact-card h-100">
                        <i class="fa fa-envelope"></i>
                        <h3>Email</h3>
                        <p><a href="mailto:<?= Html::encode($params['adminEmail']) ?>"><?= Html::encode($params['adminEmail']) ?></a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="meros-section reveal-section">
        <div class="container">
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-6">
                    <div class="meros-map-card h-100">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3071.780816052281!2d66.91040297781956!3d39.65464709999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3f4d190002e36ddf%3A0x8e83f4ad3be4f23a!2sMeros%20International%20Hospital!5e0!3m2!1sru!2s!4v1780140649710!5m2!1sru!2s"
                                width="100%" height="100%" style="border:0" loading="lazy" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="meros-contact-form-card h-100">
                        <span class="meros-kicker">Message us</span>
                        <h2>Send Us a Message</h2>
                        <p>If you have an enquiry about an existing course or want to discuss your specific requirements, please send us a message.</p>
                        <?php $form = ActiveForm::begin(['id' => 'contact-form', 'options' => ['class' => 'meros-form']]); ?>
                            <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>
                            <?= $form->field($model, 'email')->input('email') ?>
                            <?= $form->field($model, 'subject')->textInput() ?>
                            <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>
                            <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                                'template' => '<div class="row g-3 align-items-center"><div class="col-sm-5">{image}</div><div class="col-sm-7">{input}</div></div>',
                            ]) ?>
                            <div class="form-group mb-0">
                                <?= Html::submitButton('Send a Message', ['class' => 'btn btn-primary meros-primary-btn', 'name' => 'contact-button']) ?>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
