<?

namespace admin\modules\catalog\controllers\api;

use Yii;
use admin\modules\catalog\api\Catalog;
use yii\web\NotFoundHttpException;
use yii\data\Sort;

class BrandController extends \admin\base\api\Controller {

    public function actionIndex($slug = '', $page = 1, $pageSize = 36) {
        if ($slug) {
            $showDescription = true;
            if (!empty(Yii::$app->request->get('page'))) {
                $showDescription = false;
            }

            $filterFormClass = '\\' . APP_NAME . '\models\FilterForm';
            $filterForm = new $filterFormClass();
            $brand = Catalog::brand($slug);

            if (!$brand) {
                throw new NotFoundHttpException('Shop brand not found.');
            }
            $filters = null;
            if ($filterForm->load(Yii::$app->request->get()) && $filterForm->validate()) {
                $filters = $filterForm->parse();
            }

            $defaultOrder = ['price' => SORT_ASC];
            if (Yii::$app->session->has('sort')) {
                $defaultOrder = Yii::$app->session->get('sort');
            }

            $sort = new Sort([
                'attributes' => [
                    'price' => ['label' => Yii::t('admin/catalog', ' цене')],
                    'time' => ['label' => Yii::t('admin/catalog', 'дате')],
                    'title' => ['label' => Yii::t('admin/catalog', 'названию')]
                ],
                'route' => 'brand/',
                'defaultOrder' => $defaultOrder
            ]);

            $pagination = [
                'defaultPageSize' => 36,
                'pageSize' => $pageSize,
                'pageSizeLimit' => [36, 144],
                'forcePageParam' => false,
                'pageSizeParam' => 'pageSize',
                'pageParam' => 'page',
                'route' => 'brand/',
            ];


            Yii::$app->session->set('sort', $sort->orders);

            $addToCartFormClass = '\\' . APP_NAME . '\models\AddToCartForm';
            return $this->render('@admin/modules/catalog/views/api/brand/brand', [
                        'brand' => $brand,
                        'groups' => $brand->groups([
                            'sort' => $sort->orders,
                            'filters' => $filters,
                            'pagination' => $pagination,
                        ]),
                        'addToCartForm' => new $addToCartFormClass(),
                        'filterForm' => $filterForm,
                        'sort' => $sort,
                        'pagination' => $pagination,
                        'showDescription' => $showDescription,
            ]);
        } else {
            return $this->render('@admin/modules/catalog/views/api/brand/index');
        }
    }

}
