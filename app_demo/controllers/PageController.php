<?

namespace app_demo\controllers;

class PageController extends \admin\base\api\Controller { 

    public function actionIndex() {
        return $this->render('index');
    }

}
