<?
namespace admin\modules\carousel\models;

use Yii;
use admin\behaviors\CacheFlush;
use admin\behaviors\SortableModel;

class Carousel extends \admin\base\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;
    const CACHE_KEY = 'admin_module_carousel';

    public static function tableName()
    {
        return 'admin_module_carousel';
    }

    public function rules()
    {
        return [
            ['image', 'image'],
            [['title', 'text', 'link'], 'trim'],
            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ON],
        ];
    }

    public function attributeLabels()
    {
        return [
            'image' => Yii::t('admin', 'Изображение'),
            'link' =>  Yii::t('admin', 'URL'),
            'title' => Yii::t('admin', 'Название'),
            'text' => Yii::t('admin', 'Текст'),
        ];
    }

    public function behaviors()
    {
        return [
            CacheFlush::className(),
            SortableModel::className()
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if(!$insert && $this->image != $this->oldAttributes['image'] && $this->oldAttributes['image']){
                @unlink(Yii::getAlias('@webroot').$this->oldAttributes['image']);
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        @unlink(Yii::getAlias('@webroot').$this->image);
    }
}