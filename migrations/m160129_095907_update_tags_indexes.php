<?php

use yii\db\Schema;
use yii\db\Migration;

class m160129_095907_update_tags_indexes extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE `tag`
                DROP PRIMARY KEY,
                ADD PRIMARY KEY (`id`),
                ADD UNIQUE INDEX `tag` (`tag`);
            ');

    }

    public function down()
    {
        echo "m160129_095907_update_tags_indexes cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
