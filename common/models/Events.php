<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "events".
 *
 * @property int $id
 * @property string|null $name_ru
 * @property string $name_en
 * @property string|null $name_uz
 * @property string|null $desc_ru
 * @property string $desc_en
 * @property string|null $desc_uz
 * @property string|null $content_ru
 * @property string $content_en
 * @property string|null $content_uz
 * @property string|null $image
 * @property int $created_at
 * @property int $updated_at
 * @property int $user_id
 * @property int $status
 * @property string|null $video_link
 */
class Events extends \yii\db\ActiveRecord
{
   
   
   /**
    * {@inheritdoc}
    */
   public static function tableName()
   {
      return 'events';
   }
   
   public $imageFile;
   
   /**
    * {@inheritdoc}
    */
   public function rules()
   {
      return [
         [['name_ru', 'name_uz', 'desc_ru', 'desc_uz', 'content_ru', 'content_uz', 'image', 'video_link'], 'default', 'value' => null],
         [['name_en', 'desc_en', 'content_en', 'created_at', 'updated_at', 'user_id', 'status'], 'required'],
         ['video_link', 'url', 'defaultScheme' => 'https'],
         [['content_ru', 'content_en', 'content_uz'], 'string'],
         [['created_at', 'updated_at', 'user_id', 'status'], 'integer'],
         [['name_ru', 'name_en', 'name_uz', 'desc_ru', 'desc_en', 'desc_uz', 'image', 'video_link'], 'string', 'max' => 255],
         [['imageFile'], 'file', 'extensions' => 'jpg, jpeg, gif, png, webp', 'skipOnEmpty' => true],
      ];
   }
   
   /**
    * {@inheritdoc}
    */
   public function attributeLabels()
   {
      return [
         'id' => 'ID',
         'name_ru' => 'Name Ru',
         'name_en' => 'Name En',
         'name_uz' => 'Name Uz',
         'desc_ru' => 'Desc Ru',
         'desc_en' => 'Desc En',
         'desc_uz' => 'Desc Uz',
         'content_ru' => 'Content Ru',
         'content_en' => 'Content En',
         'content_uz' => 'Content Uz',
         'image' => 'Image',
         'created_at' => 'Created At',
         'updated_at' => 'Updated At',
         'user_id' => 'User ID',
         'status' => 'Status',
         'video_link' => 'Video Link',
      ];
   }
   
   public function getUser()
   {
      return $this->hasOne(User::class, ['id' => 'user_id']);
   }
   
}
