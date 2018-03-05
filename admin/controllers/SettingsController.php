<?
namespace admin\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use admin\models\Setting;

class SettingsController extends \admin\components\Controller
{
    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Setting::find()->where(['>=', 'visibility', Yii::$app->user->can("SuperAdmin") ? Setting::VISIBLE_ROOT : Setting::VISIBLE_ALL]),
        ]);
        Yii::$app->user->setReturnUrl('/admin/settings');

        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate()
    {
        $model = new Setting;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if($model->save()){
                    $this->flash('success', Yii::t('admin', 'Настройка успешно создана'));
                    return $this->redirect('/admin/settings');
                }
                else{
                    $this->flash('error', Yii::t('admin', 'Ошибка. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    public function actionEdit($id)
    {
        $model = Setting::findOne($id);

        if($model === null || ($model->visibility < (Yii::$app->user->can("SuperAdmin") ? Setting::VISIBLE_ROOT : Setting::VISIBLE_ALL))){
            $this->flash('error', Yii::t('admin', 'Запись не найдена'));
            return $this->redirect(['/admin/settings']);
        }

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if($model->save()){
                    $this->flash('success', Yii::t('admin', 'Настройка обновлена'));
                }
                else{
                    $this->flash('error', Yii::t('admin', 'Ошибка при обновлении записи. {0}', $model->formatErrors()));
                }
                return $this->refresh();
            }
        }
        else {
            return $this->render('edit', [
                'model' => $model
            ]);
        }
    }

    public function actionDelete($id)
    {
        if(($model = Setting::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('admin', 'Запись не найдена');
        }
        return $this->formatResponse(Yii::t('admin', 'Настройка удалена'));
    }
    
    public function actionUpdateSettings() {
                
        if (Setting::updateSettings()) {
            $this->flash('success', Yii::t('admin', 'Настройки обновлены'));
        } else {
            $this->flash('error', Yii::t('admin', 'Ошибка при обновлении настроек'));
        }
        return $this->back();
    }
}