<?

namespace admin\controllers;

use Yii;

class AController extends \admin\components\Controller {

    public function actionIndex() {
        if (!INSTALLED) {
            if (Yii::$app->db->createCommand("SHOW TABLES LIKE 'admin_modules'")->query()->count() > 0 ? true : false) {
                return $this->redirect(['/admin/api/install/finish']);
            } else {
                return $this->redirect(['/admin/api/install']);
            }
        }
        return $this->render('index');
    }

}
