<?php

namespace app\commands;

use app\components\Roles;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        /** @var \yii\rbac\DbManager $auth */
        $auth = \Yii::$app->authManager;

        /** @var \yii\rbac\Role $userRole */
        $userRole = $auth->createRole(Roles::API_USER);
        $auth->add($userRole);

        /** @var \yii\rbac\Role $managerRole */
        $managerRole = $auth->createRole(Roles::MANAGER);
        $auth->add($managerRole);
        $auth->addChild($managerRole, $userRole);

        /** @var \yii\rbac\Role $adminRole */
        $adminRole = $auth->createRole(Roles::ADMIN);
        $auth->add($adminRole);
        $auth->addChild($adminRole, $managerRole);
        $auth->addChild($adminRole, $userRole);

        echo "Roles success initialized\n";
        return 0;
    }
}