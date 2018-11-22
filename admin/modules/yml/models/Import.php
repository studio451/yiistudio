<?

namespace admin\modules\yml\models;

use Yii;
use yii\helpers\FileHelper;
use admin\modules\catalog\models\Item;
use admin\modules\catalog\models\Group;
use admin\modules\catalog\models\Category;
use admin\modules\catalog\models\Brand;
use admin\modules\news\models\News;
use admin\models\User;
use admin\helpers\Image;
use admin\models\Photo;
use admin\modules\yml\widgets\Excel;
use admin\modules\yml\YmlModule;

class Import extends \admin\components\ActiveRecord {

    /**
     * @var array
     */
    public $count = 0;
    public $url = '';
    public $class = 'admin\modules\yml\external_import\???';
    public $percent = 100;
    public $categories = [];
    public $importFile;
    public $asAttachment = true;
//TRUE - Обновляем данные по ID у существующих
//FALSE - Добавляем новые
    public $updateOnly = false;
    //Максимальное кол-во загружаемых фото
    public $max_number_of_uploaded_photos = 5;
    //Внешний источник, сброс активности для всех позиций из этого источника
    public $external_name = '';
    //Множитель цены, в процентах
    public $mult = 100;
    //Свойство, которое берем в качестве цены из внешнего файла *.yml
    //(информационное поле, ни на что не влияет)
    public $yml_price_property = 100;
    
    public static function tableName() {
        return 'admin_module_yml_import';
    }

    public function rules() {
        return [
                [['title'], 'required'],
                ['title', 'unique'],
                [['title', 'external_name'], 'string', 'max' => 256],
                [['url'], 'required'],
                ['url', 'string', 'max' => 512],
                [['class'], 'required'],
                ['class', 'string', 'max' => 512],
                ['class', 'checkExists'],
                ['count', 'integer'],
                ['count', 'default', 'value' => 0],
                ['mult', 'integer'],
                ['mult', 'default', 'value' => 100],
                ['max_number_of_uploaded_photos', 'integer'],
                ['max_number_of_uploaded_photos', 'default', 'value' => 5],
                ['percent', 'integer'],
                ['percent', 'default', 'value' => 100],
                [['categories', 'asAttachment', 'updateOnly', 'yml_price_property'], 'safe'],
                [['id', 'title'], 'safe'],
                [['importFile'], 'file', 'skipOnEmpty' => false, 'extensions' => ['xls', 'xlsx'], 'on' => 'import'],
        ];
    }

    public function checkExists($attribute) {
        if (!class_exists($this->$attribute)) {
            $this->addError($attribute, Yii::t('admin', 'Класс не существует'));
        }
    }

    public function upload() {
        if ($this->validate(['importFile'])) {
            FileHelper::createDirectory(Yii::getAlias('@webroot') . '/imports_yml', 0777);
            $this->importFile->saveAs('imports_yml/' . $this->importFile->baseName . '.' . $this->importFile->extension);
            return 'imports_yml/' . $this->importFile->baseName . '.' . $this->importFile->extension;
        } else {
            return false;
        }
    }

    public function attributeLabels() {
        return [
            'url' => Yii::t('admin', 'URL'),
            'class' => Yii::t('admin', 'Класс'),
            'categories' => Yii::t('admin', 'Категории'),
            'count' => Yii::t('admin/yml', 'Кол-во загружаемых предложений (0 - загрузить все)'),
            'asAttachment' => Yii::t('admin/yml', 'Загрузить файл .xlsx на локальное устройство'),
            'external_name' => Yii::t('admin/yml', 'Внешний источник, сброс активности для всех позиций из этого источника'),
            'max_number_of_uploaded_photos' => Yii::t('admin/yml', 'Кол-во загружаемых фото (0 - загрузить все)'),
            'mult' => Yii::t('admin/yml', 'Множитель цены, в процентах'),
            'yml_price_property' => Yii::t('admin/yml', 'Свойство, которое берем в качестве цены из внешнего файла *.yml (информационное поле, ни на что не влияет)'),
        ];
    }

