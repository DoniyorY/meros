<?php

namespace frontend\components;

use common\models\CourseCategory;
use common\models\Courses;
use yii\base\BaseObject;
use yii\web\UrlRuleInterface;

class CourseUrlRule extends BaseObject implements UrlRuleInterface
{
   public function createUrl($manager, $route, $params)
   {
      if ($route !== 'courses/index' || empty($params['category']) || empty($params['slug'])) {
         return false;
      }

      $category = $params['category'];
      $slug = $params['slug'];
      unset($params['category'], $params['slug']);

      $url = $category . '/' . $slug;
      if (!empty($params)) {
         $url .= '?' . http_build_query($params);
      }

      return $url;
   }

   public function parseRequest($manager, $request)
   {
      $pathInfo = trim($request->getPathInfo(), '/');

      if (!preg_match('#^([a-z0-9-]+)/([a-z0-9-]+)$#', $pathInfo, $matches)) {
         return false;
      }

      $category = $matches[1];
      $slug = $matches[2];
      $exists = Courses::find()
         ->alias('course')
         ->joinWith('category courseCategory')
         ->andWhere([
            'course.slug' => $slug,
            'course.status' => Courses::STATUS_ACTIVE,
            'courseCategory.slug' => $category,
            'courseCategory.status' => CourseCategory::STATUS_ACTIVE,
         ])
         ->exists();

      if (!$exists) {
         return false;
      }

      return ['courses/index', ['category' => $category, 'slug' => $slug]];
   }
}
