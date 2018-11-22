<?
namespace admin\modules\file\controllers\api;

use Yii;
use admin\modules\file\api\File;

class DownloadController extends \yii\web\Controller
{
    public function actionIndex($slug)
    {
        $file = File::get($slug);
        if($file){
            $file->updateCounters();
            Yii::$app->response->sendFile($file->filePath);
        }
        else{
            throw new \yii\web\NotFoundHttpException(Yii::t('admin/file', 'Файл не найден'));
        }
    }
}