    /**
     *
     */
    public function afterFind() {
        $data = json_decode($this->data);
        $this->categories = $data->categories;
        $this->count = $data->count;
        $this->url = $data->url;
        $this->class = $data->class;
        $this->asAttachment = $data->asAttachment;
        $this->updateOnly = $data->updateOnly;
        $this->max_number_of_uploaded_photos = $data->max_number_of_uploaded_photos;
        $this->external_name = $data->external_name;
        $this->mult = $data->mult;
        $this->yml_price_property = $data->yml_price_property;
        parent::afterFind();
    }

    /**
     * @return bool
     */
    public function beforeValidate() {
        $data = [
            'categories' => $this->categories,
            'count' => $this->count,
            'url' => $this->url,
            'class' => $this->class,
            'asAttachment' => $this->asAttachment,
            'updateOnly' => $this->updateOnly,
            'max_number_of_uploaded_photos' => $this->max_number_of_uploaded_photos,
            'external_name' => $this->external_name,
            'mult' => $this->mult,
            'yml_price_property' => $this->yml_price_property,
        ];
        $this->data = json_encode($data);

        return parent::beforeValidate();
    }

    public function importFromXml() {

        $yml = simplexml_load_string(file_get_contents($this->url));

        $count = 0;
        $items = [];
        foreach ($yml->shop->offers->offer as $offer) {

            if ($this->count > 0 && $count >= $this->count) {
                break;
            }

            $item = $this->getOffer($offer);
            if ($item) {
                $items[] = $item;
                $count++;
            }
        }
        return $items;
    }

    public function getOffer($offer) {
        throw new Exception("Must be implemented");
    }

    public function saveToExcelFile($noAttachment = false) {
        $models = $this->importFromXml();

        if ($noAttachment) {
            $this->asAttachment = false;
        }

        $fileName = $this->title . ' - ' . date("Y.m.d H-i") . '.xlsx';
        if (!$this->asAttachment) {
            FileHelper::createDirectory(Yii::getAlias('@webroot') . '/imports_yml', 0777);
            $savePath = Yii::getAlias('@webroot/imports_yml') . '/';
        }
        Excel::export([
            'models' => $models,
            'columns' => YmlModule::getFields(),
            'fileName' => $fileName,
            'savePath' => $savePath,
            'asAttachment' => $this->asAttachment
        ]);


        return $savePath . $fileName;
    }

    public static function savePhoto($url, $item) {
        $photo = new Photo;
        $photo->class = 'admin\modules\catalog\models\Item';
        $photo->item_id = $item->id;
        $photo->description = $item->title;

        $photo->image = Image::upload_from_url($url, $item->slug . '_' . substr(uniqid(md5(rand()), true), 0, 10), 'photos', Photo::PHOTO_MAX_WIDTH);

        if ($photo->image) {
            if ($photo->save()) {
                
            } else {
                @unlink(Yii::getAlias('@webroot') . str_replace(Url::base(true), '', $photo->image));
                return false;
            }
        } else {
            return false;
        }
        return $photo->image;
    }

