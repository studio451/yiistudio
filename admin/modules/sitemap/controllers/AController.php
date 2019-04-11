<?

namespace admin\modules\sitemap\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;

use admin\modules\sitemap\models\Sitemap;
use admin\modules\sitemap\helpers\WebConsole;

class AController extends \admin\base\admin\Controller {

    public function actionIndex() {
        $data = new ActiveDataProvider([
            'query' => Sitemap::find(),
        ]);
        return $this->render('index', [
                    'data' => $data
        ]);
    }

    public function actionCreate() {
        $model = new Sitemap();

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                if ($model->save()) {
                    $this->flash('success', Yii::t('admin/sitemap', 'Класс для создания карты сайта создан'));
                    return $this->redirect(['/admin/' . $this->module->id]);
                } else {
                    $this->flash('error', Yii::t('admin', 'Ошибка. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        } else {
            $model->class = '\admin\modules\catalog\models\Item';
            $model->priority = '0.8';
            return $this->render('create', [
                        'model' => $model
            ]);
        }
    }

    public function actionEdit($id) {
        $model = Sitemap::findOne($id);


        if ($model === null) {
            $this->flash('error', Yii::t('admin', 'Запись не найдена'));
            return $this->redirect(['/admin/' . $this->module->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                if ($model->save()) {
                    $this->flash('success', Yii::t('admin/sitemap', 'Класс для создания карты сайта обновлен'));
                } else {
                    $this->flash('error', Yii::t('admin', 'Ошибка при обновлении записи. {0}', $model->formatErrors()));
                }
                return $this->refresh();
            }
        } else {
            return $this->render('edit', [
                        'model' => $model,
            ]);
        }
    }

    public function actionDelete($id) {
        if (($model = Sitemap::findOne($id))) {
            $model->delete();
        } else {
            $this->error = Yii::t('admin', 'Запись не найдена');
        }
        return $this->formatResponse(Yii::t('admin/sitemap', 'Класс для создания карты сайта удален'));
    }

    public function actionGenerate() {

        $result = WebConsole::sitemapGenerate();

        return $this->formatResponse($result, true, true);
    }

}
