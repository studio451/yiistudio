<?

namespace app_demo\controllers;

class PageController extends \admin\components\APIController { 

    public function actionIndex() {
        return $this->render('index');
    }

}
