<?

namespace admin\widgets;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\web\View;

class GridSelectedRowsAction extends Widget {

    public $grid_id;
    public $action; //url      
    public $data = "{data:data}"; //post data
    public $pjax = false; //refresh pjax grid container if 'true', location.refresh() if 'false'
    public $func_arg = "";
    public $func_success = "";
    public $buttonOptions = [
        'class' => 'btn btn-primary',
        'id' => null,
        'title' => 'buttonTitle',
        'content' => 'content',
    ];

    public function init() {
        parent::init();

        if (empty($this->grid_id)) {
            throw new InvalidConfigException('Required `grid_id` param isn\'t set.');
        }

        if (empty($this->action)) {
            throw new InvalidConfigException('Required `action` param isn\'t set.');
        }

        if (empty($this->buttonOptions['id'])) {
            $this->buttonOptions['id'] = uniqid();
        }
    }

    public function run() {

        if ($this->func_arg == "") {
            echo '<button type="button" class="' . $this->buttonOptions['class'] . '" id="' . $this->buttonOptions['id'] . '" title="' . $this->buttonOptions['title'] . '">' . $this->buttonOptions['content'] . '</button>';
        }


        if ($this->func_arg == "") {
            $js[] = "$('#" . $this->buttonOptions['id'] . "').click(function () {";
        } else {
            $js[] = "function func_" . $this->buttonOptions['id'] . "(" . $this->func_arg . ") {"
                    . "";
        }

        $js[] = "var data = $('#" . $this->grid_id . "').yiiGridView('getSelectedRows');";
        $js[] = "if(!data.length){alert('". Yii::t('admin','Необходимо отметить элементы!') . "');return;}";
        $js[] = "$.post({
                url: '" . $this->action . "',
                data: " . $this->data . ",
                dataType: 'json',
                success: function (data) {
                    if (data.status === 'success')
                    {";
        if ($this->func_success == "") {
            if ($this->pjax) {
                $js[] = "$.pjax.reload({container: '#" . $this->grid_id . "-pjax'});";
            } else {
                $js[] = "location.reload();";
            }
        } else {
            $js[] = $this->func_success . ";";
        }

        $js[] = "} else
                    {
                        alert('Error, please try again!');
                    }                    
                },
            });";
        if ($this->func_arg == "") {
            $js[] = "});";
        } else {
            $js[] = "}";
        }

        $this->view->registerJs(implode(PHP_EOL, $js), View::POS_READY);
    }

}
