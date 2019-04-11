<?

namespace admin\modules\gallery\controllers;

use admin\modules\gallery\models\Category;

class AController extends \admin\base\admin\CategoryController {

    public $categoryClass = 'admin\modules\gallery\models\Category';
    public $moduleName = 'gallery';
    public $viewRoute = '/a/photos';

    public function actionPhotos($id) {
        if (!($model = Category::findOne($id))) {
            return $this->redirect(['/admin/' . $this->module->id]);
        }

        return $this->render('photos', [
                    'model' => $model,
        ]);
    }

}
