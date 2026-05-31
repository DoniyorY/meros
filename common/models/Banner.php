<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "banner".
 *
 * @property int $id
 * @property string $name_ru
 * @property string $name_en
 * @property string $name_uz
 * @property string $desc_ru
 * @property string $desc_en
 * @property string $desc_uz
 * @property string $image
 * @property string|null $link
 * @property int $created_at
 * @property int $updated_at
 * @property int $status
 */
class Banner extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banner';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['link'], 'default', 'value' => null],
            [['name_ru', 'name_en', 'name_uz', 'desc_ru', 'desc_en', 'desc_uz',], 'required'],
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['name_ru', 'name_en', 'name_uz', 'desc_ru', 'desc_en', 'desc_uz', 'image', 'link'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'extensions' => 'jpg, gif, png', 'skipOnEmpty' => true],
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
            'image' => 'Image',
            'link' => 'Link',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

}
