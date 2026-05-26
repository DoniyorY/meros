<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "course_lessons".
 *
 * @property int $id
 * @property int $course_id
 * @property string $slug
 * @property string $name_ru
 * @property string $name_en
 * @property string $name_uz
 * @property string $desc_ru
 * @property string $desc_en
 * @property string $desc_uz
 * @property string $video_link
 * @property string $duration
 * @property int $created_at
 * @property int $updated_at
 * @property int $video_sources
 * @property int|null $status
 * @property int $user_id
 * @property int $sort
 */
class CourseLessons extends \yii\db\ActiveRecord
{
   
   const STATUS_ACTIVE = 1;
   const STATUS_INACTIVE = 0;
   
   /**
    * {@inheritdoc}
    */
   public static function tableName()
   {
      return 'course_lessons';
   }
   
   public function behaviors()
   {
      return array_merge(parent::behaviors(), [
         'slug' => [
            'class' => SluggableBehavior::class,
            'attribute' => 'name_en',
            'slugAttribute' => 'slug',
         ]
      ]);
   }
   
   public $video;
   
   /**
    * {@inheritdoc}
    */
   public function rules()
   {
      return [
         [['status'], 'default', 'value' => 1],
         [['course_id', 'name_ru', 'name_en', 'name_uz', 'desc_ru', 'desc_en', 'desc_uz', 'created_at', 'updated_at', 'user_id', 'sort', 'video_link'], 'required'],
         [['course_id', 'created_at', 'updated_at', 'status', 'user_id', 'sort', 'video_sources'], 'integer'],
         [['desc_ru', 'desc_en', 'desc_uz', 'duration'], 'string'],
         [['slug', 'name_ru', 'name_en', 'name_uz'], 'string', 'max' => 255],
         [['video'], 'file', 'skipOnEmpty' => true, 'extensions' => 'mp4, mov, avi, mkv', 'maxSize' => 1024 * 1024 * 200,],
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
         'slug' => 'Slug',
         'name_ru' => 'Name Ru',
         'name_en' => 'Name En',
         'name_uz' => 'Name Uz',
         'desc_ru' => 'Desc Ru',
         'desc_en' => 'Desc En',
         'desc_uz' => 'Desc Uz',
         'created_at' => 'Created At',
         'updated_at' => 'Updated At',
         'status' => 'Status',
         'user_id' => 'User ID',
         'sort' => 'Sort',
      ];
   }
   
   public function uploadVideo()
   {
      if ($this->validate(['video'])) {
         $fileName = "$this->slug-" . date('dmY_His') . ".{$this->video->extension}";
         $path = \Yii::getAlias('@frontend/web/uploads/lessons/') . $fileName;
         
         if ($this->video->saveAs($path)) {
            return $fileName;
         }
      }
      return false;
   }
   
   public function getUser()
   {
      return $this->hasOne(User::class, ['id' => 'user_id']);
   }
   
   public function getCourse()
   {
      return $this->hasOne(Courses::class, ['id' => 'course_id']);
   }
   
}
