<?php

use yii\db\Migration;

class m260817_000001_add_descriptions_to_course_category extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%course_category}}', 'desc_ru', $this->text()->null()->after('name_uz'));
        $this->addColumn('{{%course_category}}', 'desc_en', $this->text()->null()->after('desc_ru'));
        $this->addColumn('{{%course_category}}', 'desc_uz', $this->text()->null()->after('desc_en'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%course_category}}', 'desc_uz');
        $this->dropColumn('{{%course_category}}', 'desc_en');
        $this->dropColumn('{{%course_category}}', 'desc_ru');
    }
}
