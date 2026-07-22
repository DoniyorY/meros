<?php

/** @var yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Html;

AppAsset::register($this);
$base = Yii::$app->request->baseUrl;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-bs-theme="dark" data-body-image="img-1" data-preloader="disable">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="auth-page-wrapper pt-5">
   <?= $content ?>

    <!-- footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <p class="mb-0 text-muted">&copy;
                            <script>document.write(new Date().getFullYear())</script> <?=Yii::$app->name?>. Crafted with <i class="mdi mdi-heart text-danger"></i> by d1verse
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end Footer -->
</div>
<!-- particles js -->
<script src="<?=$base .'/'?>libs/particles.js/particles.js"></script>
<!-- particles app js -->
<script src="<?=$base .'/'?>js/pages/particles.app.js"></script>
<!-- password-addon init -->
<script src="<?=$base .'/'?>js/pages/password-addon.init.js"></script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
