<?php

namespace app\models;

use app\components\ActiveRecord;
use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property string $auth_key
 * @property string $access_token
 * @property integer $status
 * @property string $social_name
 * @property string $social_id
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE  = 1;
    const STATUS_DISABLE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password', 'auth_key', 'access_token'], 'required'],
            [['status'], 'integer'],
            [['email', 'password', 'auth_key'], 'string', 'max' => 255],
            [['access_token'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => Yii::t('app', 'ID'),
            'email'        => Yii::t('app', 'Email'),
            'password'     => Yii::t('app', 'Пароль'),
            'auth_key'     => Yii::t('app', 'Auth Key'),
            'access_token' => Yii::t('app', 'Access Token'),
            'status'       => Yii::t('app', 'Статус'),
        ];
    }

    /**
     * @return UserQuery
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @param int|string $id
     * @return null|static
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return null|static
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['access_token' => $token]);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    /**
     * @param $email
     * @return null|self
     */
    public static function findByEmail($email)
    {
        return self::findOne(['email' => $email]);
    }

    /**
     * @param $password
     * @return string
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public static function getPasswordHash($password)
    {
        return Yii::$app->getSecurity()->generatePasswordHash($password, 15);
    }

    /**
     * Sign up user
     * @return bool
     */
    public function register()
    {
        $this->password = self::getPasswordHash($this->password);
        $this->auth_key = Yii::$app->getSecurity()->generateRandomString();
        $this->status   = self::STATUS_ACTIVE;
        $this->access_token = '';

        return $this->save(false);
    }


    /**
     * @param null $id
     * @return array
     */
    public static function enumStatus($id = null)
    {
        $enum = [
            self::STATUS_ACTIVE => Yii::t('app', 'Активен'),
            self::STATUS_DISABLE => Yii::t('app', 'Заблокирован'),
        ];

        return $id === null ? $enum : $enum[$id];
    }
}
