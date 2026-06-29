<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "read_more".
 *
 * @property int $id
 * @property int $course_id
 * @property string $title_en
 * @property string $title_ru
 * @property string $title_uz
 * @property string $content_en
 * @property string $content_ru
 * @property string $content_uz
 */
class ReadMore extends \yii\db\ActiveRecord
{
   
   
   /**
    * {@inheritdoc}
    */
   public static function tableName()
   {
      return 'read_more';
   }
   
   /**
    * {@inheritdoc}
    */
   public function rules()
   {
      return [
         [['course_id', 'title_en', 'content_en'], 'required'],
         [['course_id'], 'integer'],
         [['content_en', 'content_ru', 'content_uz'], 'string'],
         [['title_en', 'title_ru', 'title_uz'], 'string', 'max' => 255],
         [['title_ru', 'title_uz', 'content_ru', 'content_uz'], 'default', 'value' => '-'],
      ];
   }
   
   /**
    * {@inheritdoc}
    */
   public function attributeLabels()
   {
      return [
         'id' => 'ID',
         'course_id' => 'Course ID',
         'title_en' => 'Title En',
         'title_ru' => 'Title Ru',
         'title_uz' => 'Title Uz',
         'content_en' => 'Content En',
         'content_ru' => 'Content Ru',
         'content_uz' => 'Content Uz',
      ];
   }
   
   public function getCourse()
   {
      return $this->hasOne(Courses::className(), ['id' => 'course_id']);
   }
   
}
