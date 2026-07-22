<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_login_session}}`.
 */
class m260722_112126_create_user_login_session_table extends Migration
{
   public function safeUp(): void
   {
      $this->createTable('{{%user_login_session}}', [
         'id' => $this->primaryKey(),
         
         'user_id' => $this->integer()->notNull(),
         
         // В БД хранится только хеш токена.
         'token_hash' => $this->char(64)->notNull(),
         
         'ip_address' => $this->string(45)->null(),
         'user_agent' => $this->text()->null(),
         
         // Можно заполнить после разбора User-Agent.
         'device_type' => $this->string(30)->null(),
         'device_name' => $this->string(150)->null(),
         'browser' => $this->string(100)->null(),
         'operating_system' => $this->string(100)->null(),
         
         // Определяется по IP, поэтому точность условная.
         'country' => $this->string(100)->null(),
         'city' => $this->string(100)->null(),
         
         'logged_in_at' => $this->integer()->notNull(),
         'last_seen_at' => $this->integer()->notNull(),
         'expires_at' => $this->integer()->notNull(),
         
         'logged_out_at' => $this->integer()->null(),
         'revoked_at' => $this->integer()->null(),
      ]);
      
      $this->createIndex(
         'ux-user_login_session-token_hash',
         '{{%user_login_session}}',
         'token_hash',
         true
      );
      
      $this->createIndex(
         'idx-user_login_session-user_last_seen',
         '{{%user_login_session}}',
         ['user_id', 'last_seen_at']
      );
      
      $this->createIndex(
         'idx-user_login_session-user_active',
         '{{%user_login_session}}',
         [
            'user_id',
            'logged_out_at',
            'revoked_at',
            'expires_at',
         ]
      );
   }
   
   public function safeDown(): void
   {
      $this->dropTable('{{%user_login_session}}');
   }
}
