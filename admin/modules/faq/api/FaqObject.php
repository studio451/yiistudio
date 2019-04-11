<?
namespace admin\modules\faq\api;

use admin\base\Api;
use yii\helpers\Url;

class FaqObject extends \admin\base\ApiObject
{
    public function getQuestion(){
        return LIVE_EDIT ? Api::liveEdit($this->model->question, $this->editLink) : $this->model->question;
    }

    public function getAnswer(){
        return LIVE_EDIT ? Api::liveEdit($this->model->answer, $this->editLink) : $this->model->answer;
    }

    public function  getEditLink(){
        return Url::to(['/admin/faq/a/edit', 'id' => $this->id]);
    }
}