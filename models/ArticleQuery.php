<?php

namespace app\models;

use app\components\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Article]].
 *
 * @see Article
 */
class ArticleQuery extends ActiveQuery
{
    public static function articleTagTableName()
    {
        return 'article_tag';
    }

    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Article[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Article|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    /**
     * @param $articleId
     * @param array $tagsIds
     * @return int
     * @throws \yii\db\Exception
     */
    public function batchAddRelativeTags($articleId, array $tagsIds)
    {
        $values = [];
        foreach ($tagsIds as $tagId) {
            $values[] = '(' . (int) $articleId . ', ' . (int) $tagId . ')';
        }

        $sql = 'INSERT IGNORE INTO `' . self::articleTagTableName() . '` (article_id, tag_id) ' .
            'VALUES ' . implode(', ' , $values) . ';';

        return \Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * @return $this
     */
    public function active()
    {
        $this->andFilterWhere(['=', 'status', Article::STATUS_ACTIVE]);

        return $this;
    }
}