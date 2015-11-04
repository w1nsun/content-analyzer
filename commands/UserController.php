<?php

namespace app\commands;

use app\models\User;
use yii\console\Controller;
use Yii;

class UserController extends Controller
{
    public function actionAddRole($email, $roleName)
    {
        /** @var \yii\rbac\DbManager $auth */
        $auth  = Yii::$app->authManager;
        /** @var \yii\rbac\Role[] $roles */
        $roles = $auth->getRoles();

        if (!isset($roles[$roleName])) {
            echo "Role: '{$roleName}'' not found!\n";
            return 0;
        }

        $user = User::findByEmail($email);
        if (!$user) {
            echo "User with email: '{$email}' not found!\n";
            return 1;
        }

        if ($auth->checkAccess($user->id, $roleName)) {
            echo "User '{$user->email}' has role '{$roleName}'\n";
            return 1;
        }

        $auth->assign($roles[$roleName], $user->id);
        echo "Role '{$roleName}' successful added to user '{$user->email}'\n";
        return 0;
    }
}