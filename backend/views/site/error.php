<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var string $name
 * @var string $message
 * @var Exception|yii\base\ErrorException $exception
 */

$statusCode = Yii::$app->response->statusCode;

$errorData = match ($statusCode) {
   400 => [
      'title' => 'Bad Request',
      'description' => 'The server could not understand your request.',
   ],
   401 => [
      'title' => 'Unauthorized',
      'description' => 'Authentication is required to access this page.',
   ],
   403 => [
      'title' => 'Access Denied',
      'description' => 'You do not have permission to access this page.',
   ],
   404 => [
      'title' => 'Page Not Found',
      'description' => 'The page you are looking for is not available.',
   ],
   500 => [
      'title' => 'Internal Server Error',
      'description' => 'Something went wrong on our side. Please try again later.',
   ],
   503 => [
      'title' => 'Service Unavailable',
      'description' => 'The service is temporarily unavailable.',
   ],
   default => [
      'title' => $name ?: 'Unexpected Error',
      'description' => 'An unexpected error occurred.',
   ],
};

$this->title = $statusCode . ' — ' . $errorData['title'];

$imageUrl = Yii::getAlias('@web') . '/images/error.svg';
?>

<div class="page-content">
    <div class="auth-page-wrapper pt-5">

        <div class="auth-page-content">
            <div class="container">

                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="text-center pt-4">

                            <div>
                               <?= Html::img($imageUrl, [
                                  'alt' => Html::encode($errorData['title']),
                                  'class' => 'error-basic-img move-animation',
                               ]) ?>
                            </div>

                            <div class="mt-4">
                                <h1 class="display-1 fw-medium">
                                   <?= Html::encode($statusCode) ?>
                                </h1>

                                <h3 class="text-uppercase">
                                   <?= Html::encode($errorData['title']) ?>
                                </h3>

                                <p class="text-muted mb-2">
                                   <?= Html::encode($errorData['description']) ?>
                                </p>
                               
                               <?php if (!empty($message)): ?>
                                   <div class="text-muted mb-4">
                                      <?= nl2br(Html::encode($message)) ?>
                                   </div>
                               <?php endif; ?>
                               
                               <?= Html::a(
                                  '<i class="mdi mdi-home me-1"></i> Back to home',
                                  Url::home(),
                                  ['class' => 'btn btn-success']
                               ) ?>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>