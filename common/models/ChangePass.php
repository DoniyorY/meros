<?php

namespace common\models;

use yii\base\Model;
use Yii;

class ChangePass extends Model
{
   public string $old_password = '';
   public string $new_password = '';
   public string $confirm_password = '';
   
   private User $_user;
   
   public function __construct(User $user, $config = [])
   {
      $this->_user = $user;
      
      parent::__construct($config);
   }
   
   public function rules(): array
   {
      return [
         [
            ['old_password', 'new_password', 'confirm_password'],
            'required',
         ],
         
         [
            'old_password',
            'validateOldPassword',
         ],
         
         [
            'new_password',
            'string',
            'min' => 6,
            'max' => 255,
         ],
         
         [
            'confirm_password',
            'compare',
            'compareAttribute' => 'new_password',
            'message' => 'Confirm password does not match.',
         ],
      ];
   }
   
   public function attributeLabels(): array
   {
      return [
         'old_password' => 'Old Password',
         'new_password' => 'New Password',
         'confirm_password' => 'Confirm Password',
      ];
   }
   
   public function validateOldPassword(string $attribute): void
   {
      if ($this->hasErrors()) {
         return;
      }
      
      if (!$this->_user->validatePassword($this->$attribute)) {
         $this->addError($attribute, 'Incorrect old password.');
      }
   }
   
   public function changePassword(): bool
   {
      if (!$this->validate()) {
         return false;
      }
      
      $this->_user->setPassword($this->new_password);
      $this->_user->generateAuthKey();
      
      return $this->_user->save(false);
   }
}