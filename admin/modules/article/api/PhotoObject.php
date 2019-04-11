<?
namespace admin\modules\article\api;

use Yii;
use admin\base\Api;
use yii\helpers\Html;
use yii\helpers\Url;

class PhotoObject extends \admin\base\ApiObject
{
    public $image;
    public $description;

    public function box($width, $height){
        $img = Html::img($this->thumb($width, $height));
        $a = Html::a($img, $this->image, [
            'rel' => 'article-'.$this->model->id,
            'title' => $this->description,
            'data-caption' => $this->description,
        ]);
        return LIVE_EDIT ? Api::liveEdit($a, $this->editLink) : $a;
    }

    public function getEditLink(){
        return Url::to(['/admin/article/item/photos', 'id' => $this->model->id]).'#photo-'.$this->id;
    }
}