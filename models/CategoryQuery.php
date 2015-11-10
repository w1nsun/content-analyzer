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

    public function getAllAsEnum()
    {
        $categories = $this->select(['id', 'title'])->asArray()->all();
        $enum       = [];

        foreach ($categories as $category) {
            $enum[$category['id']] = $category['title'];
        }

        return $enum;
    }
}