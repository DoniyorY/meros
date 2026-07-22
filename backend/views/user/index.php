<?php

use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
                    <h4 class="mb-sm-0"><?= \yii\helpers\Html::encode($this->title) ?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a
                                        href="<?= Yii::$app->homeUrl ?>"><?= Yii::$app->name ?></a></li>
                            <li class="breadcrumb-item active"><?= \yii\helpers\Html::encode($this->title) ?></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="user-index">
            <div class="row">
                <div class="col-md-10">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-success w-100" data-bs-toggle="modal"
                            data-bs-target="#createUserModal">
                        Create User
                    </button>
                </div>
            </div>


            <!-- Modal -->
            <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="createUserModalLabel">New User</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                           <?= $this->render('_form', ['model' => new User()]) ?>
                        </div>
                    </div>
                </div>
            </div>
           
           <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
           
           <?= GridView::widget([
              'dataProvider' => $dataProvider,
              'filterModel' => $searchModel,
              'columns' => [
                 ['class' => 'yii\grid\SerialColumn'],
                 
                 // 'id',
                 'fullname',
                 [
                    'attribute' => 'role',
                    'value' => function ($data) {
                       return $data->assignment->item_name;
                    },
                    'filter' => ArrayHelper::map(\common\models\AuthItem::find()->asArray()->all(), 'name', 'name'),
                 ],
                 'email:email',
                 'phone',
                 //'address',
                 //'image',
                 
                 [
                    'attribute' => 'created_at',
                    'value' => function ($model) {
                       return Yii::$app->formatter->asDatetime($model->created_at, 'php:d.m.Y H:i:s');
                    }
                 ],
                 [
                    'attribute' => 'status',
                    'value' => function ($model) {
                       if ($model->username == 'admin') {
                          return Html::button('Active', ['class' => 'btn btn-success btn-sm w-100']);
                       }
                       if ($model->status == User::STATUS_ACTIVE) {
                          return Html::a(Yii::$app->params['user_status'][$model->status], ['status', 'id' => $model->id, 'status' => User::STATUS_INACTIVE], ['class' => 'btn btn-sm btn-success w-100', 'data' => [
                             'confirm' => 'Are you sure you want to inactive this user?',
                             'method' => 'post',
                          ]]);
                       } elseif ($model->status == User::STATUS_INACTIVE) {
                          return Html::a(Yii::$app->params['user_status'][$model->status], ['status', 'id' => $model->id, 'status' => User::STATUS_ACTIVE], ['class' => 'btn btn-sm btn-warning w-100', 'data' => [
                             'confirm' => 'Are you sure you want to activate this user?',
                             'method' => 'post',
                          ]]);
                       }
                    },
                    'format' => 'raw',
                    'filter' => Yii::$app->params['user_status'],
                 ],
                 //'updated_at',
                 //'auth_key',
                 //'password_hash',
                 //'password_reset_token',
                 //'verification_token',
                 [
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, User $model, $key, $index, $column) {
                       return Url::toRoute([$action, 'id' => $model->id]);
                    },
                    'template' => '{view}',
                 ],
              ],
           ]); ?>


        </div>
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->

