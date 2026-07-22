<?php

use common\services\UserLoginSessionService;
use yii\helpers\Html;

/** @var \common\models\User $model */
/** @var \common\models\UserLoginSession[] $loginSessions */

$currentTokenHash = UserLoginSessionService::getCurrentTokenHash();

$deviceIcons = [
   'mobile' => 'ri-smartphone-line',
   'smartphone' => 'ri-smartphone-line',
   'tablet' => 'ri-tablet-line',
   'laptop' => 'ri-macbook-line',
   'desktop' => 'ri-computer-line',
];
?>
   
   <div class="mt-4 mb-3 border-bottom pb-2">
      <?php if (!empty($loginSessions)): ?>
         <div class="float-end">
            <?= Html::a(
               'Logout All Other Sessions',
               [
                  'logout-other-sessions',
                  'userId' => $model->id,
               ],
               [
                  'class' => 'link-primary',
                  'data-method' => 'post',
                  'data-confirm' => 'Are you sure you want to logout all other sessions?',
               ]
            ) ?>
         </div>
      <?php endif; ?>
      
      <h5 class="card-title">Login History</h5>
   </div>

<?php if (empty($loginSessions)): ?>
   
   <div class="text-center py-4">
      <div class="avatar-md mx-auto mb-3">
         <div class="avatar-title bg-light text-muted rounded-circle fs-24">
            <i class="ri-history-line"></i>
         </div>
      </div>
      
      <h6 class="mb-1">No Login History</h6>
      
      <p class="text-muted mb-0">
         No active or previous login sessions were found.
      </p>
   </div>

<?php else: ?>
   
   <?php foreach ($loginSessions as $loginSession): ?>
      <?php
      $deviceType = strtolower((string) $loginSession->device_type);
      
      $deviceIcon = $deviceIcons[$deviceType]
         ?? 'ri-device-line';
      
      $isCurrentSession = $currentTokenHash !== null
         && hash_equals(
            $loginSession->token_hash,
            $currentTokenHash
         );
      
      $deviceName = $loginSession->device_name;
      
      if (!$deviceName) {
         $deviceName = implode(' · ', array_filter([
            $loginSession->browser,
            $loginSession->operating_system,
         ]));
      }
      
      $deviceName = $deviceName ?: 'Unknown Device';
      
      $location = implode(', ', array_filter([
         $loginSession->city,
         $loginSession->country,
      ]));
      
      $location = $location ?: 'Unknown Location';
      
      $lastActivity = Yii::$app->formatter->asDatetime(
         $loginSession->last_seen_at,
         'php:F d \a\t g:iA'
      );
      ?>
      
      <div class="d-flex align-items-center mb-3">
         <div class="flex-shrink-0 avatar-sm">
            <div class="avatar-title bg-light text-primary rounded-3 fs-18">
               <i class="<?= Html::encode($deviceIcon) ?>"></i>
            </div>
         </div>
         
         <div class="flex-grow-1 ms-3">
            <div class="d-flex align-items-center gap-2">
               <h6 class="mb-1">
                  <?= Html::encode($deviceName) ?>
               </h6>
               
               <?php if ($isCurrentSession): ?>
                  <span class="badge bg-success-subtle text-success">
                            Current Session
                        </span>
               <?php elseif ($loginSession->getIsActive()): ?>
                  <span class="badge bg-primary-subtle text-primary">
                            Active
                        </span>
               <?php endif; ?>
            </div>
            
            <p class="text-muted mb-0">
               <?= Html::encode($location) ?>
               -
               <?= Html::encode($lastActivity) ?>
            </p>
            
            <?php if ($loginSession->ip_address): ?>
               <small class="text-muted">
                  IP:
                  <?= Html::encode($loginSession->ip_address) ?>
               </small>
            <?php endif; ?>
         </div>
         
         <div class="ms-3">
            <?php if ($loginSession->getIsActive()): ?>
               
               <?= Html::a(
                  'Logout',
                  [
                     'logout-session',
                     'id' => $loginSession->id,
                  ],
                  [
                     'class' => $isCurrentSession
                        ? 'link-danger'
                        : 'link-primary',
                     'data-method' => 'post',
                     'data-confirm' => $isCurrentSession
                        ? 'Are you sure you want to logout from the current session?'
                        : 'Are you sure you want to logout this session?',
                  ]
               ) ?>
            
            <?php elseif ($loginSession->revoked_at !== null): ?>
               
               <span class="text-muted">
                        Revoked
                    </span>
            
            <?php elseif ($loginSession->logged_out_at !== null): ?>
               
               <span class="text-muted">
                        Logged Out
                    </span>
            
            <?php else: ?>
               
               <span class="text-muted">
                        Expired
                    </span>
            
            <?php endif; ?>
         </div>
      </div>
   <?php endforeach; ?>

<?php endif; ?>