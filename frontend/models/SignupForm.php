<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\AuthAssignment;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $first_name;
    public $last_name;
    public $phone;
    public $password;
    public $password_confirm;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            [['first_name', 'last_name', 'phone'], 'trim'],
            [['first_name', 'last_name', 'phone'], 'required'],
            [['first_name', 'last_name', 'phone'], 'string', 'max' => 255],

            [['password', 'password_confirm'], 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
            ['password_confirm', 'compare', 'compareAttribute' => 'password'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'username' => $this->t('profile_username'),
            'email' => $this->t('profile_email'),
            'first_name' => $this->t('first_name'),
            'last_name' => $this->t('last_name'),
            'phone' => $this->t('profile_phone'),
            'password' => $this->t('login_password'),
            'password_confirm' => $this->t('confirm_password'),
        ];
    }

    private function t($key)
    {
        $lang = Yii::$app->language;
        return Yii::$app->params[$key][$lang] ?? Yii::$app->params[$key]['en'] ?? $key;
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->fullname = trim($this->first_name . ' ' . $this->last_name);
            $user->phone = $this->phone;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();

            if (!$user->save()) {
                $this->addErrors($user->getErrors());
                $transaction->rollBack();
                return false;
            }

            $authAssignment = new AuthAssignment([
                'item_name' => 'guest',
                'user_id' => (string)$user->id,
                'created_at' => time(),
            ]);

            if (!$authAssignment->save()) {
                $this->addErrors($authAssignment->getErrors());
                $transaction->rollBack();
                return false;
            }

            if (!$this->sendEmail($user)) {
                $transaction->rollBack();
                return false;
            }

            $transaction->commit();
            return true;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage(), __METHOD__);
            return false;
        }
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
