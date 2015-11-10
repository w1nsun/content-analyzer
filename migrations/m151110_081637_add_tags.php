<?php

use yii\db\Schema;
use yii\db\Migration;

class m151110_081637_add_tags extends Migration
{
    public function up()
    {
        $this->execute('CREATE TABLE `tag` (
            `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `tag` VARCHAR(255) NOT NULL,
            `category_id` INT(10) UNSIGNED NOT NULL DEFAULT \'0\',
            `status` TINYINT(3) UNSIGNED NOT NULL DEFAULT \'0\',
            PRIMARY KEY (`id`, `tag`)
        )
        COLLATE=\'utf8_general_ci\'
        ENGINE=InnoDB
        AUTO_INCREMENT=80;');

        $this->execute('CREATE TABLE `article_tag` (
            `article_id` INT UNSIGNED NOT NULL,
            `tag_id` INT UNSIGNED NOT NULL,
            PRIMARY KEY (`article_id`, `tag_id`)
        )
        COLLATE=\'utf8_general_ci\'
        ENGINE=InnoDB;');
    }

    public function down()
    {
        echo "m151110_081637_add_tags cannot be reverted.\n";

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
