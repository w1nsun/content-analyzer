<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%article}}".
 *
 * @property integer $id
 * @property integer $resource_id
 * @property string $title
 * @property string $description
 * @property string $url
 * @property string $type
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $status
 *
 * @property Resource $resource
 */
class Article extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

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
            [['resource_id', 'title', 'description', 'url', 'type', 'lang', 'country', 'updated_at', 'created_at'], 'required'],
            [['resource_id', 'updated_at', 'created_at', 'status'], 'integer'],
            [['title', 'description', 'url'], 'string'],
            [['type', 'lang', 'country'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'resource_id' => Yii::t('app', 'ID Ресурса'),
            'title' => Yii::t('app', 'Заголовок'),
            'description' => Yii::t('app', 'Описание'),
            'url' => Yii::t('app', 'Url'),
            'type' => Yii::t('app', 'Тип'),
            'updated_at' => Yii::t('app', 'Время редактирования'),
            'created_at' => Yii::t('app', 'Время создания'),
            'status' => Yii::t('app', 'Статус'),
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
}
