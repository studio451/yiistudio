<?
namespace admin\modules\file\api;

use Yii;
use admin\base\Api;
use yii\helpers\Html;
use yii\helpers\Url;

class FileObject extends \admin\base\ApiObject
{
    public $slug;
    public $downloads;
    public $time;

    public function getTitle(){
        return LIVE_EDIT ? Api::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function getFile(){
        return Url::to(['/download', 'slug' => $this->slug]);
    }
    public function getFilePath(){
        return Yii::getAlias('@webroot'). DIRECTORY_SEPARATOR . $this->model->file;
    }
    public function getLink(){
        return Html::a($this->title, $this->file, ['target' => '_blank']);
    }

    public function getBytes(){
        return $this->model->size;
    }

    public function getSize(){
        return Yii::$app->formatter->asShortSize($this->model->size, 2);
    }

    public function getDate(){
        return Yii::$app->formatter->asDatetime($this->time, 'medium');
    }

    public function updateCounters()
    {
        $this->model->updateCounters(['downloads' => 1]);        
    }  
    
    public function  getEditLink(){
        return Url::to(['/admin/file/a/edit', 'id' => $this->id]);
    }
}