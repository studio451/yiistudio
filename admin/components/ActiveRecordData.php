<?

namespace admin\components;

use Yii;
use yii\helpers\Html;

/**
 * Base active record class for admin models with data
 * @package admin\components
 */
class ActiveRecordData extends \admin\components\ActiveRecord {

    public function afterFind() {

        parent::afterFind();

        if ($this->data == 'null') {            
            $this->setDefaultData();
        } else {
            $this->data = json_decode($this->data, true);
        }
    }
    
    public function beforeValidate() {

        $this->data = json_encode($this->data);

        return parent::beforeValidate();
    }

//  Example override function
//  public function getDataSchema() {
//    return [
//            'param1' => ['title' => 'Param 1', 'value' => 'Param 1 default value'],
//            'param2' => ['title' => 'Param 2',
//                'value' => 'Param 1 default value',
//                'options' => [
//                    ['value' => 'Select option value 1', 'title' => 'Select option 1'],
//                    ['value' => 'Select option value 2', 'title' => 'Select option 2'],
//                    ['value' => 'Select option value 3', 'title' => 'Select option 3'],
//                    ['value' => 'Select option value 4', 'title' => 'Select option 4'],
//                    ['value' => 'Select option value 5', 'title' => 'Select option 5'],
//                ],
//            ]
//  }
//  End example   

    public function getDataSchema() {
        return [];
    }
    
    public function setDefaultData() {
        $data = [];
        foreach ($this->getDataSchema() as $key => $param) {
            $data[$key] = $param['value'];
        }
        $this->data = $data;
    }

    public function renderDataForm() {
        $className = \yii\helpers\StringHelper::basename(get_class($this));
        foreach ($this->getDataSchema() as $key => $param) {
            if (isset($this->data[$key])) {
                $value = $this->data[$key];
            }

            if (isset($param['title'])) {
                $title = $param['title'];
            } else {
                $title = $key;
            }

            if (isset($param['options'])) {
                echo '<div class="form-group">';
                echo '<label class="control-label">' . $title . '</label>';
                echo Html::dropDownList(
                        $className . '[data][' . $key . ']', $value, yii\helpers\ArrayHelper::map($param['options'], 'value', 'title'), ['class' => 'form-control']
                );
                echo '</div>';
            } else {
                if (!is_bool($value)) {
                    echo '<div class="form-group">';
                    echo '<label class="control-label">' . $title . '</label>';
                    echo Html::input('text', $className . '[data][' . $key . ']', $value, ['class' => 'form-control']);
                    echo '</div>';
                } else {
                    echo '<div class="checkbox">';
                    echo '<label class="control-label">';
                    echo Html::checkbox($className . '[data][' . $key . ']', $value, ['uncheck' => 0]) . ' ' . $title;
                    echo '</label>';
                    echo '</div>';
                }
            }
        }
    }

}
