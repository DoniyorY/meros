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
            [['page_id', 'image', 'created_at', 'updated_at', 'user_id', 'status'], 'default', 'value' => null],
            [['page_id', 'created_at', 'updated_at', 'user_id', 'status'], 'integer'],
            [['image'], 'string', 'max' => 255],
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user_id' => 'User ID',
            'status' => 'Status',
        ];
    }

}
