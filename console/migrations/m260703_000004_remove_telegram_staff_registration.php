<?php

use yii\db\Migration;

/**
 * Нужна только тем, кто уже применил первую версию staff-патча.
 * Удаляет ставшую ненужной таблицу email/code-регистрации.
 */
final class m260703_000004_remove_telegram_staff_registration extends Migration
{
   public function safeUp(): void
   {
      if (
         $this->db->schema->getTableSchema(
            '{{%telegram_staff_registration}}',
            true
         ) === null
      ) {
         return;
      }

      $foreignKeys = $this->db->schema
         ->getTableSchema('{{%telegram_staff_registration}}', true)
         ->foreignKeys;

      if (isset($foreignKeys['fk-telegram_staff_registration-user_id'])) {
         $this->dropForeignKey(
            'fk-telegram_staff_registration-user_id',
            '{{%telegram_staff_registration}}'
         );
      }

      $this->dropTable('{{%telegram_staff_registration}}');
   }

   public function safeDown(): void
   {
      // Таблица больше не используется и намеренно не восстанавливается.
   }
}
