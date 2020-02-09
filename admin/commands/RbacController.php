<?

namespace admin\commands;

use Yii;

class RbacController extends \admin\console\Controller {

    public $user_id;

    public function options($actionID) {
        if ($actionID == 'init') {
            return [
                'user_id', 'interactive'
            ];
        }
    }

    public function actionInit() {


        $this->stdout("Start rbac initialization...\n");
       
        $auth = Yii::$app->authManager;

        //Удалить таблицу assigment, если необходимо
        $auth->removeAll();

        //Rules
        $userGroupRule = new \admin\rbac\UserGroupRule;
        $auth->add($userGroupRule);

        $shopcartOrderRule = new \admin\rbac\UserShopcartOrderRule;
        $auth->add($shopcartOrderRule);
        //End rules
        //Permissions
        //Добавление стандартных разрешений
        $auth->add($auth->createPermission('admin'));
        $auth->add($auth->createPermission('admin/api'));
        $auth->add($auth->createPermission('admin/photos'));
        $auth->add($auth->createPermission('admin/yml/excel/update-items-from-excel-file'));
        $auth->add($auth->createPermission('admin/system/live-edit'));

        //Добавление разрешений для системных модулей
        foreach (glob(ADMIN_PATH . DIRECTORY_SEPARATOR . 'modules/*') as $module) {
            $moduleName = basename($module);
            $auth->add($auth->createPermission('admin/' . $moduleName));
        }
        //Добавление разрешений для модулей приложения
        foreach (glob(APP_PATH . DIRECTORY_SEPARATOR . 'modules/*') as $module) {
            $moduleName = basename($module);
            $auth->add($auth->createPermission('admin/' . $moduleName));
        }
        
        $userShopcartOrderPermission = $auth->createPermission('UserShopcartOrderPermission');
        $userShopcartOrderPermission->ruleName = $shopcartOrderRule->name;
        $auth->add($userShopcartOrderPermission);
        //End permissions
        //Roles
        $user = $auth->createRole('User');
        $user->ruleName = $userGroupRule->name;
        $auth->add($user);
        $auth->addChild($user, $userShopcartOrderPermission);

        $catalogUser = $auth->createRole('CatalogUser');
        $auth->add($catalogUser);
        $auth->addChild($catalogUser, $user);
        $auth->addChild($catalogUser, $auth->getPermission('admin'));
        $auth->addChild($catalogUser, $auth->getPermission('admin/catalog'));
        $auth->addChild($catalogUser, $auth->getPermission('admin/photos'));
        $auth->addChild($catalogUser, $auth->getPermission('admin/yml/excel/update-items-from-excel-file'));
        $auth->addChild($catalogUser, $auth->getPermission('admin/system/live-edit'));

        $shopcartUser = $auth->createRole('ShopcartUser');
        $auth->add($shopcartUser);
        $auth->addChild($shopcartUser, $user);
        $auth->addChild($shopcartUser, $auth->getPermission('admin'));
        $auth->addChild($shopcartUser, $auth->getPermission('admin/shopcart'));

        $shopcartAdmin = $auth->createRole('ShopcartAdmin');
        $auth->add($shopcartAdmin);
        $auth->addChild($shopcartAdmin, $catalogUser);
        $auth->addChild($shopcartAdmin, $shopcartUser);
        $auth->addChild($shopcartUser, $auth->getPermission('admin/delivery'));
        $auth->addChild($shopcartUser, $auth->getPermission('admin/payment'));
        $auth->addChild($shopcartUser, $auth->getPermission('admin/feedback'));
        $auth->addChild($shopcartUser, $auth->getPermission('admin/news'));
        $auth->addChild($shopcartUser, $auth->getPermission('admin/sale'));
        $auth->addChild($shopcartUser, $auth->getPermission('admin/comment'));

        $superAdmin = $auth->createRole('SuperAdmin');
        $auth->add($superAdmin); 
        foreach ($auth->getPermissions() as $permission) {
            $auth->addChild($superAdmin, $permission);
        }
        
        $auth->assign($superAdmin, $this->user_id);

        //End roles
        $this->stdout("DONE");
    }

}
