<?

namespace admin\modules\payment\api;

use admin\components\API;
use yii\helpers\Url;

class PaymentObject extends \admin\components\ApiObject {

    public $slug;
    public $description;

    public function getTitle() {
        return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function getEditLink() {
        return Url::to(['/admin/payment/a/edit', 'id' => $this->id]);
    }
}
