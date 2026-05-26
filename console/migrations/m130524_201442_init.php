<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
   public function up()
   {
      $tableOptions = null;
      if ($this->db->driverName === 'mysql') {
         // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
         $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
      }
      
      $this->createTable('{{%user}}', [
         'id' => $this->primaryKey(),
         'username' => $this->string()->notNull()->unique(),
         'fullname' => $this->string()->notNull(),
         'email' => $this->string()->notNull()->unique(),
         'phone' => $this->string()->notNull(),
         'address' => $this->string(),
         'image' => $this->string(),
         'subscription_status' => $this->tinyInteger()->defaultValue(0),
         'status' => $this->smallInteger()->notNull()->defaultValue(10),
         'created_at' => $this->integer()->notNull(),
         'updated_at' => $this->integer()->notNull(),
         'auth_key' => $this->string(32)->notNull(),
         'password_hash' => $this->string()->notNull(),
         'password_reset_token' => $this->string()->unique(),
         'verification_token' => $this->string(),
      ], $tableOptions);
      $this->insert('{{%user}}', [
         'username' => 'admin',
         'fullname' => 'Admin',
         'email' => 'admin@email.com',
         'phone' => '+998995993603',
         'subscription_status' => 0,
         'status' => 10,
         'created_at' => time(),
         'updated_at' => time(),
         'auth_key' => Yii::$app->security->generateRandomString(),
         'password_hash' => Yii::$app->security->generatePasswordHash('342089'),
      ]);
      $this->insert('{{%user}}', [
         'username' => 'sunnat',
         'fullname' => 'Admin',
         'email' => 'admin@email.com',
         'phone' => '+998939977437',
         'subscription_status' => 0,
         'status' => 10,
         'created_at' => time(),
         'updated_at' => time(),
         'auth_key' => Yii::$app->security->generateRandomString(),
         'password_hash' => Yii::$app->security->generatePasswordHash('123456'),
      ]);
      $this->insert('{{%auth_item}}', [
         'name' => 'admin',
         'type' => 1,
         'description' => 'Administrator',
      ]);
      $this->insert('{{%auth_item}}', [
         'name' => 'guest',
         'type' => 1,
         'description' => 'Visitor',
      ]);
      $this->insert('{{%auth_item}}', [
         'name' => 'tech_support',
         'type' => 1,
         'description' => 'Technical Support / Contact',
      ]);
      $this->insert('{{%auth_assignment}}', [
         'item_name' => 'admin',
         'user_id' => 1,
         'created_at' => time(),
      ]);
      $this->insert('{{%auth_assignment}}', [
         'item_name' => 'admin',
         'user_id' => 2,
         'created_at' => time(),
      ]);
   }
   
   public function down()
   {
      $this->dropTable('{{%user}}');
   }
}
