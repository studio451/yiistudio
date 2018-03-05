<?

namespace admin\modules\yml\models;

use Yii;
use yii\helpers\FileHelper;
use admin\modules\catalog\models\Category;
use admin\modules\yml\widgets\Excel;
use admin\modules\yml\YmlModule;
/**
 *
 * @property integer $id
 * @property string $title
 * @property string $data
 *
 */
class Export extends \admin\components\ActiveRecord {

    public $count = 0;
    public $class = 'admin\modules\yml\external_export\Shop';
    public $brands = [];
    public $categories = [];
    public $to_excel = false;
    public $status = '1';
    public $shop_name;
    public $shop_company;
    public $shop_url;
    public $shop_agency;
    public $shop_email;
    public $shop_cpa;
    public $all_delivery_options_cost = 0;
    public $all_delivery_options_days = '1-2';
    public $delivery_free_from = 2500;
    public $delivery_options_cost = 350;
    public $delivery_options_days = '1-2';
    public $asAttachment = true;

    public static function tableName() {
        return 'admin_module_yml_export';
    }

    public function rules() {
        return [
            [['title', 'shop_name', 'shop_company', 'shop_url', 'all_delivery_options_cost', 'all_delivery_options_days', 'delivery_free_from', 'delivery_options_cost', 'delivery_options_days'], 'required'],
            ['title', 'unique'],
            [['class'], 'required'],
            ['class', 'string', 'max' => 512],
            ['class', 'checkExists'],
            ['count', 'integer'],
            ['count', 'default', 'value' => 0],
            ['title', 'string', 'max' => 256],
            ['shop_name', 'string', 'max' => 20],
            [['shop_url', 'shop_agency', 'shop_email'], 'string', 'max' => 255],
            [['brands', 'categories', 'status', 'asAttachment'], 'safe'],
            [['to_excel', 'shop_cpa', 'delivery_free_from', 'all_delivery_options_cost', 'delivery_options_cost'], 'integer'],
            [['id', 'title'], 'safe'],
        ];
    }

