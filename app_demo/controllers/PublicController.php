<?

namespace app_demo\controllers;

use Yii;

class PublicController extends \admin\components\APIController {

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex() {
        if (!\admin\AdminModule::INSTALLED) {
            if (Yii::$app->db->createCommand("SHOW TABLES LIKE 'admin_modules'")->query()->count() > 0 ? true : false) {
                return $this->redirect(['/admin/api/install/finish']);
            } else {
                return $this->redirect(['/admin/api/install']);
            }
        }
        return $this->render('index');
    }

}
