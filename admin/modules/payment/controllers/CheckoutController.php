<?

namespace admin\modules\payment\controllers;

use Yii;
use yii\data\ActiveDataProvider;

use admin\modules\payment\models\Checkout;

class CheckoutController extends \admin\base\admin\Controller {

    public function actionIndex() {
        $data = new ActiveDataProvider([
            'query' => Checkout::find()->orderBy(['time' => SORT_DESC]),
        ]);

        return $this->render('index', [
                    'data' => $data
        ]);
    }    

    public function actionDelete($id) {
        if (($model = Checkout::findOne($id))) {
            $model->delete();
        } else {
            $this->error = Yii::t('admin', 'Оплата не найдена');
        }
        return $this->formatResponse(Yii::t('admin/payment', 'Запись удалена'));
    }      
}
