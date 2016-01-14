<?php

use yii\db\Schema;
use yii\db\Migration;

class m160109_200242_update_category extends Migration
{
    public function up()
    {
         $this->execute(
             'ALTER TABLE `category`
                    ALTER `title` DROP DEFAULT;
                ALTER TABLE `category`
                    CHANGE COLUMN `title` `title_en` VARCHAR(255) NOT NULL AFTER `id`,
                    ADD COLUMN `title_ru` VARCHAR(255) NOT NULL AFTER `title_en`;
'
         );

    }

    public function down()
    {
        echo "m160109_200242_update_category cannot be reverted.\n";

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
