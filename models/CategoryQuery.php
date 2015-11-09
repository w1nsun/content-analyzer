<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Category]].
 *
 * @see Category
 */
class CategoryQuery extends \yii\db\ActiveQuery
{
    protected $tagsTableName = 'rss_tag_category';

    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Category[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Category|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param array $rssTags
     * @return int
     * @throws \yii\db\Exception
     */
    public function addTags(array $rssTags)
    {
        $values = [];
        foreach ($rssTags as $tag) {
            $values[] = '(\'' . addslashes($tag) . '\' , 0)';
        }

        $sql = 'INSERT IGNORE INTO `' . $this->tagsTableName . '` (tag, category_id)' .
               'VALUES ' . implode(', ' , $values) . ';';

        return \Yii::$app->db->createCommand($sql)->execute();
    }
}