<?php

namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class ProfileForm extends Model
{
    public $fullname;
    public $username;
    public $email;
    public $phone;
    public $address;
    public $imageFile;

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
            [
                'imageFile',
                'file',
                'skipOnEmpty' => true,
                'extensions' => ['jpg', 'jpeg', 'png', 'webp'],
                'maxSize' => 1024 * 1024 * 5,
            ],
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
            'imageFile' => $this->t('profile_photo'),
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
        $this->imageFile = UploadedFile::getInstance($this, 'imageFile');

        if (!$this->validate()) {
            return false;
        }

        $user = $this->_user;
        $oldImage = $user->image;
        $user->fullname = $this->fullname;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->address = $this->address;

        if ($this->imageFile) {
            $fileName = $this->saveImageFile();
            if ($fileName === null) {
                return false;
            }
            $user->image = $fileName;
        }

        if (!$user->save(false)) {
            return false;
        }

        if ($this->imageFile && $oldImage && $oldImage !== $user->image) {
            $this->deleteOldImage($oldImage);
        }

        return true;
    }

    private function saveImageFile()
    {
        $uploadDir = Yii::getAlias('@frontend/web/uploads/users');
        FileHelper::createDirectory($uploadDir);

        $baseName = preg_replace('/[^a-z0-9_-]+/i', '-', pathinfo($this->imageFile->baseName, PATHINFO_FILENAME));
        $baseName = trim($baseName, '-') ?: 'profile-photo';
        $fileName = 'user_' . $this->_user->id . '_' . $baseName . '_' . date('YmdHis') . '.' . strtolower($this->imageFile->extension);

        if (!$this->imageFile->saveAs($uploadDir . DIRECTORY_SEPARATOR . $fileName)) {
            $this->addError('imageFile', $this->t('profile_photo_upload_error'));
            return null;
        }

        return $fileName;
    }

    private function deleteOldImage($fileName)
    {
        $path = Yii::getAlias('@frontend/web/uploads/users/' . basename($fileName));
        if (is_file($path)) {
            @unlink($path);
        }
    }

    private function t($key)
    {
        $lang = Yii::$app->language;
        return Yii::$app->params[$key][$lang] ?? Yii::$app->params[$key]['en'] ?? $key;
    }
}
