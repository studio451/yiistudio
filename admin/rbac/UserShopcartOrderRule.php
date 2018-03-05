<?

namespace admin\rbac;

use Yii;
use yii\rbac\Rule;

class UserShopcartOrderRule extends Rule {

    public $name = 'userShopcartOrderRule';

    public function execute($user, $item, $params) {

        return isset($params['order']) ? $params['order']->user_id == $user : false;
    }

}
