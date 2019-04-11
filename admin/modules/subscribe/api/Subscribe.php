<?
namespace admin\modules\subscribe\api;

use Yii;
use admin\modules\subscribe\models\Subscriber;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/**
 * Subscribe module Api
 * @package admin\modules\subscribe\api
 *
 * @method static string form(array $options = []) Returns fully working standalone html form.
 * @method static array save(array $attributes) If you are using your own form, this function will be useful for manual saving of subscribers.
 */

class Subscribe extends \admin\base\Api
{
    private $_defaultFormOptions = [
        'errorUrl' => '',
        'successUrl' => ''
    ];

    public function api_form($options = [])
    {
        $model = new Subscriber;
        $options = array_merge($this->_defaultFormOptions, $options);

        ob_start();

        $form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'action' => Url::to(['/subscribe/send']),
            'layout' => 'inline'
        ]);
        echo Html::hiddenInput('errorUrl', $options['errorUrl'] ? $options['errorUrl'] : Url::current());
        echo Html::hiddenInput('successUrl', $options['successUrl'] ? $options['successUrl'] : Url::current());
        echo $form->field($model, 'email')->input('email', ['placeholder' => 'E-mail']);
        echo ' ';
        echo Html::submitButton(Yii::t('admin/subscribe', 'Отправить'), ['class' => 'btn btn-primary', 'id' => 'subscriber-send']);

        ActiveForm::end();

        return ob_get_clean();
    }

    public function api_save($email)
    {
        $model = new Subscriber(['email' => $email]);
        if($model->save()){
            return ['result' => 'success', 'error' => false];
        } else {
            return ['result' => 'error', 'error' => $model->getErrors()];
        }
    }
}