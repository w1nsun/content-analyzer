<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "likes_log".
 *
 * @property integer $id
 * @property integer $article_id
 * @property integer $created_at
 * @property integer $facebook
 * @property integer $twitter
 * @property integer $pinterest
 * @property integer $linkedin
 * @property integer $google_plus
 * @property integer $vkontakte
 */
class LikesLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'likes_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'created_at'], 'required'],
            [['article_id', 'created_at', 'facebook', 'twitter', 'pinterest', 'linkedin', 'google_plus', 'vkontakte'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'article_id' => Yii::t('app', 'Article ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'facebook' => Yii::t('app', 'Facebook'),
            'twitter' => Yii::t('app', 'Twitter'),
            'pinterest' => Yii::t('app', 'Pinterest'),
            'linkedin' => Yii::t('app', 'Linkedin'),
            'google_plus' => Yii::t('app', 'Google Plus'),
            'vkontakte' => Yii::t('app', 'Vkontakte'),
        ];
    }

    /**
     * @inheritdoc
     * @return LikesLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LikesLogQuery(get_called_class());
    }

    public function getTotal()
    {
        return $this->facebook + $this->twitter + $this->pinterest + $this->linkedin + $this->google_plus + $this->vkontakte;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'article_id']);
    }
}
