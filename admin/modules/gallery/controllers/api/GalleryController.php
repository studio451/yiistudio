<?

namespace admin\modules\gallery\controllers\api;

use Yii;
use admin\modules\gallery\api\Gallery;

class GalleryController extends \yii\web\Controller {

    public function actionIndex($slug = '') {
        if ($slug) {
            $album = Gallery::category($slug);
            if (!$album) {
                throw new \yii\web\NotFoundHttpException(Yii::t('admin/gallery', 'Альбом не найден'));
            }

            return $this->render('@admin/modules/gallery/views/api/gallery/item', [
                        'album' => $album,
                        'photos' => $album->photos(['pagination' => [
                                'defaultPageSize' => 10,
                                'forcePageParam' => false,
                                'pageSizeParam' => false,
                                'pageParam' => 'page',
                                'route' => 'gallery/',
                    ]])
            ]);
        } else {
            return $this->render('@admin/modules/gallery/views/api/gallery/index');
        }
    }

}
