<?

namespace admin\modules\seo\models;

use Yii;

class SeoTemplate extends \admin\components\ActiveRecord {

    public static function tableName() {
        return 'admin_module_seo_template';
    }

    public function rules() {
        return [
            [['title', 'h1', 'description', 'keywords'], 'trim'],
            [['title', 'h1', 'description', 'keywords'], 'string', 'max' => 255],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('admin', 'Код может содержать символы 0-9, a-z и "-" (не более: 128).')],
            ['slug', 'required'],
            ['slug', 'unique']
        ];
    }

    public function attributeLabels() {
        return [
            'slug' => Yii::t('admin', 'Код'),
            'title' => Yii::t('admin/seo', 'SEO шаблон для title'),
            'h1' => Yii::t('admin/seo', 'SEO шаблон для h1'),
            'description' => Yii::t('admin/seo', 'SEO шаблон для description'),
            'keywords' => Yii::t('admin/seo', 'SEO шаблон для keywords'),
        ];
    }

    public function isEmpty() {
        return (!$this->text);
    }

    public static function parse($id, $attribute, $array = [], $with_tags = false) {
        $model = SeoTemplate::find()->where(['id' => $id])->one();
        $tag1 = '';
        $tag2 = '';
        if ($with_tags) {
            $tag1 = '<b>';
            $tag2 = '</b>';
        }
        if ($model) {
            $template = $model->{$attribute};

            foreach ($array as $key => $value) {

                $template = str_replace('##strtolower(' . $key . ')##', $tag1 . strtolower($value) . $tag2, $template);

                $template = str_replace('##' . $key . '##', $tag1 . $value . $tag2, $template);
            }
            return $template;
        }
        if ($with_tags) {
            return '<b>' . Yii::t('admin/seo', 'SEO шаблон не найден!') . '<b>';
        } else {
            return null;
        }
    }

}
