<?

namespace admin\models\api;

use Yii;
use yii\base\Model;

class InstallForm extends Model {

    const RETURN_URL_KEY = 'admin_install_root_password';
    const ROOT_PASSWORD_KEY = 'admin_install_success_return';

    public $admin_password;
    public $admin_email;
    public $recaptcha_key;
    public $recaptcha_secret; 
    public $contact_email;
    public $contact_url;
    public $contact_name;
    public $contact_addressLocality;
    public $contact_streetAddress;
    public $contact_openingHours;
    public $contact_openingHoursISO86;
    public $contact_telephone;
    
    public function rules() {
        return [
            [['admin_password', 'admin_email', 'contact_name'], 'required'],
            ['admin_password', 'string', 'min' => 8],
            [['recaptcha_key', 'recaptcha_secret'], 'string'],
            [['contact_email', 'admin_email'], 'email'],
            [
                [
                    'admin_password',
                    'recaptcha_key',
                    'recaptcha_secret',
                    'admin_email',
                    'contact_email',
                    'contact_url',
                    'contact_name',
                    'contact_addressLocality',
                    'contact_streetAddress',
                    'contact_openingHours',
                    'contact_openingHoursISO86',
                    'contact_telephone',
                ],
                'trim'],
        ];
    }

    public function attributeLabels() {
        return [
            'admin_password' => Yii::t('admin','Пароль администратора'),
            'admin_email' => Yii::t('admin','E-mail администратора'),
            'contact_email' => Yii::t('admin','Контактный E-mail'),
            'contact_url' => Yii::t('admin','URL сайта'),
            'contact_name' => Yii::t('admin','Название сайта'),
            'contact_addressLocality' => Yii::t('admin','Населенный пункт'),
            'contact_streetAddress' => Yii::t('admin','Адрес в населенном пункте (улица, дом, строение и т.д.)'),
            'contact_openingHours' => Yii::t('admin','Часы работы'),
            'contact_openingHoursISO86' => Yii::t('admin','Часы работы в формате ISO86'),
            'contact_telephone' => Yii::t('admin','Контактный телефон'),
            'recaptcha_key' => Yii::t('admin', 'Код ReCaptcha Google'),
            'recaptcha_secret' =>  Yii::t('admin', 'Секретный ключ ReCaptcha Google'),
        ];
    }

}
