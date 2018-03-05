<?
namespace admin\modules\gallery\api;

use Yii;
use admin\components\API;
use yii\helpers\Html;
use yii\helpers\Url;

class PhotoObject extends \admin\components\ApiObject
{
    public $image;
    public $description;
    public $rel;

    public function box($width, $height){
        $img = Html::img($this->thumb($width, $height));
        $a = Html::a($img, $this->image, [            
            'rel' => 'album-' . ($this->rel ? $this->rel : $this->model->item_id),
            'title' => $this->description,
            'data-caption' => $this->description,
        ]);
        return LIVE_EDIT ? API::liveEdit($a, $this->editLink) : $a;
    }

    public function getEditLink(){
        return Url::to(['/admin/gallery/a/photos', 'id' => $this->model->item_id]).'#photo-'.$this->id;
    }
}