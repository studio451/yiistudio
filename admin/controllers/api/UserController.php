<?

namespace admin\controllers\api;

use Yii;
use admin\models\api\LoginForm;
use admin\models\api\PasswordResetRequestForm;
use admin\models\api\ResetPasswordForm;
use admin\models\Setting;

class UserController extends \yii\web\Controller {

    public $layout = 'public';
    public $enableCsrfValidation = false;

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin() {
        $model = new LoginForm;

        if (!Yii::$app->user->isGuest || ($model->load(Yii::$app->request->post()) && $model->login())) {
            return $this->goBack();
        } else {
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('@admin/views/api/user/login', [
                            'model' => $model,
                ]);
            } else {
                return $this->render('@admin/views/api/user/login', [
                            'model' => $model,
                ]);
            }
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout() {

        $returnUrl = Yii::$app->user->getReturnUrl();

        Yii::$app->user->logout();

        return Yii::$app->getResponse()->redirect($returnUrl);
    }

    /**
     * Registration user up.
     *
     * @return mixed
     */
    public function actionRegistration() {

        $registrationFormClass = '\\' . APP_NAME . '\models\RegistrationForm';
        $registrationForm = new $registrationFormClass();

        if ($registrationForm->load(Yii::$app->request->post())) {
            if ($user = $registrationForm->registration()) {
                if (Yii::$app->getUser()->login($user, 3600 * 24 * 30)) {
                    return $this->goBack();
                }
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax(Setting::get('viewFileRegistration'), [
                        'registrationForm' => $registrationForm,
            ]);
        } else {
            return $this->render(Setting::get('viewFileRegistration'), [
                        'registrationForm' => $registrationForm,
            ]);
        }
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->notifyUser()) {
                Yii::$app->session->setFlash('success', 'Проверьте вашу электронную почту для получения дальнейших инструкций по сбросу пароля');
            } else {
                Yii::$app->session->setFlash('error', 'К сожалению, мы не можем сбросить пароль по указанной электронной почте');
            }
        }

        return $this->render('@admin/views/api/user/requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Новый пароль сохранен');

            return $this->redirect(['/user/login']);
        }

        return $this->render('@admin/views/api/user/resetPassword', [
                    'model' => $model,
        ]);
    }

    public function actionLoginRegistration() {
        $loginForm = new LoginForm;

        $registrationFormClass = '\\' . APP_NAME . '\models\RegistrationForm';
        $registrationForm = new $registrationFormClass();

        if (!Yii::$app->user->isGuest) {
            return $this->goBack();
        } else {
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('@admin/views/api/user/login_registration', [
                            'loginForm' => $loginForm,
                            'registrationForm' => $registrationForm,
                ]);
            } else {
                return $this->render('@admin/views/api/user/login_registration', [
                            'loginForm' => $loginForm,
                            'registrationForm' => $registrationForm,
                ]);
            }
        }
    }

}
