<?

namespace admin\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use admin\models\User;
use admin\helpers\WebConsole;

class UsersController extends \admin\components\Controller {

    public function actionIndex() {
        $data = new ActiveDataProvider([
            'query' => User::find()->desc(),
        ]);
        Yii::$app->user->setReturnUrl(['/admin/users']);

        return $this->render('index', [
                    'data' => $data
        ]);
    }

    public function actionCreate() {
        $model = new User();
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                if ($model->save()) {
                    
                    Yii::$app->authManager->revokeAll($model->primaryKey);
                    if (Yii::$app->request->post('roles')) {
                        foreach (Yii::$app->request->post('roles') as $role) {
                            $new_role = Yii::$app->authManager->getRole($role);
                            Yii::$app->authManager->assign($new_role, $model->primaryKey);
                        }
                    }
                    
                    $this->flash('success', Yii::t('admin', 'Пользователь создан'));
                    return $this->redirect(['/admin/users']);
                } else {
                    $this->flash('error', Yii::t('admin', 'Ошибка. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        } else {
            $roles = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');
            unset($roles['User']);
            return $this->render('create', [
                        'model' => $model,
                        'roles' => $roles,
                        'user_permit' => [],
            ]);
        }
    }

    public function actionEdit($id) {
        $model = User::findOne($id);

        $userFormClass = '\\' . APP_NAME . '\models\UserForm';
        $userForm = new $userFormClass();
        
        if ($model === null) {
            $this->flash('error', Yii::t('admin', 'Запись не найдена'));
            return $this->redirect(['/admin/users']);
        }

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                if ($model->save()) {
                    Yii::$app->authManager->revokeAll($model->primaryKey);
                    if (Yii::$app->request->post('roles')) {
                        foreach (Yii::$app->request->post('roles') as $role) {
                            $new_role = Yii::$app->authManager->getRole($role);
                            Yii::$app->authManager->assign($new_role, $model->primaryKey);
                        }
                    }

                    $this->flash('success', Yii::t('admin', 'Пользователь обновлен'));
                } else {
                    $this->flash('error', Yii::t('admin', 'Ошибка при обновлении записи. {0}', $model->formatErrors()));
                }
                return $this->refresh();
            }
        } else {

            $roles = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');
            unset($roles['User']);
            $user_permit = array_keys(Yii::$app->authManager->getRolesByUser($id));

            return $this->render('edit', [
                        'model' => $model,
                        'roles' => $roles,
                        'user_permit' => $user_permit,
                        'userForm' => $userForm
            ]);
        }
    }

    public function actionDelete($id) {
        if (DEMO) {
            $this->flash('warning', Yii::t('admin', 'Недоступно в демо-версии!'));
            return $this->back();
        }
        if (($model = User::findOne($id))) {
            $model->delete();
        } else {
            $this->error = Yii::t('admin', 'Запись не найдена');
        }
        return $this->formatResponse(Yii::t('admin', 'Пользователь удален'));
    }

    public function actionRbacInit() {

        $result =  WebConsole::rbacInit(Yii::$app->user->identity->id);;

        return $this->formatResponse($result, true, true);
    }
    
    public function actionLogin($id) {
        if (($model = User::findOne($id))) {
            Yii::$app->user->login($model, 0);
             return $this->redirect(['/']);
        } else {
            $this->error = Yii::t('admin', 'Запись не найдена');
        }
    }
    
    public function actionData($id) {

        $model = User::findOne($id);

        if ($model === null) {
            $this->flash('error', Yii::t('admin', 'Запись не найдена'));
            return $this->redirect(['/admin/users/a/edit', 'id' => $model->id]);
        }

        if (is_array(Yii::$app->request->post('data'))) {
            $model->data = Yii::$app->request->post('data');
            if ($model->save()) {
                $this->flash('success', Yii::t('admin', 'Дополнительные данные пользователя обновлены'));
                return $this->redirect(['/admin/users/edit', 'id' => $model->id]);
            }
        }
        $this->flash('error', Yii::t('admin', 'Ошибка при обновлении записи. {0}', $model->formatErrors()));
        return $this->redirect(['/admin/users/a/edit', 'id' => $model->id]);
    }

}
