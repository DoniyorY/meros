<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "course_features".
 *
 * @property int $id
 * @property int $course_id
 * @property string $name_en
 * @property string $name_ru
 * @property string $name_uz
 * @property string $desc_en
 * @property string $desc_ru
 * @property string $desc_uz
 * @property string $syllabus_file
 * @property string $flyer_file
 */
class CourseFeatures extends \yii\db\ActiveRecord
{
   
   
   /**
    * {@inheritdoc}
    */
   public static function tableName()
   {
      return 'course_features';
   }
   

   
   
   /**
    * {@inheritdoc}
    */
   public function rules()
   {
      return [
         [['course_id', 'name_en'], 'required'],
         [['course_id'], 'integer'],
         [['desc_en', 'desc_ru', 'desc_uz'], 'string'],
         [['name_en', 'name_ru', 'name_uz'], 'string', 'max' => 255],
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
         'name_en' => 'Name En',
         'name_ru' => 'Name Ru',
         'name_uz' => 'Name Uz',
         'desc_en' => 'Desc En',
         'desc_ru' => 'Desc Ru',
         'desc_uz' => 'Desc Uz',
      ];
   }
   
   public function getCourse()
   {
      return $this->hasOne(Courses::className(), ['id' => 'course_id']);
   }
}
