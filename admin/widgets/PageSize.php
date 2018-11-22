<?

namespace admin\widgets;

use Yii;
use yii\helpers\Html;
use yii\base\Widget;
use yii\web\Request;

class PageSize extends Widget {
   
    public $pagination;
    public $attributes;
    public $title = '';
    public $separator = '|';

    public function run() {
        $first = true;
        foreach ($this->attributes as $attribute) {
            if($this->pagination['pageSize'] == $attribute)
            {
                $str = $attribute;
            }else
            {
                $str = Html::a($attribute, $this->createUrl($attribute), ['title' => Yii::t('admin/helpers','Показывать по ' . $attribute),'rel' => 'nofollow']);
            }
            if ($first) {
                if($this->title)
                {
                    $str = $this->title . ' ' . $str;
                }
                echo $str;
                $first = false;
            } else {
                echo ' ' . $this->separator . ' ' . $str;
            }
        }
    }
    
    public function createUrl($attribute, $absolute = false)
    {

        $request = Yii::$app->getRequest();
        $params = $request instanceof Request ? $request->getQueryParams() : [];
        $params[$this->pagination['pageSizeParam']] = $attribute;
        $params[0] = Yii::$app->controller->getRoute();       
        if ($absolute) {
            return Yii::$app->getUrlManager()->createAbsoluteUrl($params);
        } else {
            return Yii::$app->getUrlManager()->createUrl($params);
        }
    }

}
