<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "site_visit".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $session_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $url
 * @property string|null $referrer
 * @property int $visited_at
 */
class SiteVisit extends \yii\db\ActiveRecord
{
   
   
   /**
    * {@inheritdoc}
    */
   public static function tableName()
   {
      return 'site_visit';
   }
   
   /**
    * {@inheritdoc}
    */
   public function rules()
   {
      return [
         [['user_id', 'session_id', 'ip_address', 'user_agent', 'url', 'referrer'], 'default', 'value' => null],
         [['user_id', 'visited_at'], 'integer'],
         [['user_agent'], 'string'],
         [['visited_at'], 'required'],
         [['session_id'], 'string', 'max' => 128],
         [['ip_address'], 'string', 'max' => 45],
         [['url', 'referrer'], 'string', 'max' => 1000],
      ];
   }
   
   /**
    * {@inheritdoc}
    */
   public function attributeLabels()
   {
      return [
         'id' => 'ID',
         'user_id' => 'User ID',
         'session_id' => 'Session ID',
         'ip_address' => 'Ip Address',
         'user_agent' => 'User Agent',
         'url' => 'Url',
         'referrer' => 'Referrer',
         'visited_at' => 'Visited At',
      ];
   }
   
}
