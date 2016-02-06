<?php

use yii\db\Schema;
use yii\db\Migration;

class m160126_093930_articles_add_likes_field extends Migration
{
    public function up()
    {

        $this->execute(
            'ALTER TABLE `article`
                ADD COLUMN `likes_facebook` INT UNSIGNED NOT NULL DEFAULT \'0\' AFTER `status`,
                ADD COLUMN `likes_twitter` INT UNSIGNED NOT NULL DEFAULT \'0\' AFTER `likes_facebook`,
                ADD COLUMN `likes_pinterest` INT UNSIGNED NOT NULL DEFAULT \'0\' AFTER `likes_twitter`,
                ADD COLUMN `likes_linkedin` INT UNSIGNED NOT NULL DEFAULT \'0\' AFTER `likes_pinterest`,
                ADD COLUMN `likes_google_plus` INT UNSIGNED NOT NULL DEFAULT \'0\' AFTER `likes_linkedin`,
                ADD COLUMN `likes_vkontakte` INT UNSIGNED NOT NULL DEFAULT \'0\' AFTER `likes_google_plus`;'
        );
    }

    public function down()
    {
        echo "m160126_093930_articles_add_likes_field cannot be reverted.\n";

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
