<?

namespace admin\modules\news\models;

use Yii;
use admin\behaviors\SluggableBehavior;
use admin\modules\seo\behaviors\SeoTextBehavior;
use admin\behaviors\Taggable;
use admin\models\Photo;
use yii\helpers\StringHelper;

class News extends \admin\components\ActiveRecord {

    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    public static function tableName() {
        return 'admin_module_news';
    }

    public function rules() {
        return [
            [['text', 'title'], 'required'],
            [['title', 'short', 'text'], 'trim'],
            ['title', 'string', 'max' => 128],
            ['image', 'image'],
            [['views', 'time', 'status'], 'integer'],
            ['time', 'default', 'value' => time()],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('admin', 'Код может содержать символы 0-9, a-z и "-" (не более: 128).')],
            ['slug', 'default', 'value' => null],
            ['status', 'default', 'value' => self::STATUS_ON],
            ['tagNames', 'safe']
        ];
    }

    public function attributeLabels() {
        return [
            'title' => Yii::t('admin', 'Название'),
            'text' => Yii::t('admin', 'Текст'),
            'short' => Yii::t('admin', 'Краткий текст'),
            'image' => Yii::t('admin', 'Изображение'),
            'time' => Yii::t('admin', 'Дата'),
            'slug' => Yii::t('admin', 'Код'),
            'tagNames' => Yii::t('admin', 'Теги'),
        ];
    }

    public function behaviors() {
        return [
            'seoText' => SeoTextBehavior::className(),
            'taggabble' => Taggable::className(),
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true
            ],
        ];
    }

    public function getPhotos() {
        return $this->hasMany(Photo::className(), ['item_id' => 'id'])->where(['class' => self::className()])->sort();
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $settings = Yii::$app->getModule('admin')->activeModules['news']->settings;
            $this->short = StringHelper::truncate($settings['enableShort'] ? $this->short : strip_tags($this->text), $settings['shortMaxLength']);

            if (!$insert && $this->image != $this->oldAttributes['image'] && $this->oldAttributes['image']) {
                @unlink(Yii::getAlias('@webroot') . $this->oldAttributes['image']);
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterDelete() {
        parent::afterDelete();

        if ($this->image) {
            @unlink(Yii::getAlias('@webroot') . $this->image);
        }

        foreach ($this->getPhotos()->all() as $photo) {
            $photo->delete();
        }
    }
}
