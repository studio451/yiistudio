<?
namespace admin\models;

class Tag extends \admin\components\ActiveRecord
{
    public static function tableName()
    {
        return 'admin_tags';
    }

    public function rules()
    {
        return [
            ['name', 'required'],
            ['frequency', 'integer'],
            ['name', 'string', 'max' => 64],
        ];
    }
}