<?

namespace app_demo\controllers;

class PublicController extends \admin\components\APIController {

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex() {
        
        return $this->render('index');
    }

}
