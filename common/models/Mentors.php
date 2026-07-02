<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mentors".
 *
 * @property int $id
 * @property string $fullname
 * @property string|null $position_ru
 * @property string|null $position_en
 * @property string|null $position_uz
 * @property string|null $desc_ru
 * @property string|null $desc_en
 * @property string|null $desc_uz
 * @propertu string|null $avatar
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $image
 * @property int|null $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $user_id
 * @property string|null $instagram_link
 * @property string|null $linked_in_link
 * @property string|null $facebook_link
 */
class Mentors extends \yii\db\ActiveRecord
{
   
   public $imageFile;
   
   /**
    * {@inheritdoc}
    */
   public static function tableName()
   {
      return 'mentors';
   }
   
   /**
    * {@inheritdoc}
    */
   public function rules()
   {
      return [
         [['email', 'phone', 'image', 'status', 'instagram_link', 'linked_in_link', 'facebook_link'], 'default', 'value' => null],
         [['fullname', 'created_at', 'updated_at', 'user_id'], 'required'],
         [['status', 'created_at', 'updated_at', 'user_id'], 'integer'],
         [['fullname', 'email', 'phone', 'image', 'instagram_link', 'linked_in_link', 'facebook_link', 'position_ru', 'position_en', 'position_uz', 'desc_ru', 'desc_en', 'desc_uz', 'avatar'], 'string', 'max' => 255],
         [['imageFile', 'avatar'], 'file', 'extensions' => 'jpg, gif, png', 'skipOnEmpty' => true],
      ];
   }
   
   /**
    * {@inheritdoc}
    */
   public function attributeLabels()
   {
      return [
         'id' => 'ID',
         'fullname' => 'Fullname',
         'email' => 'Email',
         'phone' => 'Phone',
         'image' => 'Image',
         'status' => 'Status',
         'created_at' => 'Created At',
         'updated_at' => 'Updated At',
         'user_id' => 'User ID',
         'instagram_link' => 'Instagram Link',
         'linked_in_link' => 'Linked In Link',
         'facebook_link' => 'Facebook Link',
      ];
   }
   
   public function getUser()
   {
      return $this->hasOne(User::class, ['id' => 'user_id']);
   }
   
}
