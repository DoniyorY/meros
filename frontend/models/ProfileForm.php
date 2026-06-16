<?php

namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;

class ProfileForm extends Model
{
    public $fullname;
    public $username;
    public $email;
    public $phone;
    public $address;

    private $_user;

    public function __construct(User $user, $config = [])
    {
        $this->_user = $user;
        $this->fullname = $user->fullname;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->address = $user->address;

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['fullname', 'username', 'email', 'phone'], 'trim'],
            [['address'], 'trim'],
            [['fullname', 'username', 'email', 'phone'], 'required'],
            [['fullname', 'username', 'email', 'phone', 'address'], 'string', 'max' => 255],
            ['email', 'email'],
            ['username', 'validateUniqueUsername'],
            ['email', 'validateUniqueEmail'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'fullname' => $this->t('profile_full_name'),
            'username' => $this->t('profile_username'),
            'email' => $this->t('profile_email'),
            'phone' => $this->t('profile_phone'),
            'address' => $this->t('profile_address'),
        ];
    }

    public function validateUniqueUsername($attribute)
    {
        $exists = User::find()
            ->where(['username' => $this->$attribute])
            ->andWhere(['<>', 'id', $this->_user->id])
            ->exists();

        if ($exists) {
            $this->addError($attribute, $this->t('profile_username_taken'));
        }
    }

    public function validateUniqueEmail($attribute)
    {
        $exists = User::find()
            ->where(['email' => $this->$attribute])
            ->andWhere(['<>', 'id', $this->_user->id])
            ->exists();

        if ($exists) {
            $this->addError($attribute, $this->t('profile_email_taken'));
        }
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = $this->_user;
        $user->fullname = $this->fullname;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->address = $this->address;

        return $user->save(false);
    }

    private function t($key)
    {
        $lang = Yii::$app->language;
        return Yii::$app->params[$key][$lang] ?? Yii::$app->params[$key]['en'] ?? $key;
    }
}
