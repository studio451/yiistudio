<?

namespace admin\modules\catalog\controllers\api;

use Yii;
use yii\web\NotFoundHttpException;
use admin\modules\catalog\api\Catalog;
use yii\data\Sort;

class CatalogController extends \admin\components\APIController {

    public function actionIndex($slug = 'catalog', $page = 1, $pageSize = 36) {

        $showDescription = true;
        if (!empty(Yii::$app->request->get('page'))) {
            $showDescription = false;
        }
        $filterFormClass = '\\' . APP_NAME . '\models\FilterForm';
        $filterForm = new $filterFormClass();
        $category = Catalog::category($slug);

        $filters = null;
                
        if ($filterForm->load(Yii::$app->request->get()) && $filterForm->validate()) {
            $filters = $filterForm->parse();
        }

        if (!$category) {
            //Обработка ЧПУ названия категории по бренду
            $category = Catalog::category(implode('_', explode('_', $slug, -1)));

            if (!$category) {
                throw new NotFoundHttpException(Yii::t('admin/catalog', 'Категория не найдена.'));
            } else {
                $explode = explode('_', $slug);
                $brand = Catalog::brand($explode[count($explode) - 1]);
                if (!$brand) {
                    throw new NotFoundHttpException(Yii::t('admin/catalog', 'Категория не найдена.'));
                }
            }

            $filters['brand_id'] = $brand->id;
            $showDescription = false;
        }
        
        if(Yii::$app->request->get('tag') != null)
        {
            $filters['tag'] = Yii::$app->request->get('tag');
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
            'route' => 'catalog/',
            'defaultOrder' => $defaultOrder
        ]);

        $pagination = [
            'defaultPageSize' => 36,
            'pageSize' => $pageSize,
            'pageSizeLimit' => [36, 144],
            'forcePageParam' => false,
            'pageSizeParam' => 'pageSize',
            'pageParam' => 'page',
            'route' => 'catalog/',
        ];

        Yii::$app->session->set('sort', $sort->orders);

        $addToCartFormClass = '\\' . APP_NAME . '\models\AddToCartForm';
        return $this->render(Yii::$app->getModule('admin')->activeModules['catalog']->settings['viewFileCatalogIndex'], [
                    'category' => $category,
                    'brand' => $brand,
                    'groups' => $category->groups([
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
    }

    public function actionItem($category, $slug) {

        $category = Catalog::category($category);
        if (!$category) {
            throw new NotFoundHttpException(Yii::t('admin/catalog', 'Элемент не найден.'));
        }
        $item = Catalog::item($slug);
        if (!$item) {
            throw new NotFoundHttpException(Yii::t('admin/catalog', 'Элемент не найден.'));
        }

        $addToCartFormClass = '\\' . APP_NAME . '\models\AddToCartForm';
        return $this->render(Yii::$app->getModule('admin')->activeModules['catalog']->settings['viewFileCatalogItem'], [
                    'category' => $category,
                    'group' => $item->group,
                    'item' => $item,
                    'addToCartForm' => new $addToCartFormClass()
        ]);
    }

    public function actionSearch($text = '', $page = 1) {
        $text = filter_var($text, FILTER_SANITIZE_STRING);

        if (strlen($text) >= 2) {

            $category = Catalog::category();
            return $this->render(Yii::$app->getModule('admin')->activeModules['catalog']->settings['viewFileCatalogSearch'], [
                        'text' => $text,
                        'category' => $category,
                        'items' => Catalog::category()->items([
                            'where' => ['like', 'title', $text],
                            'pagination' => [
                                'defaultPageSize' => 24,
                                'forcePageParam' => false,
                                'pageSizeParam' => false,
                                'pageParam' => 'page',
                                'route' => 'catalog/search/',
                            ],
                        ])
            ]);
        } else {
            return $this->render(Yii::$app->getModule('admin')->activeModules['catalog']->settings['viewFileCatalogSearch'], [
                        'text' => $text,
                        'error' => Yii::t('admin/catalog', 'Запрос не должен быть менее 2 символов. Попробуйте, поискать еще.'),
            ]);
        }
    }

}
