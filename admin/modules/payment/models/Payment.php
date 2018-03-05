<?

namespace admin\modules\payment\models;

use Yii;
use yii\base\Exception;
use yii\helpers\Url;
use admin\behaviors\SluggableBehavior;
use admin\behaviors\SortableModel;
use admin\modules\shopcart\models\Order;
use admin\helpers\Mail;
use admin\models\Setting;

class Payment extends \admin\components\ActiveRecordData {

    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    public static function tableName() {
        return 'admin_module_payment';
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
                [['description', 'data'], 'safe'],
                [['status'], 'integer'],
                ['status', 'default', 'value' => self::STATUS_ON],
                [['is_manual'], 'integer'],
                ['is_manual', 'default', 'value' => 1],
                ['available_to', 'default', 'value' => 0],
                [['class'], 'required'],
                ['class', 'string', 'max' => 512],
                ['class', 'checkExists'],
                ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('admin', 'Код может содержать символы 0-9, a-z и "-" (не более: 128).')],
                ['slug', 'default', 'value' => null],
                ['slug', 'unique']
        ];
    }

    public function checkExists($attribute) {
        if (!class_exists($this->$attribute)) {
            $this->addError($attribute, Yii::t('admin', 'Класс не существует'));
        }
    }

    public function attributeLabels() {
        return [
            'title' => Yii::t('admin', 'Название'),
            'description' => Yii::t('admin', 'Описание'),
            'slug' => Yii::t('admin', 'Код'),
            'class' => Yii::t('admin/yml', 'Класс'),
            'available_to' => Yii::t('admin/payment', 'Доступен до'),
            'is_manual' => Yii::t('admin/payment', 'Оплата вручную'),
        ];
    }

    public function renderCheckoutForm($order) {
        throw new Exception("Must be implemented");
    }

    public function processCheckout($request) {
        throw new Exception("Must be implemented");
    }

    public function test($order_id, $amount) {
        return null;
    }

    public function payOrder($order) {
        if ($order->isPaid()) {
            $this->errorProcessCheckout('Заказ уже был оплачен', $order);
            return false;
        }

        $order->paid_status = Order::PAID_STATUS_PAID;
        $order->paid_time = time();
        $order->paid_details = $this->title;

        $this->successProcessCheckout('Заказ оплачен', $order);
        return $order->save();
    }

    public function errorProcessCheckout($description, $order = null) {
        $model = new Checkout();

        $model->time = time();
        $model->status = Checkout::CHECKOUT_STATUS_ERROR;
        $model->payment_id = $this->id;
        $model->payment_title = $this->title;
        $model->description = $description;
        $model->request = json_encode(array_merge(['referrer' => Yii::$app->request->referrer], Yii::$app->request->post()));
        if ($order) {
            $model->order_id = $order->id;
        }

        self::notifyAdmin($order, $description);

        return $model->save();
    }

    public function successProcessCheckout($description, $order = null, $notify = true) {
        $model = new Checkout();

        $model->time = time();
        $model->status = Checkout::CHECKOUT_STATUS_SUCCESS;
        $model->payment_id = $this->id;
        $model->payment_title = $this->title;
        $model->description = $description;
        $model->request = json_encode(Yii::$app->request->post());
        if ($order) {
            $model->order_id = $order->id;
        }

        if ($notify) {
            self::notifyAdmin($order, $description);
            self::notifyUser($order);
        }
        return $model->save();
    }

    public static function adminProcessCheckout($description, $order) {
        $model = new Checkout();

        $model->time = time();
        $model->status = Checkout::CHECKOUT_STATUS_ADMIN;
        $model->description = $description;
        $model->request = json_encode(Yii::$app->request->post());
        $model->order_id = $order->id;

        self::notifyAdmin($order, $description);

        return $model->save();
    }

    public static function notifyAdmin($order, $description) {
        $settings = Yii::$app->getModule('admin')->activeModules['payment']->settings;
        if (!$settings['notifyAdmin']) {
            return false;
        }
        return Mail::send(
                        Setting::get('contact_email'), str_replace('##order_id##', $order->id, $settings['subjectNotifyAdmin']), $settings['templateNotifyAdmin'], [
                    'order' => $order,
                    'description' => $description,
                    'link' => Url::to(['/admin/shopcart/a/edit', 'id' => $order->id], true),
                        ]
        );
    }

    public static function notifyUser($order) {
        $settings = Yii::$app->getModule('admin')->activeModules['payment']->settings;

        return Mail::send(
                    $order->email, str_replace('##order_id##', $order->id, $settings['subjectNotifyUser']), $settings['templateNotifyUser'], [
                    'order' => $order,
                    'link' => Url::to([$settings['frontendShopcartOrderRoute'], 'id' => $order->primaryKey, 'token' => $order->access_token], true),
                    ], ['replyToAdminEmail' => true]
        );
    }

}
