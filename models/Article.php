<?php

namespace app\models;

use Elasticsearch\Client;
use Yii;
use app\components\ActiveRecord;

/**
 * This is the model class for table "{{%article}}".
 *
 * @property integer $id
 * @property integer $resource_id
 * @property string $title
 * @property string $description
 * @property string $url
 * @property string $type
 * @property string $category_id
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $status
 * @property integer $likes_facebook
 * @property integer $likes_twitter
 * @property integer $likes_pinterest
 * @property integer $likes_linkedin
 * @property integer $likes_google_plus
 * @property integer $likes_vkontakte
 *
 * @property Resource $resource
 */
class Article extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLE = 0;

    const TYPE_ARTICLE = 'article';
    const TYPE_VIDEO = 'video';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            //create
            [['resource_id', 'category_id'], 'filter', 'filter' => 'intval', 'on' => self::SCENARIO_CREATE],
            [['title'], 'string', 'length' => ['max' => 1024], 'on' => self::SCENARIO_CREATE],
            [['description'], 'filter', 'filter' => 'strip_tags', 'on' => self::SCENARIO_CREATE],
            [['title', 'description', 'url'], 'trim', 'on' => self::SCENARIO_CREATE],

            //update
            [['resource_id', 'category_id'], 'filter', 'filter' => 'intval', 'on' => self::SCENARIO_UPDATE],
            [['title'], 'string', 'length' => ['max' => 1024], 'on' => self::SCENARIO_UPDATE],
            [['description'], 'filter', 'filter' => 'strip_tags', 'on' => self::SCENARIO_UPDATE],
            [['title', 'description', 'url'], 'trim', 'on' => self::SCENARIO_UPDATE],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => Yii::t('app', 'ID'),
            'resource_id'       => Yii::t('app', 'ID Ресурса'),
            'title'             => Yii::t('app', 'Заголовок'),
            'description'       => Yii::t('app', 'Описание'),
            'url'               => Yii::t('app', 'Url'),
            'type'              => Yii::t('app', 'Тип'),
            'updated_at'        => Yii::t('app', 'Время редактирования'),
            'created_at'        => Yii::t('app', 'Время создания'),
            'status'            => Yii::t('app', 'Статус'),
            'likes_facebook'    => Yii::t('app', 'Facebook'),
            'likes_twitter'     => Yii::t('app', 'Twitter'),
            'likes_pinterest'   => Yii::t('app', 'Pinterest'),
            'likes_linkedin'    => Yii::t('app', 'LinkedIn'),
            'likes_google_plus' => Yii::t('app', 'Google Plus'),
            'likes_vkontakte'   => Yii::t('app', 'Vkontakte'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResource()
    {
        return $this->hasOne(Resource::className(), ['id' => 'resource_id']);
    }

    /**
     * @inheritdoc
     * @return ArticleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ArticleQuery(get_called_class());
    }

    /**
     * @Override
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if($this->isNewRecord){
            $this->created_at = time();
        }

        $this->updated_at = time();

        return parent::beforeSave($insert);
    }

    /**
     * @param null $status
     * @return array
     */
    public static function enumStatus($status=null)
    {
        $enum = [
            self::STATUS_ACTIVE => Yii::t('app', 'Активен'),
            self::STATUS_DISABLE => Yii::t('app', 'Отключен'),
        ];

        return $status === null ? $enum : $enum[$status];
    }


    /**
     * @param null $type
     * @return array
     */
    public static function enumType($type=null)
    {
        $enum = [
            self::TYPE_ARTICLE => Yii::t('app', 'Статья'),
            self::TYPE_VIDEO => Yii::t('app', 'Видео'),
        ];

        return $type === null ? $enum : $enum[$type];
    }

    public function getTotalLikes()
    {
        return $this->likes_facebook +
                $this->likes_twitter +
                $this->likes_pinterest +
                $this->likes_linkedin +
                $this->likes_google_plus +
                $this->likes_vkontakte;
    }

    public function getTags()
    {
        return Tag::find()->findByArticle($this->id);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

//        /** @var Client $elasticsearch */
//        $elasticsearch = \Yii::$container->get('elasticsearch');
//        $elasticsearch->
    }


}
