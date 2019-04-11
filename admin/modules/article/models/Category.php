<?
namespace admin\modules\article\models;

class Category extends \admin\base\CategoryModel
{
    public static function tableName()
    {
        return 'admin_module_article_categories';
    }

    public function getItems()
    {
        return $this->hasMany(Item::className(), ['category_id' => 'id'])->sortDate();
    }

    public function afterDelete()
    {
        parent::afterDelete();

        foreach ($this->getItems()->all() as $item) {
            $item->delete();
        }
    }
    

}