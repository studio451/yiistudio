<?
namespace admin\modules\gallery\models;

use admin\models\Photo;

class Category extends \admin\components\CategoryModel
{
    public static function tableName()
    {
        return 'admin_module_gallery_categories';
    }

    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), ['item_id' => 'id'])->where(['class' => self::className()])->sort();
    }

    public function afterDelete()
    {
        parent::afterDelete();

        foreach($this->getPhotos()->all() as $photo){
            $photo->delete();
        }
    }
}