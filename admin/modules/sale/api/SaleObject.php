<?

namespace admin\modules\sale\api;

use Yii;
use admin\components\API;
use admin\models\Photo;
use admin\modules\sale\models\Sale as SaleModel;
use yii\helpers\Url;

class SaleObject extends \admin\components\ApiObject {

    public $slug;
    public $image;
    public $views;
    public $time;

    private $_photos;
    
    public function getTitle() {
        return $this->model->title;
    }

    public function getShort() {
        return LIVE_EDIT ? API::liveEdit($this->model->short, $this->editLink) : $this->model->short;
    }

    public function getBigBanner() {
        return '<div class="banner" style="background-color:' . $this->model->banner_background_color . ';border-color:' . $this->model->banner_border_color . '">
                    <div class="banner-title">
                        ' . $this->model->banner_title . '
                    </div>
                <div class="banner-content">
		<div class="banner-content-text">
			' . $this->model->banner_content_text . '
		</div>
		<div class="banner-content-button">
			<a href="/sale/' . $this->model->slug . '">' . $this->model->banner_content_button . '</a>
		</div>
                </div>
            </div>';
    }

    public function getSmallBanner() {
        return '<div class="banner banner-small" style="background-color:' . $this->model->banner_background_color . ';border-color:' . $this->model->banner_border_color . '">
                    <div class="banner-title">
                        ' . $this->model->banner_title . '
                    </div>
                <div class="banner-content">
		<div class="banner-content-text">
			' . $this->model->banner_content_text . '
		</div>
		<div class="banner-content-button">
			<a href="/sale/' . $this->model->slug . '">' . $this->model->banner_content_button . '</a>
		</div>
                </div>
            </div>';
    }

    public function getText() {
        return LIVE_EDIT ? API::liveEdit($this->model->text, $this->editLink, 'div') : $this->model->text;
    }

    public function getTags() {
        return $this->model->tagsArray;
    }

    public function getDate() {
        return Yii::$app->formatter->asDate($this->time);
    }

    public function getEditLink() {
        return Url::to(['/admin/sale/a/edit', 'id' => $this->id]);
    }

    public function getPhotos()
    {
        if(!$this->_photos){
            $this->_photos = [];

            foreach(Photo::find()->where(['class' => SaleModel::className(), 'item_id' => $this->id])->sort()->all() as $model){
                $this->_photos[] = new PhotoObject($model);
            }
        }
        return $this->_photos;
    }
}
