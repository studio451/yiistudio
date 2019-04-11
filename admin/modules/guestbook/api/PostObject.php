<?
namespace admin\modules\guestbook\api;

use Yii;
use admin\base\Api;
use yii\helpers\Url;

class PostObject extends \admin\base\ApiObject
{
    public $image;
    public $time;

    public function getName(){
        return LIVE_EDIT ? Api::liveEdit($this->model->name, $this->editLink) : $this->model->name;
    }

    public function getTitle(){
        return LIVE_EDIT ? Api::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function getText(){
        return LIVE_EDIT ? Api::liveEdit($this->model->text, $this->editLink, 'div') : $this->model->text;
    }

    public function getAnswer(){
        return LIVE_EDIT ? Api::liveEdit($this->model->answer, $this->editLink, 'div') : $this->model->answer;
    }

    public function getDate(){
        return Yii::$app->formatter->asDatetime($this->time, 'medium');
    }

    public function getEditLink(){
        return Url::to(['/admin/guestbook/a/edit', 'id' => $this->id]);
    }
}