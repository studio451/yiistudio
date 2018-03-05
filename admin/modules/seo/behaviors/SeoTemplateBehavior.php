<?

namespace admin\modules\seo\behaviors;

use Yii;
use yii\db\ActiveRecord;
use admin\modules\seo\models\SeoTemplateAssign;

class SeoTemplateBehavior extends \yii\base\Behavior {

    private $_model;

    public function getSeoTemplate() {
        return $this->owner->hasOne(SeoTemplateAssign::className(), ['item_id' => $this->owner->primaryKey()[0]])->where(['class' => get_class($this->owner)]);
    }

    public function get_SeoTemplate() {
        if (!$this->_model) {
            $this->_model = $this->owner->seoTemplate;
            if (!$this->_model) {
                $this->_model = new SeoTemplateAssign([
                    'class' => get_class($this->owner),
                    'item_id' => $this->owner->primaryKey
                ]);
            }
        }
        return $this->_model;
    }

    public function events() {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    public function afterInsert() {
        if ($this->_seoTemplate->load(Yii::$app->request->post())) {
            if (!$this->_seoTemplate->isEmpty()) {
                $this->_seoTemplate->save();
            }
        }
    }

    public function afterUpdate() {
        if ($this->_seoTemplate->load(Yii::$app->request->post())) {
            if (!$this->_seoTemplate->isEmpty()) {
                $this->_seoTemplate->save();                               
                
            } else {
                if (!$this->_seoTemplate->isNewRecord) {
                    $this->_seoTemplate->delete();
                }
            }
        }
    }

    public function afterDelete() {
        if (!$this->_seoTemplate->isNewRecord) {
            $this->_seoTemplate->delete();
        }
    }

}
