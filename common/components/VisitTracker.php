<?php
namespace common\components;

use Yii;
use yii\base\Component;
use common\models\SiteVisit;

class VisitTracker extends Component
{
   public function track(): void
   {
      $request = Yii::$app->request;
      
      if (!$request->isGet || $request->isAjax) {
         return;
      }
      
      $path = trim($request->pathInfo, '/');
      
      $excludedPrefixes = [
         'uploads/',
         'img/',
         'images/',
         'css/',
         'js/',
         'assets/',
      ];
      
      foreach ($excludedPrefixes as $prefix) {
         if (str_starts_with($path, $prefix)) {
            return;
         }
      }
      
      $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
      
      $excludedExtensions = [
         'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'ico',
         'css', 'js', 'map',
         'woff', 'woff2', 'ttf', 'eot',
         'pdf', 'zip', 'rar',
      ];
      
      if (in_array($extension, $excludedExtensions, true)) {
         return;
      }
      
      $visit = new SiteVisit([
         'user_id' => Yii::$app->user->isGuest
            ? null
            : Yii::$app->user->id,
         'session_id' => Yii::$app->session->id,
         'ip_address' => $request->userIP,
         'user_agent' => $request->userAgent,
         'url' => $request->absoluteUrl,
         'referrer' => $request->referrer,
         'visited_at' => time(),
      ]);
      
      $visit->save(false);
   }
}
?>