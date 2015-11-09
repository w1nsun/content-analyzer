<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property integer $status
 */
class Category extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE  = 1;
    const STATUS_DISABLE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'slug'], 'required'],
            [['status'], 'integer'],
            [['title', 'slug'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'     => Yii::t('app', 'ID'),
            'title'  => Yii::t('app', 'Title'),
            'slug'   => Yii::t('app', 'Slug'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @param null $status
     * @return array
     */
    public static function enumStatus($status=null)
    {
        $enum = [
            self::STATUS_ACTIVE => Yii::t('app', 'Активна'),
            self::STATUS_DISABLE => Yii::t('app', 'Удалена'),
        ];

        return $status === null ? $enum : $enum[$status];
    }

    /**
     * @inheritdoc
     * @return CategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }
}
