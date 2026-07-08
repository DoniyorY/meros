<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gallery".
 *
 * @property int $id
 * @property int|null $page_id
 * @property string|null $image
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $user_id
 * @property int|null $status
 */
class Gallery extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public $imageFile;
    public $imageFiles;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gallery';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page_id', 'image', 'created_at', 'updated_at', 'user_id'], 'default', 'value' => null],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['page_id', 'created_at', 'updated_at', 'user_id', 'status'], 'integer'],
            [['image'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'extensions' => 'jpg, jpeg, gif, png, webp', 'skipOnEmpty' => true],
            [['imageFiles'], 'file', 'extensions' => 'jpg, jpeg, gif, png, webp', 'skipOnEmpty' => true, 'maxFiles' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page_id' => 'Page ID',
            'image' => 'Image',
            'imageFile' => 'Image',
            'imageFiles' => 'Images',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user_id' => 'User ID',
            'status' => 'Status',
        ];
    }

}
