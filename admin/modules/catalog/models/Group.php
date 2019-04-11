<?

namespace admin\modules\catalog\models;

use Yii;

class Group extends \admin\base\ActiveRecord {

    const STATUS_OFF = 0;
    const STATUS_ON = 1;
    
    public static function tableName() {
        return 'admin_module_catalog_group';
    }

    public function rules() {
        return [
            [['category_id','brand_id', 'name'], 'required'],
            [['category_id','brand_id', 'time'], 'integer'],
            ['time', 'default', 'value' => time()],
        ];
    }

    public function attributeLabels() {
        return [
            'time' => Yii::t('admin', 'Дата'),
        ];
    }    

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $attributes) {
        parent::afterSave($insert, $attributes);
    }  

    public function afterFind() {
        parent::afterFind();
    }

    public function getCategory() {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getItems() {
        return $this->hasMany(Item::className(), ['id' => 'item_id']);
    }
    
    public function afterDelete() {
        parent::afterDelete();
    }
}
