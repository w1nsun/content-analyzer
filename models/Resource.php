<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resource".
 *
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property integer $type
 * @property integer $last_run_time
 * @property integer $status
 */
class Resource extends \yii\db\ActiveRecord
{

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const STATUS_ACTIVE = 1;
    const STATUS_DISABLE = 0;

    const TYPE_RSS = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resource';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            //create
            [['title', 'url'], 'required', 'on'=>self::SCENARIO_CREATE],
            [['url'], 'url', 'on'=>self::SCENARIO_CREATE],
            [['last_run_time'], 'integer', 'on'=>self::SCENARIO_CREATE],
            [['title'], 'string', 'max' => 255, 'on'=>self::SCENARIO_CREATE],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE, 'on'=>self::SCENARIO_CREATE],
            [['last_run_time'], 'default', 'value' => 0, 'on'=>self::SCENARIO_CREATE],
            [['type'], 'default', 'value' => self::TYPE_RSS, 'on'=>self::SCENARIO_CREATE],
            [['type'], 'in', 'range' => array_keys(self::enumType()), 'on'=>self::SCENARIO_CREATE],
            [['status'], 'in', 'range' => array_keys(self::enumStatus()), 'on'=>self::SCENARIO_CREATE],

            //update
            [['title', 'url'], 'required', 'on'=>self::SCENARIO_UPDATE],
            [['url'], 'url', 'on'=>self::SCENARIO_UPDATE],
            [['last_run_time'], 'integer', 'on'=>self::SCENARIO_UPDATE],
            [['title'], 'string', 'max' => 255, 'on'=>self::SCENARIO_UPDATE],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE, 'on'=>self::SCENARIO_UPDATE],
            [['last_run_time'], 'default', 'value' => 0, 'on'=>self::SCENARIO_UPDATE],
            [['type'], 'default', 'value' => self::TYPE_RSS, 'on'=>self::SCENARIO_UPDATE],
            [['type'], 'default', 'value' => self::TYPE_RSS, 'on'=>self::SCENARIO_UPDATE],
            [['type'], 'in', 'range' => array_keys(self::enumType()), 'on'=>self::SCENARIO_UPDATE],
            [['status'], 'in', 'range' => array_keys(self::enumStatus()), 'on'=>self::SCENARIO_UPDATE],
        ];
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
            self::TYPE_RSS => Yii::t('app', 'RSS'),
        ];

        return $type === null ? $enum : $enum[$type];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Заголовок'),
            'url' => Yii::t('app', 'Url'),
            'type' => Yii::t('app', 'Тип'),
            'last_run_time' => Yii::t('app', 'Время последнего парсинга'),
            'status' => Yii::t('app', 'Статус'),
        ];
    }

    /**
     * @inheritdoc
     * @return ResourceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ResourceQuery(get_called_class());
    }
}
