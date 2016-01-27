<?php

namespace app\models\forms;

use app\models\User;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    const SCENARIO_DEFAULT = 'default';
    const SCENARIO_SOCIAL = 'social';

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;


    /**
     * @var string
     */
    public $social;

    /**
     * @var string
     */
    public $social_id;


    /**
     * @var bool
     */
    public $rememberMe = true;


    /**
     * @var bool
     */
    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required', 'on' => self::SCENARIO_DEFAULT],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean', 'on' => self::SCENARIO_DEFAULT],
            // password is validated by validatePassword()
            ['password', 'validatePassword', 'on' => self::SCENARIO_DEFAULT],

            [['social', 'social_id'], 'required', 'on' => self::SCENARIO_SOCIAL],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'Неправильный email или пароль'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function socialLogin()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getSocialUser(), 2592000);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getSocialUser()
    {
        if ($this->_user === false) {
            $this->_user = User::find()->where(['social_name' => $this->social, 'social_id' => $this->social_id])->one();
        }

        return $this->_user;
    }


    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByEmail($this->username);
        }

        return $this->_user;
    }
}
