<?php

 return [
     'cookieValidationKey' => 'cookie_validation_key',
     'db' => [
         'class'    => 'yii\db\Connection',
         'dsn'      => 'mysql:host=localhost;dbname=db_name',
         'username' => 'username',
         'password' => 'password',
         'charset'  => 'utf8',
     ],
     'params' => [
         'nodejs_app' => [
             'access_token' => 'example_access_token',
         ]
     ],
 ];