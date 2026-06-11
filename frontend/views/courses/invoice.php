<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\SubscriptionPlans $model
 */

$this->title = "Invoice";
$lang = Yii::$app->language;
$base = Yii::$app->request->baseUrl;

?>

<div id="page-content">
    <!-- Breadcrumb -->
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Yii::$app->homeUrl ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Course</li>
                <li class="breadcrumb-item active" aria-current="page"><?= $model->{"name_$lang"} ?></li>
            </ol>
        </nav>
    </div>
    <!-- end Breadcrumb -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Invoice#123</h1>
            </div>
            <hr class="mt-2">
            <h2>You are not Logged in</h2>
            <div class="col-md-6">
               <?php $form = ActiveForm::begin() ?>
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name">
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name">
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="username">Password</label>
                    <input type="text" class="form-control" id="username" name="username">
                </div>
                <div class="form-group">
                    <label for="username">Confirm Password</label>
                    <input type="text" class="form-control" id="username" name="username">
                </div>
               <?php ActiveForm::end() ?>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <tr>
                                <th>Product</th>
                                <td><?=$model->{"name_$lang"}?></td>
                            </tr>
                            <tr>
                                <th>Price</th>
                                <td><?=Yii::$app->formatter->asDecimal($model->price)?> uzs / 3 month</td>
                            </tr>
                            <tr>
                                <th>Product</th>
                                <td><?=Yii::$app->formatter->asDecimal($model->price)?> uzs / 3 month</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
