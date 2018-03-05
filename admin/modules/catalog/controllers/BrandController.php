<?

namespace admin\modules\catalog\controllers;

use Yii;
use admin\behaviors\StatusController;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use admin\components\Controller;
use admin\modules\catalog\models\Category;
use admin\modules\catalog\models\Brand;
use admin\helpers\Image;
use admin\behaviors\SortableDateController;
use yii\widgets\ActiveForm;

class BrandController extends Controller {

    public function behaviors() {
        return [
            [
                'class' => SortableDateController::className(),
                'model' => Brand::className(),
            ],
            [
                'class' => StatusController::className(),
                'model' => Brand::className()
            ]
        ];
    }

    public function actionIndex() {
        $data = new ActiveDataProvider([
            'query' => Brand::find()->orderBy(['title' => SORT_ASC]),
        ]);
        return $this->render('index', [
                    'data' => $data
        ]);
    }

    public function actionCreate() {

        $model = new Brand();

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
               
                if (isset($_FILES) && $this->module->settings['itemThumb']) {
                    $model->image = UploadedFile::getInstance($model, 'image');
                    if ($model->image && $model->validate(['image'])) {
                        $model->image = Image::upload($model->image, 'catalog');
                    } else {
                        $model->image = $model->oldAttributes['image'];
                    }
                }

                if ($model->save()) {
                    $this->flash('success', Yii::t('admin/catalog', 'Бренд создан'));
                    return $this->redirect(['/admin/' . $this->module->id . '/brand/edit/', 'id' => $model->primaryKey]);
                } else {
                    $this->flash('error', Yii::t('admin', 'Ошибка. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    public function actionEdit($id) {
        if (!($model = Brand::findOne($id))) {
            return $this->redirect(['/admin/' . $this->module->id . '/brand']);
        }

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {                
                if (isset($_FILES)) {
                    $model->image = UploadedFile::getInstance($model, 'image');
                    if ($model->image && $model->validate(['image'])) {
                        $model->image = Image::upload($model->image, 'brand');
                    } else {
                        $model->image = $model->oldAttributes['image'];
                    }
                }

                if ($model->save()) {
                    $this->flash('success', Yii::t('admin/catalog', 'Бренд обновлен'));
                    return $this->redirect(['/admin/' . $this->module->id . '/brand/edit', 'id' => $model->primaryKey]);
                } else {
                    $this->flash('error', Yii::t('admin', 'Ошибка при обновлении записи. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        } else {
            return $this->render('edit', [
                        'model' => $model,
            ]);
        }
    }   

    public function actionClearImage($id) {
        $model = Brand::findOne($id);

        if ($model === null) {
            $this->flash('error', Yii::t('admin', 'Запись не найдена'));
        } elseif ($model->image) {
            $model->image = '';
            if ($model->update()) {
                @unlink(Yii::getAlias('@webroot') . $model->image);
                $this->flash('success', Yii::t('admin', 'Изображение сброшено'));
            } else {
                $this->flash('error', Yii::t('admin', 'Ошибка при обновлении записи. {0}', $model->formatErrors()));
            }
        }
        return $this->back();
    }

    public function actionDelete($id) {
        if (($model = Brand::findOne($id))) {
            $model->delete();
        } else {
            $this->error = Yii::t('admin', 'Запись не найдена');
        }
        return $this->formatResponse(Yii::t('admin/catalog', 'Бренд удален'));
    }

    public function actionUp($id, $category_id) {
        return $this->move($id, 'up', ['category_id' => $category_id]);
    }

    public function actionDown($id, $category_id) {
        return $this->move($id, 'down', ['category_id' => $category_id]);
    }

    public function actionOn($id) {
        return $this->changeStatus($id, Brand::STATUS_ON);
    }

    public function actionOff($id) {
        return $this->changeStatus($id, Brand::STATUS_OFF);
    }
}
