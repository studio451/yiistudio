<?
namespace admin\modules\feedback\api;

use Yii;
use admin\modules\feedback\models\Feedback as FeedbackModel;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use admin\widgets\ReCaptcha;


/**
 * Feedback module Api
 * @package admin\modules\feedback\api
 *
 * @method static string form(array $options = []) Returns fully worked standalone html form.
 * @method static array save(array $attributes) If you using your own form, this function will be useful for manual saving feedback's.
 */

class Feedback extends \admin\base\Api
{
    private $_defaultFormOptions = [
        'errorUrl' => '',
        'successUrl' => ''
    ];

    public function api_form($options = [])
    {
        $model = new FeedbackModel;
        $model->scenario = FeedbackModel::SCENARIO_FEEDBACK;
        
        $settings = Yii::$app->getModule('admin')->activeModules['feedback']->settings;
        $options = array_merge($this->_defaultFormOptions, $options);

        ob_start();
        $form = ActiveForm::begin([
            'enableClientValidation' => true,
            'action' => Url::to(['/feedback'])
        ]);

        echo Html::hiddenInput('errorUrl', $options['errorUrl'] ? $options['errorUrl'] : Url::current());
        echo Html::hiddenInput('successUrl', $options['successUrl'] ? $options['successUrl'] : Url::current());

        echo $form->field($model, 'name');
        echo $form->field($model, 'email')->input('email');

        if($settings['enablePhone']) echo $form->field($model, 'phone');
        if($settings['enableTitle']) echo $form->field($model, 'title');

        echo $form->field($model, 'text')->textarea();

        if($settings['enableCaptcha']) echo $form->field($model, 'reCaptcha')->widget(ReCaptcha::className());

        echo Html::submitButton(Yii::t('admin', 'Отправить'), ['class' => 'btn btn-primary']);
        ActiveForm::end();

        return ob_get_clean();
    }

    public function api_save($data)
    {
        $model = new FeedbackModel($data);
        $model->scenario = FeedbackModel::SCENARIO_FEEDBACK;
        
        if($model->save()){
            return ['result' => 'success'];
        } else {
            return ['result' => 'error', 'error' => $model->getErrors()];
        }
    }
}