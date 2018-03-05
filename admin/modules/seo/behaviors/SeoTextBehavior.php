<?

namespace admin\modules\seo\behaviors;

use Yii;
use yii\db\ActiveRecord;
use admin\modules\seo\models\SeoText;

class SeoTextBehavior extends \yii\base\Behavior {

    private $_model;

    public function events() {
        if (APP_CONSOLE) {
            return [
                ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
            ];
        } else {
            return [
                ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
                ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
                ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
            ];
        }
    }

    public function getSeoText() {
        return $this->owner->hasOne(SeoText::className(), ['item_id' => $this->owner->primaryKey()[0]])->where(['class' => get_class($this->owner)]);
    }

    public function get_SeoText() {
        if (!$this->_model) {
            $this->_model = $this->owner->seoText;
            if (!$this->_model) {
                $this->_model = new SeoText([
                    'class' => get_class($this->owner),
                    'item_id' => $this->owner->primaryKey
                ]);
            }
        }

        return $this->_model;
    }

    public function afterInsert() {
        if ($this->_seoText->load(Yii::$app->request->post())) {
            if (!$this->_seoText->isEmpty()) {
                $this->_seoText->save();
            }
        }
    }

    public function afterUpdate() {
        if ($this->_seoText->load(Yii::$app->request->post())) {
            if (!$this->_seoText->isEmpty()) {
                $this->_seoText->save();
            } else {
                if ($this->_seoText->primaryKey) {
                    $this->_seoText->delete();
                }
            }
        }
    }

    public function afterDelete() {
        if (!$this->_seoText->isNewRecord) {
            $this->_seoText->delete();
        }
    }

}
