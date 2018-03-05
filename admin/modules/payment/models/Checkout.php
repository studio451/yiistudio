<?

namespace admin\modules\payment\models;

use Yii;

class Checkout extends \admin\components\ActiveRecord {

    const CHECKOUT_STATUS_ERROR = 0;
    const CHECKOUT_STATUS_SUCCESS = 1;
    const CHECKOUT_STATUS_ADMIN = 2;

    public static function tableName() {
        return 'admin_module_payment_checkout';
    }

    public function rules() {
        return [
            ['time', 'default', 'value' => time()],
        ];
    }

    public static function getByOrderId($order_id) {
        return Checkout::find()->where(['order_id' => $order_id])->orderBy(['time' => SORT_DESC])->all();
    }

    public function renderStatus() {
        if ($this->status == self::CHECKOUT_STATUS_SUCCESS) {
            return '<span class="label label-success">' . Yii::t('admin/shopcart', 'Успешная оплата') . '</span>';
        } elseif ($this->status == self::CHECKOUT_STATUS_ADMIN) {
            return '<span class="label label-primary">' . Yii::t('admin/shopcart', 'Панель управления') . '</span>';
        } else {
            return '<span class="label label-danger">' . Yii::t('admin/shopcart', 'Ошибка оплаты') . '</span>';
        }
    }

    public function renderModal($id = '') {

        if ($id == '') {
            $id = 'requestModal_' . $this->id;
        }
        $str = '';
        $request = (json_decode($this->request));
        foreach ($request as $key => $value) {
            $str .= '<span class=text-muted>'.$key . '</span>: <b>' . $value . '</b><br>';
        }
        return '<div class = "modal fade" id = "' . $id . '" tabindex = "-1" role = "dialog">
<div class = "modal-dialog modal-lg" role = "document">
<div class = "modal-content">
<div class = "modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-fw fa-close"></i></button>
<h4 class = "modal-title">' . Yii::$app->formatter->asDatetime($this->time, 'short')
                . ' ' . $this->renderStatus() . '<br>' . $this->description . '</h4>
</div>
<div class="modal-body">' . $str . '</div>                                
</div>
</div>
</div>';
    }

}
