<?
namespace admin\modules\file\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use admin\modules\seo\behaviors\SeoTextBehavior;
use admin\behaviors\SortableModel;

class File extends \admin\base\ActiveRecord
{
    public static function tableName()
    {
        return 'admin_module_files';
    }

    public function rules()
    {
        return [
            ['file', 'file'],
            ['title', 'required'],
            ['title', 'string', 'max' => 128],
            ['title', 'trim'],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('admin', 'Код может содержать символы 0-9, a-z и "-" (не более: 128).')],
            ['slug', 'default', 'value' => null],
            [['downloads', 'size'], 'integer'],
            ['time', 'default', 'value' => time()]
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('admin', 'Название'),
            'file' => Yii::t('admin', 'Файл'),
            'slug' => Yii::t('admin', 'Код')
        ];
    }

    public function behaviors()
    {
        return [
            SortableModel::className(),
            'seoText' => SeoTextBehavior::className(),
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true
            ]
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if(!$insert && $this->file !== $this->oldAttributes['file']){
                @unlink(Yii::getAlias('@webroot').$this->oldAttributes['file']);
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        @unlink(Yii::getAlias('@webroot').$this->file);
    }
}