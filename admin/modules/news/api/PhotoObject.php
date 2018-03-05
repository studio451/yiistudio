<?
namespace admin\modules\news\api;

use Yii;
use admin\components\API;
use yii\helpers\Html;
use yii\helpers\Url;

class PhotoObject extends \admin\components\ApiObject
{
    public $image;
    public $description;

    public function box($width, $height){
        $img = Html::img($this->thumb($width, $height));
        $a = Html::a($img, $this->image, [
            'rel' => 'news-'.$this->model->item_id,
            'title' => $this->description,
            'data-caption' => $this->description,
        ]);
        return LIVE_EDIT ? API::liveEdit($a, $this->editLink) : $a;
    }

    public function getEditLink(){
        return Url::to(['/admin/news/a/photos', 'id' => $this->model->item_id]).'#photo-'.$this->id;
    }
}