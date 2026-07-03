<?php

declare(strict_types=1);

namespace backend\controllers;

use common\models\User;
use common\services\TelegramStaffLinkService;
use common\services\TelegramStaffUserService;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

final class TelegramStaffConnectController extends Controller
{
   public function behaviors(): array
   {
      return [
         'access' => [
            'class' => AccessControl::class,
            'rules' => [
               [
                  'allow' => true,
                  'roles' => ['@'],
               ],
            ],
         ],
      ];
   }
   
   public function actionConnect(): Response
   {
      $user = User::findOne((int)Yii::$app->user->id);
      if ($user === null) {
         throw new NotFoundHttpException('Employee account not found.');
      }
      
      if (!TelegramStaffUserService::hasAllowedRole($user)) {
         throw new ForbiddenHttpException(
            'Only admin and techsupport employees can connect the staff bot.'
         );
      }
      
      return $this->redirect(TelegramStaffLinkService::createConnectLink($user));
   }
   
   public function actionDisconnect(): Response
   {
      $user = User::findOne((int)Yii::$app->user->id);
      if ($user === null) {
         throw new NotFoundHttpException('Employee account not found.');
      }
      
      TelegramStaffUserService::disconnect($user);
      Yii::$app->session->setFlash(
         'success',
         'Служебный Telegram отключён.'
      );
      
      return $this->redirect(Yii::$app->request->referrer ?: ['/site/index']);
   }
}
