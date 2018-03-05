<?

namespace admin\modules\shopcart\models;

use Yii;
use admin\behaviors\CalculateNotice;
use admin\helpers\Mail;
use admin\models\Setting;
use admin\validators\EscapeValidator;
use yii\helpers\Url;

class Order extends \admin\components\ActiveRecordData {

    const PAID_STATUS_NOT_PAID = 0;
    const PAID_STATUS_PAID = 1;
    const STATUS_BLANK = 0;
    const STATUS_PENDING = 1;
    const STATUS_PROCESSED = 2;
    const STATUS_DECLINED = 3;
    const STATUS_SENT = 4;
    const STATUS_RETURNED = 5;
    const STATUS_ERROR = 6;
    const STATUS_COMPLETED = 7;
    const SESSION_KEY = 'admin_module_shopcart_at';

    public static function tableName() {
        return 'admin_module_shopcart_orders';
    }

    public function rules() {
        return [            
            ['name', 'required', 'when' => function($model) {
                    return $model->scenario == 'confirm' && Yii::$app->getModule('admin')->activeModules['shopcart']->settings['enableName'];
                }],
            ['phone', 'required', 'when' => function($model) {
                    return $model->scenario == 'confirm' && Yii::$app->getModule('admin')->activeModules['shopcart']->settings['enablePhone'];
                }],
            [['name', 'address', 'phone', 'comment'], 'trim'],
            ['email', 'email'],
            [['user_id', 'delivery_id', 'payment_id'], 'integer'],
            ['name', 'string', 'max' => 32],
            ['address', 'string', 'max' => 1024],
            ['phone', 'string', 'max' => 32],
            ['phone', 'match', 'pattern' => '/^[\d\s-\+\(\)]+$/'],
            ['comment', 'string', 'max' => 1024],
            [['name', 'address', 'phone', 'comment'], EscapeValidator::className()],
        ];
    }

    public function attributeLabels() {
        return [
            'name' => Yii::t('admin', 'Имя'),
            'email' => Yii::t('admin', 'E-mail'),
            'address' => Yii::t('admin', 'Адрес'),
            'phone' => Yii::t('admin', 'Телефон'),
            'comment' => Yii::t('admin', 'Комментарий'),
            'remark' => Yii::t('admin/shopcart', 'Комментарий сотрудника'),
        ];
    }

    public function behaviors() {
        return [
            'cn' => [
                'class' => CalculateNotice::className(),
                'callback' => function() {
                    return self::find()->where(['new' => 1])->count();
                }
                    ]
                ];
            }

            public static function statusName($status) {
                $states = self::states();
                return !empty($states[$status]) ? $states[$status] : $status;
            }

            public static function states() {
                return [
                    self::STATUS_BLANK => Yii::t('admin/shopcart', 'Корзина'),
                    self::STATUS_PENDING => Yii::t('admin/shopcart', 'В обработке'),
                    self::STATUS_PROCESSED => Yii::t('admin/shopcart', 'Обработан'),
                    self::STATUS_DECLINED => Yii::t('admin/shopcart', 'Отменен'),
                    self::STATUS_SENT => Yii::t('admin/shopcart', 'Отправлен'),
                    self::STATUS_RETURNED => Yii::t('admin/shopcart', 'Возврат'),
                    self::STATUS_ERROR => Yii::t('admin/shopcart', 'Ошибка'),
                    self::STATUS_COMPLETED => Yii::t('admin/shopcart', 'Выполнен'),
                ];
            }

            public function isPaid() {
                return (int) $this->paid_status === static::PAID_STATUS_PAID;
            }

            public function isNotPaid() {
                return (int) $this->paid_status === static::PAID_STATUS_NOT_PAID;
            }

            public static function paidStatusName($paidStatus) {
                $paidStates = self::paidStates();
                return !empty($paidStates[$paidStatus]) ? $paidStates[$paidStatus] : $paidStatus;
            }

            public static function paidStates() {
                return [
                    self::PAID_STATUS_NOT_PAID => Yii::t('admin/shopcart', 'Не оплачен'),
                    self::PAID_STATUS_PAID => Yii::t('admin/shopcart', 'Оплачен'),
                ];
            }

            public function getStatusName() {
                $states = self::states();
                return !empty($states[$this->status]) ? $states[$this->status] : $this->status;
            }

            public function renderStatus() {
                if ($this->status == self::STATUS_PENDING) {
                    return '<span class="label label-primary">' . $this->getStatusName() . '</span>';
                }
                if ($this->status == self::STATUS_PROCESSED) {
                    return '<span class="label label-success">' . $this->getStatusName() . '</span>';
                }
                return '<span class="label label-default">' . $this->getStatusName() . '</span>';
            }

            public function getPaidStatusName() {
                $paidStates = self::paidStates();
                return !empty($paidStates[$this->paid_status]) ? $paidStates[$this->paid_status] : $this->paid_status;
            }

            public function renderPaidStatus() {
                if ($this->paid_status == self::PAID_STATUS_PAID) {
                    return '<span class="label label-success">' . $this->getPaidStatusName() . '</span>';
                }
                return '<span class="label label-default">' . $this->getPaidStatusName() . '</span>';
            }

            public function getGoods() {
                return $this->hasMany(Good::className(), ['order_id' => 'id']);
            }

            public function getCost() {
                $total = 0;
                foreach ($this->goods as $good) {
                    $total += $good->count * round($good->price * (1 - $good->discount / 100));
                }

                return $total;
            }

            public function getTotalCost() {
                return $this->cost + $this->delivery_cost;
            }

            public function beforeSave($insert) {
                if (parent::beforeSave($insert)) {
                    if ($insert) {
                        $this->ip = Yii::$app->request->userIP;
                        $this->access_token = Yii::$app->security->generateRandomString(32);
                        $this->time = time();
                    } else {
                        if ($this->oldAttributes['status'] == self::STATUS_BLANK && $this->status == self::STATUS_PENDING) {
                            $this->new = 1;
                            $this->notifyAdmin();
                            $this->notifyUser();
                        }
                    }
                    return true;
                } else {
                    return false;
                }
            }

            public function afterDelete() {
                parent::afterDelete();

                foreach ($this->getGoods()->all() as $good) {
                    $good->delete();
                }
            }

            public function notifyAdmin() {
                $settings = Yii::$app->getModule('admin')->activeModules['shopcart']->settings;

                if (!$settings['notifyAdmin']) {
                    return false;
                }
                return Mail::send(
                                Setting::get('contact_email'), str_replace('##order_id##', $this->id, $settings['subjectNotifyAdmin']), $settings['templateNotifyAdmin'], [
                            'order' => $this,
                            'link' => Url::to(['/admin/shopcart/a/edit', 'id' => $this->primaryKey], true),
                                ]
                );
            }

            public function notifyUser() {
                if ($this->email) {
                    $settings = Yii::$app->getModule('admin')->activeModules['shopcart']->settings;

                    return Mail::send(
                                    $this->email, str_replace('##order_id##', $this->id, $settings['subjectNotifyUser']), $settings['templateNotifyUser'], [
                                'order' => $this,
                                'link' => Url::to([$settings['frontendShopcartOrderRoute'], 'id' => $this->primaryKey, 'token' => $this->access_token], true),
                                    ], ['replyToAdminEmail' => true]
                    );
                }
            }

        }
        