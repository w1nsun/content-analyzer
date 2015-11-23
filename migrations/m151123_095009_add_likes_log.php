<?php

use yii\db\Schema;
use yii\db\Migration;

class m151123_095009_add_likes_log extends Migration
{
    public function up()
    {
        $this->execute('CREATE TABLE analyzer.likes_log
            (
                id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
                article_id INT UNSIGNED NOT NULL,
                created_at INT UNSIGNED NOT NULL,
                facebook INT UNSIGNED DEFAULT 0 NOT NULL,
                twitter INT UNSIGNED DEFAULT 0 NOT NULL,
                pinterest INT UNSIGNED DEFAULT 0 NOT NULL,
                linkedin INT UNSIGNED DEFAULT 0 NOT NULL,
                google_plus INT DEFAULT 0 NOT NULL,
                vkontakte INT UNSIGNED DEFAULT 0 NOT NULL
            )
            COLLATE=\'utf8_general_ci\'
            ENGINE=InnoDB;');
    }

    public function down()
    {
        echo "m151123_095009_add_likes_log cannot be reverted.\n";

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
