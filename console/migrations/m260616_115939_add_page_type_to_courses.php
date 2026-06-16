<?php

use yii\db\Migration;

class m260616_115939_add_page_type_to_courses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->addColumn('courses','page_type',$this->integer());
         $courses = \common\models\Courses::find()->all();
         foreach ($courses as $courses){
            $courses->page_type = 1;
            $courses->save();
         }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('courses','page_type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260616_115939_add_page_type_to_courses cannot be reverted.\n";

        return false;
    }
    */
}
