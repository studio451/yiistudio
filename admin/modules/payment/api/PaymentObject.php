<?

namespace admin\modules\payment\api;

use admin\base\Api;
use yii\helpers\Url;

class PaymentObject extends \admin\base\ApiObject {

    public $slug;
    public $description;

    public function getTitle() {
        return LIVE_EDIT ? Api::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function getEditLink() {
        return Url::to(['/admin/payment/a/edit', 'id' => $this->id]);
    }
}
