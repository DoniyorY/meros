<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "post_images".
 *
 * @property int $id
 * @property int $post_id
 * @property string|null $name_ru
 * @property string $name_en
 * @property string|null $name_uz
 * @property string $image
 */
class PostImages extends \yii\db\ActiveRecord
{

    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post_images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_ru', 'name_uz'], 'default', 'value' => null],
            [['post_id', 'name_en', 'image'], 'required'],
            [['post_id'], 'integer'],
            [['name_ru', 'name_en', 'name_uz', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'name_ru' => 'Name Ru',
            'name_en' => 'Name En',
            'name_uz' => 'Name Uz',
            'image' => 'Image',
        ];
    }

    public function getPost()
    {
        return $this->hasOne(Posts::className(), ['id' => 'post_id']);
    }

}
