<?

namespace admin\modules\shopcart\commands;

use Yii;
use yii\console\Controller;
use admin\models\User;
use admin\modules\shopcart\models\Order;

class ShopcartController extends Controller {

    public function options($actionID) {
        if ($actionID == 'clear-users-no-order') {
            return [
            ];
        }        
    }

    public function actionClearUsersNoOrder() {

        $users = User::find()->where([])->all();
        
        $deleted = 0;
        foreach ($users as $user) {

            $count = Order::find()->where(['user_id' => $user->id])->count();
            if($count == 0)
            {
             if($user->delete())
             {
                $deleted++;
             }
            }            
        }

        $this->stdout(Yii::t('admin', '{deleted} пользователя(ей), у которых нет заказов, удалено',['deleted' => $deleted]) . "\n");
        $this->stdout("DONE\n");
    }    

}
