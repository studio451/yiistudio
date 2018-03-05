<?

namespace admin\widgets;

use Yii;
use yii\base\Widget;

class Sort extends Widget {

    public $sort;
    public $attributes;
    public $title = '';
    public $separator = '|';

    public function run() {
        $first = true;
        foreach ($this->attributes as $attribute) {
            $label = null;
            if (($direction = $this->sort->getAttributeOrder($attribute)) !== null) {
                if ($direction == SORT_DESC) {
                    $label = $this->sort->attributes[$attribute]['label'] . '<i class="fa">&nbsp</i><i class="fa fa-sort-desc"></i>';
                } else {
                    $label = $this->sort->attributes[$attribute]['label'] . '<i class="fa">&nbsp</i><i class="fa fa-sort-asc"></i>';
                }
            }
            $str = $this->sort->link($attribute, ['label' => $label,'rel' => 'nofollow']);
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

}
