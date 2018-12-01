<?

namespace admin\modules\delivery\models;

use Yii;
use admin\behaviors\SluggableBehavior;
use admin\behaviors\SortableModel;
use admin\modules\payment\models\Payment;

class Delivery extends \admin\components\ActiveRecord {

    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    private $_paymentsCheckboxList = null;

    public static function tableName() {
        return 'admin_module_delivery';
    }

    public function behaviors() {
        return [
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true
            ],
            SortableModel::className()
        ];
    }

    public function rules() {
        return [
            [['title'], 'required'],
            [['description', 'paymentsCheckboxList'], 'safe'],
            [['price', 'free_from', 'available_from'], 'number'],
            [['status', 'need_address'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ON],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('admin', 'Код может содержать символы 0-9, a-z и "-" (не более: 128).')],
            ['slug', 'default', 'value' => null],
            ['slug', 'unique']
        ];
    }

    public function attributeLabels() {
        return [
            'title' => Yii::t('admin', 'Название'),
            'description' => Yii::t('admin', 'Описание'),
            'slug' => Yii::t('admin', 'Код'),
            'price' => Yii::t('admin/delivery', 'Цена'),
            'free_from' => Yii::t('admin/delivery', 'Бесплатно от'),
            'available_from' => Yii::t('admin/delivery', 'Доступна от'),
            'need_address' => Yii::t('admin/delivery', 'Нужен адрес'),
        ];
    }

    public function getPayment($id_payment) {
        foreach ($this->payments as $payment) {
            if ($payment->id == $id_payment && $payment->status == Payment::STATUS_ON) {
                return $payment;
            }
        }
        return null;
    }

    public function getPayments() {
        return $this->hasMany(Payment::className(), ['id' => 'payment_id'])->viaTable('admin_module_delivery_payment', ['delivery_id' => 'id']);
    }

    public function getPaymentsCheckboxList() {
        if ($this->_paymentsCheckboxList == null) {
            $this->_paymentsCheckboxList = yii\helpers\ArrayHelper::map($this->payments, 'id', 'id');
        }
        return $this->_paymentsCheckboxList;
    }

    public function setPaymentsCheckboxList($value) {
        $this->_paymentsCheckboxList = $value;
    }

    public function afterSave($insert, $attributes) {

        parent::afterSave($insert, $attributes);

        DeliveryPayment::deleteAll(['delivery_id' => $this->id]);

        if (is_array($this->paymentsCheckboxList)) {
            foreach ($this->paymentsCheckboxList as $payment_id) {
                $deliveryPayment = new DeliveryPayment();
                $deliveryPayment->delivery_id = $this->id;
                $deliveryPayment->payment_id = $payment_id;
                $deliveryPayment->save();
            }
        }
    }
}
