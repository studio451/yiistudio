<?
namespace admin\modules\carousel\api;

use admin\base\Api;
use yii\helpers\Url;

class CarouselObject extends \admin\base\ApiObject
{
    public $image;
    public $link;   
    
    public function getTitle(){
            return LIVE_EDIT ? Api::liveEdit($this->model->title, $this->editLink, 'div') : $this->model->title;
    }
    
    public function getText(){
            return LIVE_EDIT ? Api::liveEdit($this->model->text, $this->editLink, 'div') : $this->model->text; 
    }
    
    public function getEditLink(){
        return Url::to(['/admin/carousel/a/edit', 'id' => $this->id]);
    }
}