    //Добавление новых элементов, брендов, обновление старых
    public function loadItemsFromExcelFile($fileInput) {

        if ($this->external_name != '') {
            Group::deleteAll(['external_name' => $this->external_name]);
            Item::updateAll(['status' => Item::STATUS_OFF], ['and', ['external_name' => $this->external_name], ['!=', 'external_manual', 1]]);
        }

        $array = Excel::import([
                    'fileName' => $fileInput,
        ]);
        $rows = $array['fileName'];
        $errors = [];
        $save_amount = 0;


        $categories = [];
        $_categories = Category::find()->all();
        foreach ($_categories as $_category) {
            $type = $_category->getFieldOptions('type');
            if ($type) {
                $categories[mb_strtolower($type)] = $_category;
            }
        }

        $brands = [];
        $_brands = Brand::find()->all();
        foreach ($_brands as $_brand) {
            $brands[mb_strtolower($_brand->title)] = $_brand;
        }

        foreach ($rows as $row) {
            $error = null;
            $category = $categories[mb_strtolower($row['Наименование'])];
            if ($category) {
                $brand = $brands[mb_strtolower($row['Бренд'])];
                if (!$brand) {
                    $brand = new Brand();
                    $brand->title = ucfirst(mb_strtolower($row['Бренд']));
                    $brand->save();
                    $brands[mb_strtolower($row['Бренд'])] = $brand;
                }

                if ($brand) {
                    $item_id = (int) trim($row['ID']);

                    $item = new Item();

                    $item->brand_id = $brand->id;
                    $item->category_id = $category->id;
                    $item->name = trim($row['Модель']);
                    $item->article = trim($row['Артикул']);
                    $item->base_price = trim($row['Закупочная цена']);
                    $item->price = trim($row['Цена']);
                    $item->status = trim($row['Статус']);
                    $item->description = trim($row['Описание']);
                    $item->available = 1;

                    $item->data = new \stdClass();
                    //Доп.параметры
                    foreach (YmlModule::getAdditionalFields(true) as $field) {
                        if ($row[$field['header']]) {
                            $item->data->$field['attribute'] = trim($row[$field['header']]);
                        }
                    }



                    $old_item = null;
                    if ($item_id) {
                        if ($this->external_name != '') {
                            $item->external_id = $item_id;
                            $item->external_name = $this->external_name;
                            $old_item = Item::findOne(['external_id' => $item_id, 'external_name' => $this->external_name]);
                        } else {
                            $old_item = Item::findOne(['id' => $item_id]);
                        }
                    } else {
                        $old_item = Item::findOne(['brand_id' => $item->brand_id, 'category_id' => $item->category_id, 'name' => $item->name, 'article' => $item->article]);
                    }

                    if ($old_item) {

                        $old_gift = $old_item->gift;
                        $old_new = $old_item->new;
                        
                        if ($this->external_name != '' && $old_item->external_manual == 1) {                            
                            $old_price = $old_item->price;
                            $old_status = $old_item->status;
                            
                            $old_item->attributes = $item->attributes;
                            
                            $old_item->price = $old_price;
                            $old_item->status = $old_status;
                            
                            $old_item->external_manual = 1;
                        } else {
                            $old_item->attributes = $item->attributes;
                        }

                        $old_item->gift = $old_gift;
                        $old_item->new = $old_new;
                        
                        if (!$old_item->save()) {
                            $error = $row['Наименование'] . ' ' . $row['Бренд'] . ' ' . $row['Модель'] . ' ' . $row['Артикул'] . ' not update';
                        } else {
                            $save_amount++;
                        }
                    } else {

                        $item->validate();

                        if (!$item->save()) {
                            $error = $row['Наименование'] . ' ' . $row['Бренд'] . ' ' . $row['Модель'] . ' ' . $row['Артикул'] . ' not saved';
                        } else {
                            $save_amount++;
                        }
                    }

                    //Фото
                    if ($error) {
                        $errors[] = $error;
                    } else {
                        if (!$old_item && $this->external_name != '') {
                            $photos = json_decode((string) $row['Фото'], true);
                            $image = null;
                            if (count($photos) > 0) {
                                foreach ($photos as $photo) {
                                    if ($this->max_number_of_uploaded_photos > 0 && $count_photos >= $this->max_number_of_uploaded_photos) {
                                        break;
                                    }
                                    if (!$image) {
                                        $image = self::savePhoto($photo, $item);
                                    } else {
                                        self::savePhoto($photo, $item);
                                    }
                                }
                            }
                            if (!$image) {

                                $item->delete();
                                $errors[] = $row['Наименование'] . ' ' . $row['Бренд'] . ' ' . $row['Модель'] . ' ' . $row['Артикул'] . ' фото не сохранено, элемент удален';
                                $save_amount--;
                            } else {
                                $item->updateAttributes(['image' => $image]);
                            }
                        }
                    }
                } else {
                    $errors[] = $row['Наименование'] . ' ' . $row['Бренд'] . ' ' . $row['Модель'] . ' ' . $row['Артикул'] . ' brand not find and not saved';
                }
            } else {
                $errors[] = $row['Наименование'] . ' ' . $row['Бренд'] . ' ' . $row['Модель'] . ' ' . $row['Артикул'] . ' category not find';
            }
        }
        $result['all_amount'] = count($rows);
        $result['save_amount'] = $save_amount;
        $result['errors'] = $errors;

        return $result;
    }

