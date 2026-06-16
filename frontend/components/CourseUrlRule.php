<?php

namespace frontend\components;

use Yii;
use yii\db\Query;
use yii\web\UrlRule;

class CourseUrlRule extends UrlRule
{
   public function parseRequest($manager, $request)
   {
      $result = parent::parseRequest($manager, $request);
      
      if ($result === false) {
         return false;
      }
      
      [$route, $params] = $result;
      
      $categorySlug = $params['category'] ?? null;
      $courseSlug = $params['slug'] ?? null;
      
      if (!$categorySlug || !$courseSlug) {
         return false;
      }
      
      $cacheKey = [
         'course-url-rule',
         $categorySlug,
         $courseSlug,
      ];
      
      $exists = Yii::$app->cache->getOrSet(
         $cacheKey,
         static function () use ($categorySlug, $courseSlug) {
            return (new Query())
               ->from(['course' => '{{%courses}}'])
               ->innerJoin(
                  ['category' => '{{%course_category}}'],
                  'category.id = course.category_id'
               )
               ->where([
                  'category.slug' => $categorySlug,
                  'course.slug' => $courseSlug,
               ])
               ->andWhere([
                  'category.status' => 1,
                  'course.status' => 1,
               ])
               ->exists();
         },
         300
      );
      
      if (!$exists) {
         /*
          * Это не существующий курс.
          * Yii продолжит проверять следующие rules.
          */
         return false;
      }
      
      return [$route, $params];
   }
}