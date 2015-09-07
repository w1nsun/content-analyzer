<?php

namespace app\models;

use Yii;
use app\components\ActiveRecord;

/**
 * This is the model class for table "image".
 *
 * @property integer $id
 * @property string $owner
 * @property integer $owner_id
 * @property string $src
 * @property integer $width
 * @property integer $height
 * @property string $size
 * @property integer $parent_id
 * @property integer $status
 */
class Image extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //create
            [['src', 'width', 'height', 'size'], 'required', 'on' => self::SCENARIO_CREATE],
            [['owner', 'src', 'size'], 'string', 'max' => 255, 'on' => self::SCENARIO_CREATE],
            [['owner_id', 'width', 'height', 'parent_id', 'status'], 'integer', 'on' => self::SCENARIO_CREATE],

            //update
            [['src', 'width', 'height', 'size'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['owner', 'src', 'size'], 'string', 'max' => 255, 'on' => self::SCENARIO_UPDATE],
            [['owner_id', 'width', 'height', 'parent_id', 'status'], 'integer', 'on' => self::SCENARIO_UPDATE],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'owner' => Yii::t('app', 'Owner'),
            'owner_id' => Yii::t('app', 'Owner ID'),
            'src' => Yii::t('app', 'Src'),
            'width' => Yii::t('app', 'Ширины'),
            'height' => Yii::t('app', 'Высота'),
            'size' => Yii::t('app', 'Размер'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'status' => Yii::t('app', 'Статус'),
        ];
    }

    /**
     * @inheritdoc
     * @return ImageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ImageQuery(get_called_class());
    }



    /* todo: сохранения файла на диск и генерация размеров. Добавить размеры в конфиг. Сделать подгрузку размеров по паренту. Валидация.
    */
}
