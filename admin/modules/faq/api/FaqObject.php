<?
namespace admin\modules\faq\api;

use admin\components\API;
use yii\helpers\Url;

class FaqObject extends \admin\components\ApiObject
{
    public function getQuestion(){
        return LIVE_EDIT ? API::liveEdit($this->model->question, $this->editLink) : $this->model->question;
    }

    public function getAnswer(){
        return LIVE_EDIT ? API::liveEdit($this->model->answer, $this->editLink) : $this->model->answer;
    }

    public function  getEditLink(){
        return Url::to(['/admin/faq/a/edit', 'id' => $this->id]);
    }
}