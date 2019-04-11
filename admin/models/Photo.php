<?
namespace admin\models;

use Yii;
use admin\behaviors\SortableModel;

class Photo extends \admin\base\ActiveRecord
{
    const PHOTO_MAX_WIDTH = 1900;
    

    
    const PHOTO_THUMB_WIDTH = 90;
    const PHOTO_THUMB_HEIGHT = 90;

    public static function tableName()
    {
        return 'admin_photos';
    }

    public function rules()
    {
        return [
            [['class', 'item_id'], 'required'],
            ['item_id', 'integer'],
            ['image', 'image'],
            ['description', 'trim']
        ];
    }

    public function behaviors()
    {
        return [
            SortableModel::className()
        ];
    }

    public function afterDelete()
    {
        parent::afterDelete();

        @unlink(Yii::getAlias('@webroot').$this->image);
    }
}