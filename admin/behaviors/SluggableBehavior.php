<?

namespace admin\behaviors;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class SluggableBehavior extends \yii\behaviors\SluggableBehavior {

    /**
     * @var string
     */
    public $slugAttribute = 'slug';

    /**
     * @var string|array
     */
    public $attribute = 'title';

    /**
     * @var bool
     */
    public $ensureUnique = true;

    /**
     * @var string
     */
    public $replacement = '_';

    /**
     * @var bool
     */
    public $lowercase = true;

    /**
     * @var bool
     */
    public $immutable = true;

    /**
     * @var bool
     */
    private $slugIsEmpty = false;

    /**
     * @inheritdoc
     * @param ActiveRecord $owner
     */
    public function attach($owner) {
        $this->attribute = (array) $this->attribute;
        $primaryKey = $owner->primaryKey();
        $primaryKey = is_array($primaryKey) ? array_shift($primaryKey) : $primaryKey;
        if (in_array($primaryKey, $this->attribute, true) && $owner->getIsNewRecord()) {
            $this->attributes[ActiveRecord::EVENT_AFTER_INSERT] = $this->slugAttribute;
        }

        parent::attach($owner);
    }

    /**
     * @inheritdoc
     */
    protected function getValue($event) {
        /* @var $owner ActiveRecord */
        $owner = $this->owner;

        if (!empty($owner->{$this->slugAttribute}) && !$this->slugIsEmpty && $this->immutable) {
            $slug = $owner->{$this->slugAttribute};
        } else {
            if ($owner->getIsNewRecord()) {
                $this->slugIsEmpty = true;
            }
            if ($this->attribute !== null) {
                $attributes = $this->attribute;

                $slugParts = array_map(function ($attribute) {
                    return ArrayHelper::getValue($this->owner, $attribute);
                }, $attributes);

                $slug = $this->slugify(implode($this->replacement, $slugParts), $this->replacement, $this->lowercase);

                if (!$owner->getIsNewRecord() && $this->slugIsEmpty) {
                    $owner->{$this->slugAttribute} = $slug;
                    $owner->save(false, [$this->slugAttribute]);
                }
            } else {
                $slug = parent::getValue($event);
            }
        }

        if ($this->ensureUnique) {
            $baseSlug = $slug;
            $iteration = 0;
            while (!$this->validateSlug($slug)) {
                $iteration++;
                $slug = $this->generateUniqueSlug($baseSlug, $iteration);
            }
        }

        return $slug;
    }

    /**
     * @param string $str
     * @param string $replacement
     * @param bool $lowercase
     *
     * @return string
     */
    public static function slugify($str, $replacement = '_', $lowercase = true) {

        // ГОСТ 7.79B
        $transliteration = array(
            'А' => 'A', 'а' => 'a',
            'Б' => 'B', 'б' => 'b',
            'В' => 'V', 'в' => 'v',
            'Г' => 'G', 'г' => 'g',
            'Д' => 'D', 'д' => 'd',
            'Е' => 'E', 'е' => 'e',
            'Ё' => 'Yo', 'ё' => 'yo',
            'Ж' => 'Zh', 'ж' => 'zh',
            'З' => 'Z', 'з' => 'z',
            'И' => 'I', 'и' => 'i',
            'Й' => 'J', 'й' => 'j',
            'К' => 'K', 'к' => 'k',
            'Л' => 'L', 'л' => 'l',
            'М' => 'M', 'м' => 'm',
            'Н' => "N", 'н' => 'n',
            'О' => 'O', 'о' => 'o',
            'П' => 'P', 'п' => 'p',
            'Р' => 'R', 'р' => 'r',
            'С' => 'S', 'с' => 's',
            'Т' => 'T', 'т' => 't',
            'У' => 'U', 'у' => 'u',
            'Ф' => 'F', 'ф' => 'f',
            'Х' => 'H', 'х' => 'h',
            'Ц' => 'Cz', 'ц' => 'cz',
            'Ч' => 'Ch', 'ч' => 'ch',
            'Ш' => 'Sh', 'ш' => 'sh',
            'Щ' => 'Shh', 'щ' => 'shh',
            'Ъ' => 'ʺ', 'ъ' => 'ʺ',
            'Ы' => 'Y`', 'ы' => 'y`',
            'Ь' => '', 'ь' => '',
            'Э' => 'E`', 'э' => 'e`',
            'Ю' => 'Yu', 'ю' => 'yu',
            'Я' => 'Ya', 'я' => 'ya',
            '№' => '#', 'Ӏ' => '‡',
            '’' => '`', 'ˮ' => '¨',
        );

        $str = strtr($str, $transliteration);
        if ($lowercase) {
            $str = strtolower($str);
        }
        $str = preg_replace('/[^\s0-9a-z\-]/', '', $str);
        $str = preg_replace('|([-]+)|s', $replacement, $str);
        $str = str_replace(' ', $replacement, $str);
        $str = trim($str, $replacement);

        return $str;
    }

    /**
     * @param string $baseSlug
     * @param int $iteration
     *
     * @return string
     */
    protected function generateUniqueSlug($baseSlug, $iteration) {
        return is_callable($this->uniqueSlugGenerator) ? call_user_func($this->uniqueSlugGenerator, $baseSlug, $iteration, $this->owner) : $baseSlug . $this->replacement . ($iteration + 1);
    }

}
