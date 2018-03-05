<?

namespace admin\rbac;

use Yii;
use yii\rbac\Rule;

class UserGroupRule extends Rule {

    public $name = 'userGroupRule';

    public function execute($user, $item, $params) {
        if (!\Yii::$app->user->isGuest) {
            return true;
        }
        return false;
    }

}
