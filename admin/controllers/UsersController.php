<?

namespace admin\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use admin\models\User;
use admin\helpers\WebConsole;
use admin\models\users\FilterForm;
use admin\behaviors\StatusController;
use admin\behaviors\SortableController;

class UsersController extends \admin\base\admin\Controller {

    public function beforeAction($action) {
        if (!parent::beforeAction($action))
            return false;

        //Отключение функциональности в демо-режиме
        if (DEMO === true) {

            if ($action->id == 'delete' ||
                    $action->id == 'rbac-init' ||
                    ($action->id == 'edit' && Yii::$app->request->isPost) ||
                    $action->id == 'delete-json' ||
                    $action->id == 'off') {
                $this->flash('warning', Yii::t('admin', 'Недоступно в демо-версии!'));
                $this->goBack();
                return false;
            }
        }
        return true;
    }

    public function behaviors() {
        return [
                [
                'class' => SortableController::className(),
                'model' => User::className(),
            ],
                [
                'class' => StatusController::className(),
                'model' => User::className()
            ]
        ];
    }

    public function actionIndex() {

        $filterForm = new FilterForm();
        $filters = [];
        if ($filterForm->load(Yii::$app->request->get()) && $filterForm->validate()) {
            $filters = $filterForm->parse($filters);
        }

        $query = User::find()->desc();
        if (array_key_exists('email', $filters)) {
            $query->andFilterWhere(['like', 'email', $filters['email']]);
            unset($filters['email']);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 50,]
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'filterForm' => $filterForm,
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
        if (($model = User::findOne($id))) {
            $model->delete();
        } else {
            $this->error = Yii::t('admin', 'Запись не найдена');
        }
        return $this->formatResponse(Yii::t('admin', 'Пользователь удален'));
    }

    public function actionRbacInit() {
        $result = WebConsole::rbacInit(Yii::$app->user->identity->id);
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
            return $this->redirect(['/admin/users/edit', 'id' => $model->id]);
        }

        if (is_array(Yii::$app->request->post('User'))) {
            if (is_array(Yii::$app->request->post('User')[data])) {
            $model->data = Yii::$app->request->post('User')[data];
            if ($model->save()) {
                $this->flash('success', Yii::t('admin', 'Дополнительные данные пользователя обновлены'));
                return $this->redirect(['/admin/users/edit', 'id' => $model->id]);
            }
            }
        }
        $this->flash('error', Yii::t('admin', 'Ошибка при обновлении записи. {0}', $model->formatErrors()));
        return $this->redirect(['/admin/users/edit', 'id' => $model->id]);
    }

    public function actionOn($id) {
        return $this->changeStatus($id, User::STATUS_ON);
    }

    public function actionOff($id) {
        return $this->changeStatus($id, User::STATUS_OFF);
    }

    public function actionDeleteJson() {

        $data = Yii::$app->request->post('data');
        if (isset($data)) {
            if (!is_array($data)) {
                return Json::encode([
                    'status' => 'error'
                ]);
                return;
            }
            foreach ($data as $item) {
                $model = User::findOne($item);
                if ($model) {
                    $model->delete();
                }
            }

            return Json::encode([
                'status' => 'success'
            ]);
        } else {
            return Json::encode([
                'status' => 'error'
            ]);
        }
    }

}
