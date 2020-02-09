<?

namespace admin\models;

use Yii;
use admin\helpers\Data;
use admin\behaviors\CacheFlush;
use admin\behaviors\SortableModel;

class Module extends \admin\base\ActiveRecord {

    const STATUS_OFF = 0;
    const STATUS_ON = 1;
    const CACHE_KEY = 'admin_modules';

    public static function tableName() {
        return 'admin_modules';
    }

    public function rules() {
        return [
            [['name', 'class', 'title'], 'required'],
            [['name', 'class', 'title', 'icon'], 'trim'],
            ['name', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/'],
            ['name', 'unique'],
            ['class', 'match', 'pattern' => '/^[\w\\\]+$/'],
            ['class', 'checkExists'],
            [['icon','type'], 'string'],
            ['status', 'in', 'range' => [0, 1]],
        ];
    }

    public function attributeLabels() {
        return [
            'name' => Yii::t('admin', 'Название модуля'),
            'class' => Yii::t('admin', 'Класс'),
            'title' => Yii::t('admin', 'Название'),
            'icon' => Yii::t('admin', 'Иконка'),
            'type' => Yii::t('admin', 'Тип (ADMIN, APP)'),
            'order_num' => Yii::t('admin', 'Порядок'),
        ];
    }

    public function behaviors() {
        return [
            CacheFlush::className(),
            SortableModel::className()
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if (!$this->settings || !is_array($this->settings)) {
                $this->settings = self::getDefaultSettings($this->name);
            }
            $this->settings = json_encode($this->settings);

            return true;
        } else {
            return false;
        }
    }

    public function afterFind() {
        parent::afterFind();
        $this->settings = $this->settings !== '' ? json_decode($this->settings, true) : self::getDefaultSettings($this->name);
    }

    public static function findAllActive() {
        return Data::cache(self::CACHE_KEY, 3600, function() {
                    $result = [];
                    try {
                        foreach (self::find()->where(['status' => self::STATUS_ON])->sort()->all() as $module) {
                            $module->trigger(self::EVENT_AFTER_FIND);
                            $result[$module->name] = (object) $module->attributes;
                        }
                    } catch (\yii\db\Exception $e) {
                        
                    }

                    return $result;
                });
    }

    public function setSettings($settings) {
        $newSettings = [];
        foreach ($this->settings as $key => $value) {
            if (is_array($value)) {
                $newSettings[$key] = $this->settings[$key];
                continue;
            }
            $newSettings[$key] = is_bool($value) ? ($settings[$key] ? true : false) : ($settings[$key] ? $settings[$key] : '');
        }
        $this->settings = $newSettings;
    }

    public function checkExists($attribute) {
        if (!class_exists($this->$attribute)) {
            $this->addError($attribute, Yii::t('admin', 'Класс не существует'));
        }
    }

    static function getDefaultSettings($moduleName) {
        $modules = Yii::$app->getModule('admin')->activeModules;
        if (isset($modules[$moduleName])) {
            return Yii::createObject($modules[$moduleName]->class, [$moduleName])->settings;
        } else {
            return [];
        }
    }    
}
