<?
namespace admin\modules\seo\models;

class SeoTemplateAssign extends \admin\base\ActiveRecord
{
    public static function tableName()
    {
        return 'admin_module_seo_template_assign';
    }
       
    public function rules()
    {
        return [
            [['template_id', 'item_template_id'], 'safe'],
        ];
    }
    
    public function attributeLabels() {
        return [
            'template_id' => Yii::t('admin/seo', 'SEO шаблон для категорий'),
            'item_template_id' => Yii::t('admin/seo', 'SEO шаблон для элементов'),
        ];
    }
    
    public function isEmpty()
    {
        return (!$this->template_id && !$this->item_template_id);
    }
}