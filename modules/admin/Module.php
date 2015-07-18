<?php

namespace app\modules\admin;

class Module extends \yii\base\Module
{
    /**
     * @Overview
     * @var string
     */
    public $controllerNamespace = 'app\modules\admin\controllers';


    /**
     * @Overview
     * @var string
     */
    public $defaultRoute = 'dashboard';


    /**
     * Initialize
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
