<?php

namespace tests\codeception\unit\models;

use Yii;
use yii\codeception\TestCase;
use app\models\forms\LoginForm;
use Codeception\Specify;

class LoginFormTest extends TestCase
{
    use Specify;

    protected function tearDown()
    {
        Yii::$app->user->logout();
        parent::tearDown();
    }

    public function testLoginNoUser()
    {
        $model = new LoginForm([
            'username' => 'not_existing_username',
            'password' => 'not_existing_password',
        ]);

        $this->specify('user should not be able to login, when there is no identity', function () use ($model) {
            expect('model should not login user', $model->login())->false();
            expect('user should not be logged in', Yii::$app->user->isGuest)->true();
        });
    }

    public function testLoginWrongPassword()
    {
        $model = new LoginForm([
            'username' => 'demo',
            'password' => 'wrong_password',
        ]);

        $this->specify('user should not be able to login with wrong password');
        $this->assertFalse($model->login());
        $this->assertArrayHasKey('password', $model->errors);
        $this->assertTrue(Yii::$app->user->isGuest);
    }

    public function testLoginCorrect()
    {
        $model = new LoginForm([
            'username' => 'demo',
            'password' => 'demo',
        ]);

        $this->specify('user should be able to login with correct credentials');

        $this->assertTrue($model->login());
        $this->assertArrayNotHasKey('password', $model->errors);
        $this->assertFalse(Yii::$app->user->isGuest);
    }

}
