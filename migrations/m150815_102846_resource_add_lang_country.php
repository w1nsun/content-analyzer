<?php

use yii\db\Schema;
use yii\db\Migration;

class m150815_102846_resource_add_lang_country extends Migration
{
    public function up()
    {
        $this->execute(
            'ALTER TABLE `resource`
                CHANGE COLUMN `type` `type` VARCHAR(255) NOT NULL AFTER `url`,
                    ADD COLUMN `lang` VARCHAR(255) NOT NULL AFTER `type`,
                    ADD COLUMN `country` VARCHAR(255) NOT NULL AFTER `lang`;'
        );
    }

    public function down()
    {
        echo "m150815_102846_resource_add_lang_country cannot be reverted.\n";

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
