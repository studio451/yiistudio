<?
namespace admin\modules\news\api;

use Yii;
use admin\components\API;
use admin\models\Photo;
use admin\modules\news\models\News as NewsModel;
use yii\helpers\Url;

class NewsObject extends \admin\components\ApiObject
{
    public $slug;
    public $image;
    public $views;
    public $time;

    private $_photos;

    public function getTitle(){
        return $this->model->title;
    }

    public function getShort(){
        return LIVE_EDIT ? API::liveEdit($this->model->short, $this->editLink) : $this->model->short;
    }

    public function getText(){
        return LIVE_EDIT ? API::liveEdit($this->model->text, $this->editLink, 'div') : $this->model->text;
    }

    public function getTags(){
        return $this->model->tagsArray;
    }

    public function getDate(){
        return Yii::$app->formatter->asDate($this->time);
    }

    public function getPhotos()
    {
        if(!$this->_photos){
            $this->_photos = [];

            foreach(Photo::find()->where(['class' => NewsModel::className(), 'item_id' => $this->id])->sort()->all() as $model){
                $this->_photos[] = new PhotoObject($model);
            }
        }
        return $this->_photos;
    }

    public function  getEditLink(){
        return Url::to(['/admin/news/a/edit', 'id' => $this->id]);
    }
}