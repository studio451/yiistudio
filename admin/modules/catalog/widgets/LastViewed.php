<?

namespace admin\modules\catalog\widgets;

use Yii;
use yii\base\Widget;
use admin\modules\catalog\api\Catalog;

class LastViewed extends Widget {

    public $currentViewedSlug = null;
    public $lastViewedView = '@admin/modules/catalog/widgets/views/last_viewed/index';
    public $addToCartForm = null;
    public $items = [];

    public function init() {
        parent::init();

        if (Yii::$app->getSession()->has('lastViewedSlugs')) {
            $lastViewedSlugs = Yii::$app->getSession()->get('lastViewedSlugs');

            $lastViewedSlugs = json_decode($lastViewedSlugs, true);

            $newLastViewedSlugs = [];
            $count = 0;


            $count = 3;
            for ($i = 4; $i >= 0; $i--) {
                if ($lastViewedSlugs[$i]) {
                    if ($lastViewedSlugs[$i] != $this->currentViewedSlug && $count >= 0) {
                        $this->items[] = Catalog::item($lastViewedSlugs[$i]);
                        $newLastViewedSlugs[$count] = $lastViewedSlugs[$i];
                        $count--;
                    }
                }
            }
        }
        $newLastViewedSlugs[4] = $this->currentViewedSlug;

        Yii::$app->getSession()->set('lastViewedSlugs', json_encode($newLastViewedSlugs));
    }

    public function run() {

        return $this->render($this->lastViewedView, [
                    'items' => $this->items,
                    'addToCartForm' => $this->addToCartForm,
        ]);
    }

}
