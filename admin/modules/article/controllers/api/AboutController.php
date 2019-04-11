<?

namespace admin\modules\article\controllers\api;

use Yii;
use admin\modules\article\api\Article;

class AboutController extends \admin\base\api\Controller {

    public function actionIndex($slug = '', $tag = null, $page = 1) {
        if ($slug) {
            $item = Article::get($slug);
            if (!$item) {
                throw new NotFoundHttpException(Yii::t('admin/article', 'Статья не найдена.'));
            }

            return $this->render('@admin/modules/article/views/api/article/item', [
                        'item' => $item
            ]);
        } else {
            $category = Article::category('about');
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
    }

}
