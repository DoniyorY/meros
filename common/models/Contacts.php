<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "contacts".
 *
 * @property int $id
 * @property string $fullname
 * @property string $email
 * @property string $phone
 * @property string $subject
 * @property string $message
 * @property int $created_at
 * @property int $updated_at
 * @property int $status
 */
class Contacts extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contacts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fullname', 'email', 'phone', 'subject', 'message', 'created_at', 'updated_at', 'status'], 'required'],
            [['message'], 'string'],
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['fullname', 'email', 'phone', 'subject'], 'string', 'max' => 255],
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
            'subject' => 'Subject',
            'message' => 'Message',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

}
