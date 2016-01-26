<?php

namespace app\models\forms;

use app\models\User;
use yii\base\Model;
use Yii;

class SignupForm extends Model
{
    const SCENARIO_DEFAULT = 'default';
    const SCENARIO_SOCIAL = 'social';

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
            [['email', 'password', 'repeat_password'], 'required', 'on' => self::SCENARIO_DEFAULT],
            ['email', 'email', 'on' => self::SCENARIO_DEFAULT],
            ['email', 'email', 'on' => self::SCENARIO_SOCIAL],
            ['password', 'validatePassword', 'on' => self::SCENARIO_DEFAULT],
            ['password', 'compare', 'compareAttribute' => 'repeat_password', 'on' => self::SCENARIO_DEFAULT],
            ['email', 'isRegisteredUser'],
        ];
    }

    public function isRegisteredUser($attribute, $params)
    {
        $user = User::find()->where(['email' => $this->email])->one();
        if ($user) {
            $this->addError(
                $attribute,
                \Yii::t('app', 'Пользователь с таким email ({email}) уже зарегистрирован', ['email' => $this->email])
            );
            return false;
        }

        return true;
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

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'email'           => Yii::t('app', 'Email'),
            'password'        => Yii::t('app', 'Пароль'),
            'repeat_password' => Yii::t('app', 'Повторите пароль'),
        ];
    }
}