<?

namespace app_demo\controllers;

class ContactController extends \admin\base\api\Controller { 

    public function actionIndex() {
        return $this->render('index');
    }

}