    //Добавление новых элементов, брендов
    public static function addItemsFromExcelFile($fileInput) {

        $array = Excel::import([
                    'fileName' => $fileInput,
        ]);
        $rows = $array['fileName'];
        $errors = [];
        $save_amount = 0;


        $categories = [];
        $_categories = Category::find()->all();
        foreach ($_categories as $_category) {
            $type = $_category->getFieldOptions('type');
            if ($type) {
                $categories[mb_strtolower($type)] = $_category;
            }
        }

        $brands = [];
        $_brands = Brand::find()->all();
        foreach ($_brands as $_brand) {
            $brands[mb_strtolower($_brand->title)] = $_brand;
        }

        foreach ($rows as $row) {
            $error = null;
            $category = $categories[mb_strtolower($row['Наименование'])];
            if ($category) {
                $brand = $brands[mb_strtolower($row['Бренд'])];
                if (!$brand) {
                    $brand = new Brand();
                    $brand->title = ucfirst(mb_strtolower($row['Бренд']));
                    $brand->save();
                    $brands[mb_strtolower($row['Бренд'])] = $brand;
                }

                if ($brand) {
                    $item_id = (int) trim($row['ID']);

                    $item = new Item();

                    $item->brand_id = $brand->id;
                    $item->category_id = $category->id;
                    $item->name = trim($row['Модель']);
                    $item->article = trim($row['Артикул']);
                    $item->base_price = trim($row['Закупочная цена']);
                    $item->price = trim($row['Цена']);
                    $item->status = 0;
                    $item->description = trim($row['Описание']);
                    $item->available = 1;

                    $item->data = new \stdClass();
                    //Доп.параметры
                    foreach (YmlModule::getAdditionalFields(true) as $field) {
                        if ($row[$field['header']]) {
                            $item->data->$field['attribute'] = trim($row[$field['header']]);
                        }
                    }

                    $old_item = null;
                    $old_item = Item::findOne(['brand_id' => $item->brand_id, 'category_id' => $item->category_id, 'name' => $item->name, 'article' => $item->article]);

                    if ($old_item) {
                        
                    } else {
                        if (!$item->save()) {
                            $errors[] = $row['Наименование'] . ' ' . $row['Бренд'] . ' ' . $row['Модель'] . ' ' . $row['Артикул'] . ' not saved';
                        } else {
                            $save_amount++;
                        }
                    }
                } else {
                    $errors[] = $row['Наименование'] . ' ' . $row['Бренд'] . ' ' . $row['Модель'] . ' ' . $row['Артикул'] . ' brand not find and not saved';
                }
            } else {
                $errors[] = $row['Наименование'] . ' ' . $row['Бренд'] . ' ' . $row['Модель'] . ' ' . $row['Артикул'] . ' category not find';
            }
        }
        $result['all_amount'] = count($rows);
        $result['save_amount'] = $save_amount;
        $result['errors'] = $errors;

        return $result;
    }

    //Только обновление существующих элементов
    public static function updateItemsFromExcelFile($fileInput) {

        $array = Excel::import([
                    'fileName' => $fileInput,
        ]);
        $rows = $array['fileName'];
        $errors = [];
        $save_amount = 0;
        foreach ($rows as $row) {

            $item = Item::findOne(['id' => trim($row['ID'])]);
            if ($item) {
                $item->base_price = trim($row['Закупочная цена']);
                $item->price = trim($row['Цена']);
                $item->status = trim($row['Статус']);
                $item->description = trim($row['Описание']);
                $item->available = 1;
                $item->data = new \stdClass();
                //Доп.параметры   *
                foreach (YmlModule::getAdditionalFields(true) as $field) {
                    if ($row[$field['header']]) {
                        $item->data->$field['attribute'] = trim($row[$field['header']]);
                    }
                }
                if (!$item->save()) {
                    $errors[] = $row['Наименование'] . ' ' . $row['Бренд'] . ' ' . $row['Модель'] . ' ' . $row['Артикул'] . ' not saved';
                }
            }
            $save_amount++;
        }


        $result['all_amount'] = count($rows);
        $result['save_amount'] = $save_amount;
        $result['errors'] = $errors;

        return $result;
    }

