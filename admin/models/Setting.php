<?

namespace admin\models;

use Yii;
use admin\helpers\Data;
use admin\behaviors\CacheFlush;

class Setting extends \admin\base\ActiveRecord {

    const VISIBLE_NONE = 0;
    const VISIBLE_ROOT = 1;
    const VISIBLE_ALL = 2;
    const CACHE_KEY = 'admin_settings';

    static $_data;

    public static function all() {
        return
                    [
                        ['name' => 'admin_email', 'value' => '', 'title' => Yii::t('admin', 'E-mail администратора')],
                        ['name' => 'contact_email', 'value' => '', 'title' => Yii::t('admin', 'E-mail, с которого рассылаются письма')], 
                        ['name' => 'contact_url', 'value' => '', 'title' => Yii::t('admin', 'URL сайта')],
                        ['name' => 'contact_name', 'value' => '', 'title' => Yii::t('admin', 'Название сайта')],
                        ['name' => 'contact_addressLocality', 'value' => '', 'title' => Yii::t('admin', 'Населенный пункт')],
                        ['name' => 'contact_streetAddress', 'value' => '', 'title' => Yii::t('admin', 'Адрес в населенном пункт (улица, дом, строение и т.д.)')],
                        ['name' => 'contact_openingHours', 'value' => '', 'title' => Yii::t('admin', 'Часы работы')],
                        ['name' => 'contact_openingHoursISO86', 'value' => '', 'title' => Yii::t('admin', 'Часы работы в формате ISO86')],
                        ['name' => 'contact_telephone', 'value' => '', 'title' => Yii::t('admin', 'Контактный телефон')],
                        ['name' => 'contact_messenger', 'value' => '', 'title' => Yii::t('admin', 'Мессенджер')],
                        ['name' => 'contact_skype', 'value' => '', 'title' => Yii::t('admin', 'Skype')],
                        ['name' => 'recaptcha_key', 'value' => '', 'title' => Yii::t('admin', 'Код ReCaptcha Google')],
                        ['name' => 'recaptcha_secret', 'value' => '', 'title' => Yii::t('admin', 'Секретный ключ ReCaptcha Google')],
                        ['name' => 'auth_time', 'value' => 2592000, 'title' => Yii::t('admin', 'Время авторизации')],                                                               
                        ['name' => 'toolbar_position', 'value' => 'top', 'title' => Yii::t('admin', 'Позиция панели инструментов') . ' ("top" or "bottom")'],                        
                        ['name' => 'subjectNotifyUserPasswordResetToken', 'value' => Yii::t('admin', 'Сброс пароля для ') . '', 'title' => Yii::t('admin', 'Тема письма Сброс пароля')],
                        ['name' => 'templateNotifyUserPasswordResetToken', 'value' => '@admin/mail/ru/password_reset_token', 'title' => Yii::t('admin', 'Шаблон письма Сброс пароля')],
                        ['name' => 'replyToAdminEmail', 'value' => true, 'title' => Yii::t('admin', 'Отправлять копии писем на email администратора')],
                        ['name' => 'counter_yandexMetrikaId', 'value' => 0, 'title' => Yii::t('admin', 'Идентификатор счетчика Yandex.Metrika')],
                        ['name' => 'counter_googleAnalyticsId', 'value' => 0, 'title' => Yii::t('admin', 'Идентификатор счетчика Google Analytics')],
                        ['name' => 'path_dumps', 'value' => Yii::getAlias('@app/dumps'), 'title' => Yii::t('admin', 'Путь к дампам')],
                        ['name' => 'viewFileRegistration', 'value' => '@admin/views/api/user/registration', 'title' => Yii::t('admin', 'View-файл регистрации')],
                        ['name' => 'enableCaptchaRegistration', 'value' => 0, 'title' => Yii::t('admin', 'Запрашивать reCapcha при регистрации')],
                        ['name' => 'generatePasswordRegistration', 'value' => 0, 'title' => Yii::t('admin', 'Генерировать пароль при регистрации')],
                        ['name' => 'subjectNotifyUserRegistration', 'value' => Yii::t('admin', 'Регистрация на сайте '), 'title' => Yii::t('admin', 'Тема письма Регистрация')], ['name' => 'templateNotifyUserRegistration', 'value' => '@admin/mail/ru/registration', 'title' => Yii::t('admin', 'Шаблон письма Регистрация')],
        ];
    }

    public static function tableName() {
        return 'admin_settings';
    }

    public function rules() {
        return [
                ['value', 'safe'],
                [['name', 'title'], 'required'],
                [['name', 'title'], 'trim'],
                ['name', 'match', 'pattern' => '/^[a-zA-Z][\w_-]*$/'],
                ['name', 'unique'],
                ['visibility', 'number', 'integerOnly' => true]
        ];
    }

    public function attributeLabels() {
        return [
            'name' => Yii::t('admin', 'Код'),
            'title' => Yii::t('admin', 'Название'),
            'value' => Yii::t('admin', 'Значение'),
            'visibility' => Yii::t('admin', 'Только для администратора')
        ];
    }

    public function behaviors() {
        return [
            CacheFlush::className()
        ];
    }

    public static function get($name) {
        if (!self::$_data) {
            self::$_data = Data::cache(self::CACHE_KEY, 3600, function() {
                        $result = [];
                        try {
                            foreach (parent::find()->all() as $setting) {
                                $result[$setting->name] = $setting->value;
                            }
                        } catch (\yii\db\Exception $e) {
                            
                        }
                        return $result;
                    });
        }
        return isset(self::$_data[$name]) ? self::$_data[$name] : null;
    }

    public static function set($name, $value, $title = "") {
        if (self::get($name)) {
            $setting = Setting::find()->where(['name' => $name])->one();
            $setting->value = $value;
            if ($title) {
                $setting->value = $title;
            }
        } else {
            if ($title == "") {
                $title = $name;
            }
            $setting = new Setting([
                'name' => $name,
                'value' => $value,
                'title' => $title,
                'visibility' => self::VISIBLE_ALL
            ]);
        }
        $setting->save();
    }

    public static function updateSettings($values = []) {

        foreach (self::all() as $setting) {
            $count = Setting::find()->where(['name' => $setting['name']])->count();
            if ($count == 0) {
                if (isset($values[$setting['name']])) {
                    $value = $values[$setting['name']];
                } else {
                    $value = $setting['value'];
                }
                $setting = new Setting([
                    'name' => $setting['name'],
                    'value' => $value,
                    'title' => $setting['title'],
                    'visibility' => self::VISIBLE_ALL
                ]);
                $setting->save();
            }
        }

        return true;
    }

}
