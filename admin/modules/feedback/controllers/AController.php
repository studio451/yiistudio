<?
namespace admin\modules\feedback\controllers;

use Yii;
use yii\data\ActiveDataProvider;

use admin\models\Setting;
use admin\modules\feedback\models\Feedback;

class AController extends \admin\base\admin\Controller
{
    public $new = 0;
    public $noAnswer = 0;

    public function init()
    {
        parent::init();

        $this->new = Yii::$app->getModule('admin')->activeModules['feedback']->notice;
        $this->noAnswer = Feedback::find()->status(Feedback::STATUS_VIEW)->count();
    }

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Feedback::find()->status(Feedback::STATUS_NEW)->desc(),
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionNoanswer()
    {
        $data = new ActiveDataProvider([
            'query' => Feedback::find()->status(Feedback::STATUS_VIEW)->desc(),
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionAll()
    {
        $data = new ActiveDataProvider([
            'query' => Feedback::find()->desc(),
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionView($id)
    {
        $model = Feedback::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('admin', 'Запись не найдена'));
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        if($model->status == Feedback::STATUS_NEW){
            $model->status = Feedback::STATUS_VIEW;
            $model->update();
        }

        $postData = Yii::$app->request->post('Feedback');
        if($postData) {
            if(filter_var(Setting::get('contact_email'), FILTER_VALIDATE_EMAIL))
            {
                $model->answer_subject = filter_var($postData['answer_subject'], FILTER_SANITIZE_STRING);
                $model->answer_text = filter_var($postData['answer_text'], FILTER_SANITIZE_STRING);
                if($model->sendAnswer()){
                    $model->status = Feedback::STATUS_ANSWERED;
                    $model->save();
                    $this->flash('success', Yii::t('admin/feedback', 'Ответ успешно отправлен'));
                }
                else{
                    $this->flash('error', Yii::t('admin/feedback', 'Произошла ошибка при отправке письма'));
                }
            }
            else {
                $this->flash('error', Yii::t('admin/feedback', 'Пожалуйста, заполните "E-mail администратора" в настройках'));
            }

            return $this->refresh();
        }
        else {
            if(!$model->answer_text) {
                $model->answer_subject = Yii::t('admin/feedback', $this->module->settings['answerSubject']);
                if ($this->module->settings['answerHeader']) $model->answer_text = Yii::t('admin/feedback', $this->module->settings['answerHeader']) . " " . $model->name . ".\n";
                if ($this->module->settings['answerFooter']) $model->answer_text .= "\n\n" . Yii::t('admin/feedback', $this->module->settings['answerFooter']);
            }

            return $this->render('view', [
                'model' => $model
            ]);
        }
    }

    public function actionSetAnswer($id)
    {
        $model = Feedback::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('admin', 'Запись не найдена'));
        }
        else{
            $model->status = Feedback::STATUS_ANSWERED;
            if($model->update()) {
                $this->flash('success', Yii::t('admin/feedback', 'Запись обновлена'));
            }
            else{
                $this->flash('error', Yii::t('admin', 'Ошибка при обновлении записи. {0}', $model->formatErrors()));
            }
        }
        return $this->goBack();
    }

    public function actionDelete($id)
    {
        if(($model = Feedback::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('admin', 'Запись не найдена');
        }
        return $this->formatResponse(Yii::t('admin/feedback', 'Запись удалена'));
    }
}