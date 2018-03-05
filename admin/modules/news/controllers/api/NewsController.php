<?

namespace admin\modules\news\controllers\api;

use admin\modules\news\api\News;

class NewsController extends \admin\components\APIController {

    public function actionIndex($slug = '', $tag = null) {
        if ($slug) {
            $news = News::get($slug);
            if (!$news) {
                throw new \yii\web\NotFoundHttpException(Yii::t('admin/news','Новость не найдена'));
            }

            return $this->render('@admin/modules/news/views/api/news/item', [
                        'news' => $news
            ]);
        } else {
            return $this->render('@admin/modules/news/views/api/news/index', [
                        'news' => News::items(['tags' => $tag, 'pagination' => [
                                'defaultPageSize' => 10,
                                'forcePageParam' => false,
                                'pageSizeParam' => false,
                                'pageParam' => 'page',
                                'route' => 'news/',
                            ],
                        ])
            ]);
        }
    }

}
