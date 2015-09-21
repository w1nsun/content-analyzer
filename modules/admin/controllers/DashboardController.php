<?php

namespace app\modules\admin\controllers;

use app\components\Images\ImageDownloader;
use app\components\Images\ImageValidator;

class DashboardController extends BaseController
{



    /**
     * Action
     * @return string
     */
    public function actionIndex()
    {
//        $imageValidator = new ImageValidator();
//        $imageDownloader = new ImageDownloader($imageValidator, \Yii::getAlias('@app/runtime/tmp'));
//        $res = $imageDownloader
//            ->from('https://habrastorage.org/files/846/c52/089/846c52089fd14c5da7db65f2daeea5c2.png')
//            ->to(\Yii::getAlias('@app/web/files/1.png'))
//            ->save();
//
//
//        vd($res);

        return $this->render('index');
    }
}
