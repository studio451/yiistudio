<?
namespace admin\modules\block\api;

use Yii;
use admin\components\API;
use yii\helpers\Html;
use yii\helpers\Url;

class BlockObject extends \admin\components\ApiObject
{
    public $slug;

    public function getText(){
        if($this->model->isNewRecord){
            return $this->createLink;
        } else {
            return LIVE_EDIT ? API::liveEdit($this->model->text, $this->editLink, 'div') : $this->model->text;
        }
    }

    public function getEditLink(){
        return Url::to(['/admin/block/a/edit', 'id' => $this->id]);
    }

    public function getCreateLink(){
        return Html::a(Yii::t('admin/block', 'Создать HTML-блок'), ['/admin/block/a/create', 'slug' => $this->slug], ['target' => '_blank']);
    }
}