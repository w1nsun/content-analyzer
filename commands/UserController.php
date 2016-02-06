<?php

namespace app\commands;

use app\components\Roles;
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

    public function actionAddApiUser()
    {
        $password           = md5(microtime());
        $user               = new User();
        $user->email        = 'nodejs-app@user.local';
        $user->password     = $password;
        $user->access_token = Yii::$app->params['nodejs_app']['access_token'];

        if ($user->register()) {

            /** @var \yii\rbac\DbManager $auth */
            $auth  = Yii::$app->authManager;

            if (!$auth->checkAccess($user->id, Roles::API_USER)) {
                /** @var \yii\rbac\Role $role */
                $role = $auth->getRoles()[Roles::API_USER];
                $auth->assign($role, $user->id);
            }

            echo "Api user with password '{$password}' successful added\n";
            return 0;
        }

        return 1;
    }
}