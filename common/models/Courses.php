<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\helpers\Inflector;

/**
 * This is the model class for table "courses".
 *
 * @property int $id
 * @property int $category_id
 * @property int $page_type
 * @property string $slug
 * @property string $name_ru
 * @property string $name_en
 * @property string $name_uz
 * @property string $title_ru
 * @property string $title_en
 * @property string $title_uz
 * @property string $desc_ru
 * @property string $desc_en
 * @property string $desc_uz
 * @property int $created_at
 * @property int $updated_at
 * @property int|null $status
 * @property int $user_id
 * @property int|null $mentor_id
 * @property string|null $preview_video_link
 * @property string|null $image
 * @property string $syllabus_file
 * @property string $flyer_file
 * @property string $course_icons
 */
class Courses extends \yii\db\ActiveRecord
{
   
   const STATUS_ACTIVE = 1;
   const STATUS_INACTIVE = 0;
   public $imageFile;
   public $syllabus;
   public $flyer;
   public $icon;
   
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
   
   /**
    * {@inheritdoc}
    */
   public static function tableName()
   {
      return 'courses';
   }
   
   /**
    * {@inheritdoc}
    */
   public function rules()
   {
      return [
         [['preview_video_link', 'mentor_id', 'image'], 'default', 'value' => null],
         [['status'], 'default', 'value' => 1],
         [['category_id', 'name_ru', 'name_en', 'name_uz', 'desc_ru', 'desc_en', 'desc_uz', 'created_at', 'updated_at', 'user_id','page_type'], 'required'],
         [['category_id', 'created_at', 'updated_at', 'status', 'user_id', 'mentor_id'], 'integer'],
         [['desc_ru', 'desc_en', 'desc_uz'], 'string'],
         [['slug', 'name_ru', 'name_en', 'name_uz', 'preview_video_link', 'image', 'title_ru', 'title_en', 'title_uz'], 'string', 'max' => 255],
         [['imageFile', 'icon'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, jpeg, png, gif', 'maxSize' => 1024 * 1024 * 5],
         [['syllabus', 'flyer'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf, doc, docx'],
      ];
   }
   
   public function uploadImage()
   {
      if ($this->validate(['imageFile'])) {
         $dir = Yii::getAlias('@frontend/web/uploads/courses/');
         
         if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
         }
         
         $baseName = $this->slug ?: Inflector::slug($this->name_en);
         $fileName = $baseName . '-' . date('d.m.Y_H.i.s') . '.' . $this->imageFile->extension;
         
         if ($this->imageFile->saveAs($dir . $fileName)) {
            return $fileName;
         }
      }
      
      return false;
   }
   
   /**
    * {@inheritdoc}
    */
   public function attributeLabels()
   {
      return [
         'id' => 'ID',
         'category_id' => 'Category ID',
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
         'mentor_id' => 'Mentor ID',
         'preview_video_link' => 'Preview Video Link',
         'image' => 'Image',
         'imageFile' => 'Image'
      ];
   }
   
   public function getCategory()
   {
      return $this->hasOne(CourseCategory::class, ['id' => 'category_id']);
   }
   
   public function getUser()
   {
      return $this->hasOne(User::class, ['id' => 'user_id']);
   }
   
   public function getMentor()
   {
      return $this->hasOne(Mentors::class, ['id' => 'mentor_id']);
   }
   
   public function getLessons()
   {
      return $this->hasMany(CourseLessons::class, ['course_id' => 'id'])->orderBy(['sort' => SORT_ASC]);
   }
   
   public function getFeatures()
   {
      return $this->hasMany(CourseFeatures::class, ['course_id' => 'id']);
   }
   
   public function getSubs()
   {
      return $this->hasMany(SubscriptionPlans::class, ['course_id' => 'id']);
   }
}
