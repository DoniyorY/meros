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
      
      $userAgent = Yii::$app->request->userAgent;
      
      
      
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
      $visit->is_bot = $this->isBot($request->userAgent) ? 1 : 0;
      $visit->save(false);
   }
   private function isBot(?string $userAgent): bool
   {
      if (!$userAgent) {
         return true;
      }
      
      return (bool) preg_match(
         '/bot|crawler|spider|slurp|facebookexternalhit|preview|'
         . 'google|bing|yandex|baidu|duckduck|ahrefs|semrush|'
         . 'mj12bot|dotbot|petalbot|gptbot|chatgpt-user/i',
         $userAgent
      );
   }
}
?>