    public function checkExists($attribute) {
        if (!class_exists($this->$attribute)) {
            $this->addError($attribute, Yii::t('admin', 'Класс не существует'));
        }
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('admin/yml', 'ID'),
            'title' => Yii::t('admin/yml', 'Название выгрузки'),
            'brands' => Yii::t('admin/yml', 'Бренды'),
            'class' => Yii::t('admin/yml', 'Класс'),
            'count' => Yii::t('admin/yml', 'Кол-во загружаемых предложений (0 - загрузить все)'),
            'categories' => Yii::t('admin/yml', 'Категории'),
            'shop_name' => Yii::t('admin/yml', 'Короткое название магазина (не более 20 символов)'),
            'shop_company' => Yii::t('admin/yml', 'Полное наименование компании, владеющей магазином. Не публикуется, используется для внутренней идентификации'),
            'shop_url' => Yii::t('admin/yml', 'URL главной страницы магазина'),
            'to_excel' => Yii::t('admin/yml', 'Тип экспорта: YML или Excel'),
            'status' => Yii::t('admin/yml', 'Выгружать товары со статусом'),
            'shop_agency' => Yii::t(
                    'admin/yml', 'Наименование агентства, которое оказывает техническую поддержку магазину и отвечает за работоспособность сайта'
            ),
            'shop_email' => Yii::t(
                    'admin/yml', 'Контактный адрес разработчиков CMS или агентства, осуществляющего техподдержку'
            ),
            'shop_cpa' => Yii::t('admin/yml', 'Участие товарных предложений в программе «Покупка на Маркете»'),
            'all_delivery_options_cost' => Yii::t('admin/yml', 'Общая стоимость доставки <delivery-options><option cost="..."/></delivery-options>'),
            'all_delivery_options_days' => Yii::t('admin/yml', 'Общая срок доставки в рабочих днях <delivery-options><option days="..."/></delivery-options>'),
            'delivery_free_from' => Yii::t('admin/yml', 'Бесплатная доставка от (Если выполняется это условие, то к товару применяются индивидуальные условия доставки)'),
            'delivery_options_cost' => Yii::t('admin/yml', 'Индивидуальная стоимость доставки <delivery-options><option cost="..."/></delivery-options>'),
            'delivery_options_days' => Yii::t('admin/yml', 'Индивидуальный срок доставки в рабочих днях <delivery-options><option days="..."/></delivery-options>'),
            'asAttachment' => Yii::t('admin/yml', 'Загрузить файл .xlsx на локальное устройство'),
        ];
    }

    public function afterFind() {
        $data = json_decode($this->data);

        $this->brands = $data->brands;
        $this->class = $data->class;
        $this->count = $data->count;
        $this->categories = $data->categories;
        $this->to_excel = $data->to_excel;
        $this->status = $data->status;
        $this->shop_name = $data->shop_name;
        $this->shop_company = $data->shop_company;
        $this->shop_url = $data->shop_url;
        $this->shop_agency = $data->shop_agency;
        $this->shop_email = $data->shop_email;
        $this->shop_cpa = $data->shop_cpa;
        $this->all_delivery_options_cost = $data->all_delivery_options_cost;
        $this->all_delivery_options_days = $data->all_delivery_options_days;
        $this->delivery_free_from = $data->delivery_free_from;
        $this->delivery_options_cost = $data->delivery_options_cost;
        $this->delivery_options_days = $data->delivery_options_days;
        $this->asAttachment = $data->asAttachment;
 
        parent::afterFind();
    }

    public function beforeValidate() {

        $data = [
            'brands' => $this->brands,
            'class' => $this->class,
            'count' => $this->count,
            'categories' => $this->categories,
            'to_excel' => $this->to_excel,
            'status' => $this->status,
            'shop_name' => $this->shop_name,
            'shop_company' => $this->shop_company,
            'shop_url' => $this->shop_url,
            'shop_agency' => $this->shop_agency,
            'shop_email' => $this->shop_email,
            'shop_cpa' => $this->shop_cpa,
            'all_delivery_options_cost' => $this->all_delivery_options_cost,
            'all_delivery_options_days' => $this->all_delivery_options_days,
            'delivery_free_from' => $this->delivery_free_from,
            'delivery_options_cost' => $this->delivery_options_cost,
            'delivery_options_days' => $this->delivery_options_days,
            'asAttachment' => $this->asAttachment,
        ];
        $this->data = json_encode($data);

        return parent::beforeValidate();
    }

    private function listCategories() {
        $query = Category::find();
        if ($this->status != '') {
            $query->andFilterWhere(['=', 'status', (int) $this->status]);
        }
        if (!empty($this->categories)) {
            $query->andFilterWhere(['in', 'id', (array) $this->categories]);
        }

        return $query->all();
    }

    private function listItems() {
        $query = \admin\modules\catalog\models\Item::find()->with(['category'])->orderBy(['brand_id' => SORT_ASC, 'name' => SORT_ASC]);
        if ($this->status != '') {
            $query->andFilterWhere(['=', 'status', (int) $this->status]);
        }

        if (!empty($this->categories)) {
            $query->andFilterWhere(['in', 'category_id', (array) $this->categories]);
        }

        if (!empty($this->brands)) {
            $query->andFilterWhere(['in', 'brand_id', (array) $this->brands]);
        }

        $items = $query->all();

        $_items = [];
        foreach ($items as $item) {
            if ($this->count > 0 && $count >= $this->count) {
                break;
            }
            $count++;

            $_items[] = $this->getItem($item);
        }
        return $_items;
    }

    public function saveToYmlFile() {
        
        $categories = $this->listCategories();
        $items = $this->listItems();
        
        $file_tmp = Yii::getAlias('@webroot') . '/exports_yml/yml_' . $this->shop_name . '_tmp.xml';
        $file = Yii::getAlias('@webroot') . '/exports_yml/yml_' . $this->shop_name . '.xml';
        
        FileHelper::createDirectory(Yii::getAlias('@webroot') . '/exports_yml', 0777);
        
        file_put_contents($file_tmp, Yii::$app->controller->renderPartial(
                        '@admin/modules/yml/views/export/yml.php', [
                    'xmlHeader' => '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">' . PHP_EOL,
                    'model' => $this,
                    'currencies' => ['RUB'],
                    'categories' => $categories,
                    'items' => $items,
                        ]
        ));
        $file_old = Yii::getAlias('@webroot') . '/exports_yml/yml_' . $this->shop_name . '_' . date('Y_m_d_H_i_s') . '.xml';
        if (file_exists($file)) {
            rename($file, $file_old);
        }
        rename($file_tmp, $file);
        return $file;
    }

    public function getItem($item) {
        throw new Exception("Must be implemented");
    }

    public function saveToExcelFile() {

        $items = $this->listItems();

        $fileName = $this->title . ' - ' . date("Y.m.d H-i") . '.xlsx';
        if (!$this->asAttachment) {
            FileHelper::createDirectory(Yii::getAlias('@webroot') . '/exports_excel', 0777);
            $savePath = Yii::getAlias('@webroot') . '/exports_excel';
        }
        Excel::export([
            'models' => $items,
            'columns' => YmlModule::getFields(),
            'fileName' => $fileName,
            'savePath' => $savePath,
            'asAttachment' => $this->asAttachment
        ]);

        return $savePath .'/'. $fileName;
    }
}
