<?

namespace admin\modules\article\controllers\api;

use Yii;
use admin\modules\article\api\Article;

class ArticleController extends \admin\base\api\Controller {

    public function actionIndex($slug, $tag = null, $page = 1) {
        $category = Article::category($slug);
        if (!$category) {
            throw new \yii\web\NotFoundHttpException(Yii::t('admin/article', 'Статьи не найдены.'));
        }

        return $this->render('@admin/modules/article/views/api/article/index', [
                    'category' => $category,
                    'items' => $category->items(['tags' => $tag, 'pagination' => [
                            'defaultPageSize' => 10,
                            'forcePageParam' => false,
                            'pageSizeParam' => false,
                            'pageParam' => 'page',
                            'route' => 'article/',
                ]])
        ]);
    }

    public function actionItem($category, $slug) {
        $category = Article::category($category);
        $item = Article::get($slug);
        if (!$item) {
            throw new NotFoundHttpException(Yii::t('admin/article', 'Статья не найдена.'));
        }
        if (Yii::$app->request->isAjax) {
             return $this->renderAjax('@admin/modules/article/views/api/article/item', [
                        'category' => $category,
                        'item' => $item
            ]);
        } else {
            return $this->render('@admin/modules/article/views/api/article/item', [
                        'category' => $category,
                        'item' => $item
            ]);
        }
    }

}
