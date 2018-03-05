<?

namespace admin\models\storage;

use Yii;
use yii\base\Model;

/**
 * Class Restore
 *
 */
class Restore extends Model
{
    /**
     * @var bool
     */
    public $initData = false;

    /**
     * @var bool
     */
    public $demoData = false;
    
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
     * @param array $customOptions
     * @param array $config
     */
    public function __construct(array $customOptions = [], array $config = [])
    {
        $this->customOptions = $customOptions;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [           
            [['initData', 'demoData'], 'boolean'],
            ['preset', 'in', 'range' => array_keys($this->customOptions), 'skipOnEmpty' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'initData' => Yii::t('admin', 'Добавить данные для инициализации'),
            'demoData' => Yii::t('admin', 'Добавить демо-данные'),
            'preset'  => Yii::t('admin', 'Особые настройки'),
        ];
    }

    /**
     * @return array
     */
    public function hasPresets()
    {
        return !empty($this->customOptions);
    }

    /**
     * @return array
     */
    public function getCustomOptions()
    {
        return $this->customOptions;
    }

    /**
     * @return array
     */
    public function makeRestoreOptions()
    {
        return [
            'preset' => $this->preset ? $this->preset : false,
            'presetData' => $this->preset ? $this->customOptions[$this->preset] : '',
        ];
    }    
}
