<?php

namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;

class ChangePasswordForm extends Model
{
    public $currentPassword;
    public $newPassword;
    public $repeatPassword;

    private $_user;

    public function __construct(User $user, $config = [])
    {
        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['currentPassword', 'newPassword', 'repeatPassword'], 'required'],
            ['currentPassword', 'validateCurrentPassword'],
            ['newPassword', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
            ['repeatPassword', 'compare', 'compareAttribute' => 'newPassword', 'message' => $this->t('profile_password_mismatch')],
        ];
    }

    public function attributeLabels()
    {
        return [
            'currentPassword' => $this->t('profile_current_password'),
            'newPassword' => $this->t('profile_new_password'),
            'repeatPassword' => $this->t('profile_repeat_password'),
        ];
    }

    public function validateCurrentPassword($attribute)
    {
        if (!$this->hasErrors() && !$this->_user->validatePassword($this->$attribute)) {
            $this->addError($attribute, $this->t('profile_current_password_invalid'));
        }
    }

    public function changePassword()
    {
        if (!$this->validate()) {
            return false;
        }

        $this->_user->setPassword($this->newPassword);
        $this->_user->generateAuthKey();

        return $this->_user->save(false);
    }

    private function t($key)
    {
        $lang = Yii::$app->language;
        return Yii::$app->params[$key][$lang] ?? Yii::$app->params[$key]['en'] ?? $key;
    }
}
