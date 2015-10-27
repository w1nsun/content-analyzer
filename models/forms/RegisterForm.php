<?php

namespace app\models\forms;

use yii\base\Model;

class RegisterForm extends Model
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $repeat_password;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['email', 'password', 'repeat_password'], 'required'],
            ['email', 'email'],
            ['password', 'validatePassword'],
            ['password', 'compare', 'compareAttribute' => 'repeat_password'],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validatePassword($attribute, $params)
    {
        $allowedChars = '|@#$^&="!"№\';%:?*()_+-/,.1234567890ZYXWVUTSRQPONMLKJIHGFEDCBAzyxwvutsrqponmlkjihgfedcba';
        if ($badChars = str_replace(str_split($allowedChars), '', $this->$attribute)) {
            $badChars = implode('', array_unique(preg_split('//u', $badChars, -1, PREG_SPLIT_NO_EMPTY)));

            $this->addError($attribute, \Yii::t('app', 'Не разрешены символы {chars}', ['chars' => $badChars]));
        }

        if (
            !preg_match('/^([a-zA-z]|[0-9]|[,\.\?\|\!\@#\$%\^&*()_\-\+="№;:\'\/])+$/', $this->$attribute) ||
            strpos($this->$attribute, '..') !== false
        ) {
            $this->addError($attribute, \Yii::t('app', 'Значение пароля недопустимо'));
        }
    }
}