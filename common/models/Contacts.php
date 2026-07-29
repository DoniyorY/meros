<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;

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
    public $verifyCode;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if ($this->getIsNewRecord() && $this->status === null) {
            $this->status = 0;
        }
    }

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
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fullname', 'email', 'phone', 'subject', 'message'], 'required', 'except' => 'homepage'],
            [['fullname', 'phone', 'message'], 'required', 'on' => 'homepage'],
            [['message'], 'string'],
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['fullname', 'email', 'phone', 'subject'], 'string', 'max' => 255],
            ['email', 'email'],
            ['status', 'default', 'value' => 0],
            [['email', 'subject'], 'default', 'value' => '', 'on' => 'homepage'],
            ['verifyCode', 'captcha', 'except' => 'homepage'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fullname' => 'Full name',
            'email' => 'Email',
            'phone' => 'Phone',
            'subject' => 'Subject',
            'message' => 'Message',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'verifyCode' => 'Verification Code',
        ];
    }

}
