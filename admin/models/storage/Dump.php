<?

namespace admin\models\storage;

use Yii;
use yii\base\Model;

/**
 * Class Dump
 *
 */
class Dump extends Model {

    /**
     * @var bool
     */
    public $isArchive = false;

    /**
     * @var bool
     */
    public $schemaOnly = false;

    /**
     * @var bool
     */
    public $preset = null;

    /**
     * @var array
     */
    protected $customOptions;

    /**
     * Dump constructor.
     *
     * @param array $projectList
     * @param array $customOptions
     * @param array $config
     */
    public function __construct(array $customOptions = [], array $config = []) {
        $this->customOptions = $customOptions;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['isArchive', 'schemaOnly'], 'boolean'],
            ['preset', 'in', 'range' => array_keys($this->customOptions), 'skipOnEmpty' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'isArchive' => Yii::t('admin', 'Архив'),
            'schemaOnly' => Yii::t('admin', 'Только схема данных'),
            'preset' => Yii::t('admin', 'Особые настройки'),
        ];
    }

    /**
     * @return array
     */
    public function hasPresets() {
        return !empty($this->customOptions);
    }

    /**
     * @return array
     */
    public function getCustomOptions() {
        return $this->customOptions;
    }

    /**
     * @return array
     */
    public function makeDumpOptions() {
        return [
            'isArchive' => $this->isArchive,
            'schemaOnly' => $this->schemaOnly,
            'preset' => $this->preset ? $this->preset : false,
            'presetData' => $this->preset ? $this->customOptions[$this->preset] : '',
        ];
    }

}
