<?

namespace admin\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;

class Menu extends \yii\widgets\Menu {

    public $linkActiveTemplate = '';
    
    protected function renderItem($item) {
        if (isset($item['url'])) {
            if($item['active'])
            {
                $template = ArrayHelper::getValue($item, 'template', $this->linkActiveTemplate);
            }else
            {
                $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);
            }
            return strtr($template, [
                '{url}' => Html::encode(Url::to($item['url'])),
                '{label}' => $item['label'],
            ]);
        } else {
            $template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);

            return strtr($template, [
                '{label}' => $item['label'],
            ]);
        }
    }

}
