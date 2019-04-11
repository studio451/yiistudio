<?
namespace admin\models;

use Yii;
use admin\validators\EscapeValidator;

class SearchText extends \admin\base\ActiveRecord
{
    public static function tableName()
    {
        return 'admin_search_text';
    }

    public function rules()
    {
        return [
            [['text'], 'trim'],
            [['text'], 'string', 'max' => 255],
            [['text'], EscapeValidator::className()],
        ];
    }

    public function attributeLabels()
    {
        return [
            'text' => Yii::t('admin','Текст')
        ];
    }    
}