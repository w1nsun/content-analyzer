<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $title_en
 * @property string $title_ru
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
            [['title_en', 'title_ru', 'slug'], 'required'],
            [['status'], 'integer'],
            [['title_en', 'title_ru', 'slug'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => Yii::t('app', 'ID'),
            'title_en'  => Yii::t('app', 'Заголовок {locale}', ['locale' => 'EN']),
            'title_ru'  => Yii::t('app', 'Заголовок {locale}', ['locale' => 'RU']),
            'slug'      => Yii::t('app', 'Slug'),
            'status'    => Yii::t('app', 'Статус'),
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

    /**
     * @inheritdoc
     * @return CategoryQuery the active query used by this AR class.
     */
    public static function query()
    {
        return new CategoryQuery(get_called_class());
    }

    /**
     * @return string Category title by current locale
     */
    public function getTitle()
    {
        $locales = [
            'ru-RU' => 'ru',
            'en-GB' => 'en',
            'en-US' => 'en',
        ];

        $titleVar = 'title_' . $locales[\Yii::$app->language];

        return $this->{$titleVar};
    }
}
