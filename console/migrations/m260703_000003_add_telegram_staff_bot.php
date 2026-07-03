<?php

use yii\db\Migration;

final class m260703_000003_add_telegram_staff_bot extends Migration
{
   public function safeUp(): void
   {
      $userTable = $this->db->schema->getTableSchema('{{%user}}', true);
      if ($userTable === null) {
         throw new RuntimeException('The user table was not found.');
      }

      if (!isset($userTable->columns['telegram_staff_chat_id'])) {
         $this->addColumn(
            '{{%user}}',
            'telegram_staff_chat_id',
            $this->bigInteger()->null()
         );
         $this->createIndex(
            'ux-user-telegram_staff_chat_id',
            '{{%user}}',
            'telegram_staff_chat_id',
            true
         );
      }

      if (!isset($userTable->columns['telegram_staff_user_id'])) {
         $this->addColumn(
            '{{%user}}',
            'telegram_staff_user_id',
            $this->bigInteger()->null()
         );
         $this->createIndex(
            'ux-user-telegram_staff_user_id',
            '{{%user}}',
            'telegram_staff_user_id',
            true
         );
      }

      if (!isset($userTable->columns['telegram_staff_username'])) {
         $this->addColumn(
            '{{%user}}',
            'telegram_staff_username',
            $this->string(255)->null()
         );
      }

      if (!isset($userTable->columns['telegram_staff_connected_at'])) {
         $this->addColumn(
            '{{%user}}',
            'telegram_staff_connected_at',
            $this->integer()->null()
         );
      }
   }

   public function safeDown(): void
   {
      $userTable = $this->db->schema->getTableSchema('{{%user}}', true);
      if ($userTable === null) {
         return;
      }

      if (isset($userTable->columns['telegram_staff_connected_at'])) {
         $this->dropColumn('{{%user}}', 'telegram_staff_connected_at');
      }
      if (isset($userTable->columns['telegram_staff_username'])) {
         $this->dropColumn('{{%user}}', 'telegram_staff_username');
      }
      if (isset($userTable->columns['telegram_staff_user_id'])) {
         $this->dropIndex('ux-user-telegram_staff_user_id', '{{%user}}');
         $this->dropColumn('{{%user}}', 'telegram_staff_user_id');
      }
      if (isset($userTable->columns['telegram_staff_chat_id'])) {
         $this->dropIndex('ux-user-telegram_staff_chat_id', '{{%user}}');
         $this->dropColumn('{{%user}}', 'telegram_staff_chat_id');
      }
   }
}
