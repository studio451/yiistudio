<?

namespace admin\controllers;

use admin\helpers\WebConsole;


class TranslateController extends \admin\base\admin\Controller
{    
    public function actionIndex() {
        return $this->render('index');
    }    
    
    public function actionMessageExtractAdmin() {

        $result = WebConsole::MessageExtractAdmin();

        return $this->formatResponse($result, true, true);
    }

    public function actionMessageExtractApp() {

        $result = WebConsole::MessageExtractApp();

        return $this->formatResponse($result, true, true);
    }
}
