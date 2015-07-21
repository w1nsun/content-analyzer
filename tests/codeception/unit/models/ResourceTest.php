<?php

namespace tests\codeception\unit\models;

use Yii;
use app\models\Resource;
use Codeception\Specify;
use yii\codeception\TestCase;

class ResourceTest extends TestCase
{
    use Specify;

    //-----title
    public function testEmptyTitleValidationCreateScenario()
    {
        $model = new Resource(['scenario' => Resource::SCENARIO_CREATE]);
        $model->title = '';

        $this->specify('title is required');
        $this->assertFalse($model->validate(['title']));
        $this->assertArrayHasKey('title', $model->errors);
    }

    public function testFilledTitleValidationCreateScenario()
    {
        $model = new Resource(['scenario' => Resource::SCENARIO_CREATE]);
        $model->title = 'test title';

        $this->specify('title is filled');
        $this->assertTrue($model->validate(['title']));
        $this->assertArrayNotHasKey('title', $model->errors);
    }

    public function testOverLimitTitleValidationCreateScenario()
    {
        $model = new Resource(['scenario' => Resource::SCENARIO_CREATE]);
        $model->title = self::_generateRandomString(256);

        $this->specify('title over 255 symbols');
        $this->assertFalse($model->validate(['title']));
        $this->assertArrayHasKey('title', $model->errors);
    }


    //------url
    public function testUrlCorrectValidationCreateScenario()
    {
        $model = new Resource(['scenario' => Resource::SCENARIO_CREATE]);
        $model->url = 'http://stackoverflow.com/questions/4356289/php-random-string-generator';

        $this->specify('correct url');
        $this->assertTrue($model->validate(['url']));
        $this->assertArrayNotHasKey('url', $model->errors);
    }

    public function testUrlWrongValidationCreateScenario()
    {
        $model = new Resource(['scenario' => Resource::SCENARIO_CREATE]);
        $model->url = self::_generateRandomString(50);

        $this->specify('incorrect url');
        $this->assertFalse($model->validate(['url']));
        $this->assertArrayHasKey('url', $model->errors);
    }


    //------status
    public function testStatusCorrectValidationCreateScenario()
    {
        $model = new Resource(['scenario' => Resource::SCENARIO_CREATE]);
        $model->status = Resource::STATUS_ACTIVE;

        $this->specify('correct status');
        $this->assertTrue($model->validate(['status']));
        $this->assertArrayNotHasKey('status', $model->errors);
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
