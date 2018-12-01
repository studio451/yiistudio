<?

namespace admin\modules\sitemap\models;

use Yii;

/**
 *
 * @property integer $id
 * @property string $title
 * @property string $data
 *
 */
class Sitemap extends \admin\components\ActiveRecord {

    /**
     * @var
     */
    public $priority;

   

    public static function tableName() {
        return 'admin_module_sitemap';
    }

    public function rules() {
        return [
            [['class'], 'required'],
            [['priority'], 'safe'],
        ];
    }

    public function attributeLabels() {
        return [
            'class' => Yii::t('admin/sitemap', 'Класс модели, например: \admin\modules\catalog\models\Item'),
            'priority' => Yii::t('admin/sitemap', 'Параметр "priority" - от 0.1 до 1'),                    
        ];
    }

    /**
     *
     */
    public function afterFind() {
        $data = json_decode($this->data);
        $this->priority = $data->priority;
        parent::afterFind();
    }

    /**
     * @return bool
     */
    public function beforeValidate() {
        $data = [
            'priority' => $this->priority,
        ];
        $this->data = json_encode($data);

        return parent::beforeValidate();
    }

}
