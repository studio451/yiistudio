<?
namespace admin\controllers;

use yii\data\ActiveDataProvider;

use admin\models\api\LoginForm;

class LogsController extends \admin\base\admin\Controller
{
    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => LoginForm::find()->desc(),
        ]);

        return $this->render('index', [
            'data' => $data
        ]);
    }
}