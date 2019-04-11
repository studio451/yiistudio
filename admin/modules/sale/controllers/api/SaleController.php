<?

namespace admin\modules\sale\controllers\api;

use Yii;
use admin\modules\sale\api\Sale;

class SaleController extends \admin\base\api\Controller {

    public function actionIndex($slug = '', $tag = null) {
        if ($slug) {
            $sale = Sale::get($slug);
            if (!$sale) {
                throw new \yii\web\NotFoundHttpException(Yii::t('admin/sale','Акция не найдена'));
            }
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('@admin/modules/sale/views/api/sale/item', [
                            'sale' => $sale
                ]);
            } else {
                return $this->render('@admin/modules/sale/views/api/sale/item', [
                            'sale' => $sale
                ]);
            }
        } else {
            return $this->render('@admin/modules/sale/views/api/sale/index', [
                        'sale' => Sale::items(['tags' => $tag, 'pagination' => [
                                'defaultPageSize' => 10,
                                'forcePageParam' => false,
                                'pageSizeParam' => false,
                                'pageParam' => 'page',
                                'route' => 'sale/',
                            ],
                        ])
            ]);
        }
    }

}
