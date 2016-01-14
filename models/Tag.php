<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tag".
 *
 * @property integer $id
 * @property string $tag
 * @property integer $category_id
 * @property integer $status
 */
class Tag extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE  = 1;
    const STATUS_DISABLE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag'], 'required'],
            [['category_id', 'status'], 'integer'],
            [['tag'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('app', 'ID'),
            'tag'         => Yii::t('app', 'Тэг'),
            'category_id' => Yii::t('app', 'Категория'),
            'status'      => Yii::t('app', 'Статус'),
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
            self::STATUS_DISABLE => Yii::t('app', 'Удален'),
        ];

        return $status === null ? $enum : $enum[$status];
    }

    /**
     * @inheritdoc
     * @return TagQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TagQuery(get_called_class());
    }
}
