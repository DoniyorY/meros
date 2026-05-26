<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "posts".
 *
 * @property int $id
 * @property int $category_id
 * @property string $name_ru
 * @property string $name_en
 * @property string $name_uz
 * @property string $desc_ru
 * @property string $desc_en
 * @property string $desc_uz
 * @property string $content_ru
 * @property string $content_en
 * @property string $content_uz
 * @property int $created_at
 * @property int $updated_at
 * @property int $status
 * @property int $user_id
 * @property string $image
 */
class Posts extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    public $imageFile;
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
        return 'posts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => 1],
            [['category_id', 'name_ru', 'name_en', 'name_uz', 'desc_ru', 'desc_en', 'desc_uz', 'content_ru', 'content_en', 'content_uz', 'created_at', 'updated_at', 'user_id', 'image'], 'required'],
            [['category_id', 'created_at', 'updated_at', 'status', 'user_id'], 'integer'],
            [['desc_ru', 'desc_en', 'desc_uz', 'content_ru', 'content_en', 'content_uz','slug'], 'string'],
            [['name_ru', 'name_en', 'name_uz', 'image','slug'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'name_ru' => 'Name Ru',
            'name_en' => 'Name En',
            'name_uz' => 'Name Uz',
            'desc_ru' => 'Desc Ru',
            'desc_en' => 'Desc En',
            'desc_uz' => 'Desc Uz',
            'content_ru' => 'Content Ru',
            'content_en' => 'Content En',
            'content_uz' => 'Content Uz',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'user_id' => 'User ID',
            'image' => 'Image',
        ];
    }

}
