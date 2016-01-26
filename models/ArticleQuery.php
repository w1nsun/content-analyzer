<?php

namespace app\models;

use app\components\ActiveQuery;
use yii\data\ActiveDataProvider;

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
    public function recentActive()
    {
        $this->andFilterWhere(['=', 'status', Article::STATUS_ACTIVE]);
        $this->andFilterWhere(['>=', 'created_at', (time()-(3600*24*5))]);

        return $this;
    }

    /**
     * @return ActiveDataProvider
     */
    public function trends()
    {
        $totalQuery = '`likes_facebook` + `likes_twitter` + `likes_pinterest` + `likes_linkedin` + `likes_google_plus` + `likes_vkontakte`';

        $dataProvider = new ActiveDataProvider([
            'query' => Article::find()->where(['status' => Article::STATUS_ACTIVE]),
            'sort'  => [
                'attributes' => [
                    'likes_facebook',
                    'likes_twitter',
                    'likes_pinterest',
                    'likes_linkedin',
                    'likes_google_plus',
                    'likes_vkontakte',
                    'totalLikes' => [
                        'asc'   => ['created_at' => SORT_ASC, $totalQuery => SORT_ASC],
                        'desc'  => ['created_at' => SORT_DESC, $totalQuery => SORT_DESC],
                    ],
                ],
                'defaultOrder' => [
                    'totalLikes' => SORT_DESC
                ]
            ]
        ]);

        return $dataProvider;
    }
}