<?

namespace app_demo\controllers;

class ContactController extends \admin\components\APIController { 

    public function actionIndex() {
        return $this->render('index');
    }

}
