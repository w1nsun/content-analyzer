<?php

namespace tests\codeception\unit\models;

use Yii;
use app\models\Resource;
use Codeception\Specify;
use yii\codeception\TestCase;

class ResourceTest extends TestCase
{
    use Specify;

    public $model;

    public function setUp()
    {
        parent::setUp();

        $this->model = new Resource;
    }

    public function testValidationCreateScenario()
    {
        $this->model->setScenario(Resource::SCENARIO_CREATE);

        $this->specify('title is required', function(){
            $this->model->title = '';
            $this->assertFalse($this->model->validate(['title']));
            $this->assertArrayHasKey('title', $this->model->errors);
        });

        $this->specify('title is filled', function(){
            $this->model->title = 'test title';
            $this->assertTrue($this->model->validate(['title']));
            $this->assertArrayNotHasKey('title', $this->model->errors);
        });

        $this->specify('title over 255 symbols', function(){
            $this->model->title = self::_generateRandomString(256);
            $this->assertFalse($this->model->validate(['title']));
            $this->assertArrayHasKey('title', $this->model->errors);
        });

        $this->specify('url is valid', function(){
            $this->model->url = 'http://stackoverflow.com/questions/4356289/php-random-string-generator';
            $this->assertTrue($this->model->validate(['url']));
            $this->assertArrayNotHasKey('url', $this->model->errors);
        });

        $this->specify('url is not valid', function(){
            $this->model->url = 'random string';
            $this->assertFalse($this->model->validate(['url']));
            $this->assertArrayHasKey('url', $this->model->errors);
        });

        $this->specify('status is valid', function(){
            $this->model->status = Resource::STATUS_ACTIVE;
            $this->assertTrue($this->model->validate(['status']));
            $this->assertArrayNotHasKey('status', $this->model->errors);
        });

        $this->specify('status is not valid', function(){
            $this->model->status = 'status';
            $this->assertTrue($this->model->validate(['status']));
            $this->assertEquals(0, $this->model->status);
        });

        $this->specify('status is default', function(){
            $this->model->status = null;
            $this->assertTrue($this->model->validate(['status']));
            $this->assertEquals(Resource::STATUS_ACTIVE, $this->model->status);
        });

        $this->specify('status after filter', function(){
            $this->model->status = (string)Resource::STATUS_ACTIVE;
            $this->assertTrue($this->model->validate(['status']));
            $this->assertTrue(is_int($this->model->status));
        });

        $this->specify('type is valid', function(){
            $this->model->type = Resource::TYPE_RSS;
            $this->assertTrue($this->model->validate(['type']));
            $this->assertArrayNotHasKey('type', $this->model->errors);
        });

        $this->specify('type is not valid', function(){
            $this->model->type = 'type';
            $this->assertFalse($this->model->validate(['type']));
            $this->assertArrayHasKey('type', $this->model->errors);
        });

        $this->specify('type is default', function(){
            $this->model->type = null;
            $this->assertTrue($this->model->validate(['type']));
            $this->assertEquals(Resource::TYPE_RSS, $this->model->type);
        });

        $this->specify('type after filter', function(){
            $this->model->type = (string)Resource::TYPE_RSS;
            $this->assertTrue($this->model->validate(['type']));
            $this->assertTrue(is_int($this->model->type));
        });

    }

    public function testValidationUpdateScenario()
    {
        $this->model->setScenario(Resource::SCENARIO_UPDATE);

        $this->specify('title is required', function(){
            $this->model->title = '';
            $this->assertFalse($this->model->validate(['title']));
            $this->assertArrayHasKey('title', $this->model->errors);
        });

        $this->specify('title is filled', function(){
            $this->model->title = 'test title';
            $this->assertTrue($this->model->validate(['title']));
            $this->assertArrayNotHasKey('title', $this->model->errors);
        });

        $this->specify('title over 255 symbols', function(){
            $this->model->title = self::_generateRandomString(256);
            $this->assertFalse($this->model->validate(['title']));
            $this->assertArrayHasKey('title', $this->model->errors);
        });

        $this->specify('url is valid', function(){
            $this->model->url = 'http://stackoverflow.com/questions/4356289/php-random-string-generator';
            $this->assertTrue($this->model->validate(['url']));
            $this->assertArrayNotHasKey('url', $this->model->errors);
        });

        $this->specify('url is not valid', function(){
            $this->model->url = 'random string';
            $this->assertFalse($this->model->validate(['url']));
            $this->assertArrayHasKey('url', $this->model->errors);
        });

        $this->specify('status is valid', function(){
            $this->model->status = Resource::STATUS_ACTIVE;
            $this->assertTrue($this->model->validate(['status']));
            $this->assertArrayNotHasKey('status', $this->model->errors);
        });

        $this->specify('status is not valid', function(){
            $this->model->status = 'status';
            $this->assertTrue($this->model->validate(['status']));
            $this->assertEquals(0, $this->model->status);
        });

        $this->specify('status is default', function(){
            $this->model->status = null;
            $this->assertTrue($this->model->validate(['status']));
            $this->assertEquals(Resource::STATUS_ACTIVE, $this->model->status);
        });

        $this->specify('status after filter', function(){
            $this->model->status = (string)Resource::STATUS_ACTIVE;
            $this->assertTrue($this->model->validate(['status']));
            $this->assertTrue(is_int($this->model->status));
        });

        $this->specify('type is valid', function(){
            $this->model->type = Resource::TYPE_RSS;
            $this->assertTrue($this->model->validate(['type']));
            $this->assertArrayNotHasKey('type', $this->model->errors);
        });

        $this->specify('type is not valid', function(){
            $this->model->type = 'type';
            $this->assertFalse($this->model->validate(['type']));
            $this->assertArrayHasKey('type', $this->model->errors);
        });

        $this->specify('type is default', function(){
            $this->model->type = null;
            $this->assertTrue($this->model->validate(['type']));
            $this->assertEquals(Resource::TYPE_RSS, $this->model->type);
        });

        $this->specify('type after filter', function(){
            $this->model->type = (string)Resource::TYPE_RSS;
            $this->assertTrue($this->model->validate(['type']));
            $this->assertTrue(is_int($this->model->type));
        });
    }


    /*
     * Other functions
     */

    private static function _generateRandomString($length)
    {
        $characters = '01 234 567 89a bcd efgh ijkl mn opq rst uvwx yzA BCD EFG HIJKL MN OP QRST UVW XYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
