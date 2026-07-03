<?php

use yii\db\Migration;

final class m260703_000002_add_telegram_email_registration extends Migration
{
   public function safeUp(): void
   {
      $userTable = $this->db->schema->getTableSchema('{{%user}}', true);
      if ($userTable === null) {
         throw new RuntimeException('The user table was not found.');
      }

      if (!isset($userTable->columns['telegram_user_id'])) {
         $this->addColumn(
            '{{%user}}',
            'telegram_user_id',
            $this->bigInteger()->null()->after('telegram_chat_id')
         );

         $this->createIndex(
            'ux-user-telegram_user_id',
            '{{%user}}',
            'telegram_user_id',
            true
         );
      }

      if ($this->db->schema->getTableSchema('{{%telegram_registration}}', true) === null) {
         $this->createTable('{{%telegram_registration}}', [
            'id' => $this->primaryKey(),
            'telegram_chat_id' => $this->string(32)->notNull(),
            'telegram_user_id' => $this->string(32)->null(),
            'telegram_username' => $this->string(255)->null(),
            'telegram_language' => $this->string(5)->null(),
            'user_id' => $this->integer()->null(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(1),
            'code_hash' => $this->string(255)->null(),
            'attempts' => $this->tinyInteger()->notNull()->defaultValue(0),
            'code_expires_at' => $this->integer()->null(),
            'last_code_sent_at' => $this->integer()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
         ]);

         $this->createIndex(
            'ux-telegram_registration-chat_id',
            '{{%telegram_registration}}',
            'telegram_chat_id',
            true
         );

         $this->createIndex(
            'ix-telegram_registration-user_id',
            '{{%telegram_registration}}',
            'user_id'
         );

         $this->createIndex(
            'ix-telegram_registration-expires_at',
            '{{%telegram_registration}}',
            'code_expires_at'
         );

         $this->addForeignKey(
            'fk-telegram_registration-user_id',
            '{{%telegram_registration}}',
            'user_id',
            '{{%user}}',
            'id',
            'SET NULL',
            'CASCADE'
         );
      }
   }

   public function safeDown(): void
   {
      if ($this->db->schema->getTableSchema('{{%telegram_registration}}', true) !== null) {
         $this->dropForeignKey(
            'fk-telegram_registration-user_id',
            '{{%telegram_registration}}'
         );
         $this->dropTable('{{%telegram_registration}}');
      }

      $userTable = $this->db->schema->getTableSchema('{{%user}}', true);
      if ($userTable !== null && isset($userTable->columns['telegram_user_id'])) {
         $this->dropIndex('ux-user-telegram_user_id', '{{%user}}');
         $this->dropColumn('{{%user}}', 'telegram_user_id');
      }
   }
}