    //Добавление новых категорий, обновление старых
    public static function loadCategoriesFromExcelFile($fileInput) {

        $array = Excel::import([
                    'fileName' => $fileInput,
        ]);
        $rows = $array['fileName'];
        $errors = [];
        $save_amount = 0;

        $categories = [];
        $_categories = Category::find()->all();
        foreach ($_categories as $_category) {
            $categories[mb_strtolower($_category->title)] = $_category;
        }

        $catalog = $categories['catalog'];
        if ($catalog) {
            foreach ($rows as $row) {
                $error = null;
                $category = $categories[mb_strtolower($row['Название'])];
                if (!$category) {
                    $category = new Category();
                    $category->title = ucfirst($row['Название']);


                    $category->order_num = $catalog->order_num;
                    $category->appendTo($catalog);
                    if ($category->save()) {
                        $category->setFieldOptions('type', ucfirst($row['Тип элемента']));
                        $category->save();
                        $categories[mb_strtolower($row['Название'])] = $category;
                        $save_amount++;
                    } else {
                        $error = $row['Название'] . ' ' . $row['Тип элемента'] . ' not saved';
                    }
                } else {
                    $category->setFieldOptions('type', ucfirst($row['Тип элемента']));
                    if ($category->save()) {
                        $save_amount++;
                    } else {
                        $error = $row['Название'] . ' ' . $row['Тип элемента'] . ' not updated';
                    }
                }
            }
        } else {
            $error = 'Catalog category is not found, create it';
        }
        $result['all_amount'] = count($rows);
        $result['save_amount'] = $save_amount;
        $result['errors'] = $errors;

        return $result;
    }

    //Добавление новых новостей, обновление старых
    public static function loadNewsFromExcelFile($fileInput) {

        $array = Excel::import([
                    'fileName' => $fileInput,
        ]);
        $rows = $array['fileName'];
        $errors = [];
        $save_amount = 0;

        $news = [];
        $_news = News::find()->all();
        foreach ($_news as $_new) {
            $news[mb_strtolower($_new->title)] = $_new;
        }


        foreach ($rows as $row) {
            $error = null;
            $new = $news[mb_strtolower($row['Название'])];
            if (!$new) {
                $new = new News();
                $new->title = $row['Название'];
                $new->time = strtotime($row['Дата']);
                $new->short = $row['Текст'];
                $new->text = $row['Текст'];

                if ($new->save()) {
                    $news[mb_strtolower($row['Название'])] = $new;
                    $save_amount++;
                } else {
                    $error = $row['Название'] . ' not saved';
                }
            } else {
                $new->time = strtotime($row['Дата']);
                $new->short = $row['Текст'];
                $new->text = $row['Текст'];
                if ($new->save()) {
                    $save_amount++;
                } else {
                    $error = $row['Название'] . ' not updated';
                }
            }
        }

        $result['all_amount'] = count($rows);
        $result['save_amount'] = $save_amount;
        $result['errors'] = $errors;

        return $result;
    }

    //Добавление пользователей
    public static function loadUsersFromExcelFile($fileInput) {

        $array = Excel::import([
                    'fileName' => $fileInput,
        ]);
        $rows = $array['fileName'];
        $errors = [];
        $save_amount = 0;

        $users = [];
        $_users = User::find()->all();
        foreach ($_users as $_user) {
            $users[mb_strtolower($_user->email)] = $_user;
        }


        foreach ($rows as $row) {
            $error = null;
            $user = $users[mb_strtolower($row['Email'])];
            if (!$user) {
                $user = new User();
                $user->email = $row['Email'];
                $user->password = substr(uniqid(md5(rand()), true), 0, 8);


                if ($user->save()) {
                    $users[mb_strtolower($row['Email'])] = $user;
                    if ($row['Имя'] != '') {
                        $user->data = ['name' => $row['Имя']];
                    } else {
                        $user->data = ['name' => $row['Фамилия']];
                    }
                    $user->save();

                    $save_amount++;
                } else {
                    $error = $row['Email'] . ' not saved';
                }
            }
        }

        $result['all_amount'] = count($rows);
        $result['save_amount'] = $save_amount;
        $result['errors'] = $errors;

        return $result;
    }

}
