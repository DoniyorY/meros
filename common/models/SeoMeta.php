<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "seo_meta".
 *
 * @property int $id
 * @property string $entity_type
 * @property int $entity_id
 * @property string|null $title_ru
 * @property string|null $title_en
 * @property string|null $title_uz
 * @property string|null $description_ru
 * @property string|null $description_en
 * @property string|null $description_uz
 * @property string|null $h1_ru
 * @property string|null $h1_en
 * @property string|null $h1_uz
 * @property string|null $text_ru
 * @property string|null $text_en
 * @property string|null $text_uz
 * @property string|null $canonical
 * @property string|null $og_image
 * @property string|null $robots
 * @property int $created_at
 * @property int $updated_at
 */
class SeoMeta extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'seo_meta';
    }
   
   public const TYPE_COURSE = 'course';
   public const TYPE_COURSE_CATEGORY = 'course_category';
   public const TYPE_POST = 'post';
   public const TYPE_EVENT = 'event';
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title_ru', 'title_en', 'title_uz', 'description_ru', 'description_en', 'description_uz', 'h1_ru', 'h1_en', 'h1_uz', 'text_ru', 'text_en', 'text_uz', 'canonical', 'og_image', 'robots'], 'default', 'value' => null],
            [['entity_type', 'entity_id', 'created_at', 'updated_at'], 'required'],
            [['entity_id', 'created_at', 'updated_at'], 'integer'],
            [['description_ru', 'description_en', 'description_uz', 'text_ru', 'text_en', 'text_uz'], 'string'],
            [['entity_type'], 'string', 'max' => 50],
            [['title_ru', 'title_en', 'title_uz', 'h1_ru', 'h1_en', 'h1_uz', 'robots'], 'string', 'max' => 255],
            [['canonical', 'og_image'], 'string', 'max' => 1000],
            [['entity_type', 'entity_id'], 'unique', 'targetAttribute' => ['entity_type', 'entity_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entity_type' => 'Entity Type',
            'entity_id' => 'Entity ID',
            'title_ru' => 'Title Ru',
            'title_en' => 'Title En',
            'title_uz' => 'Title Uz',
            'description_ru' => 'Description Ru',
            'description_en' => 'Description En',
            'description_uz' => 'Description Uz',
            'h1_ru' => 'H1 Ru',
            'h1_en' => 'H1 En',
            'h1_uz' => 'H1 Uz',
            'text_ru' => 'Text Ru',
            'text_en' => 'Text En',
            'text_uz' => 'Text Uz',
            'canonical' => 'Canonical',
            'og_image' => 'Og Image',
            'robots' => 'Robots',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}
