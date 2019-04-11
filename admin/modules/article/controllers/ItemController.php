<?
namespace admin\modules\article\controllers;

use Yii;
use admin\behaviors\SortableDateController;
use admin\behaviors\StatusController;
use yii\web\UploadedFile;
use admin\modules\article\models\Category;
use admin\modules\article\models\Item;
use admin\helpers\Image;
use yii\widgets\ActiveForm;

class ItemController extends \admin\base\admin\Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => SortableDateController::className(),
                'model' => Item::className(),
            ],
            [
            'class' => StatusController::className(),
            'model' => Item::className()
            ]
        ];
    }

    public function actionIndex($id)
    {
        if(!($model = Category::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }


    public function actionCreate($id)
    {
        if(!($category = Category::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        $model = new Item;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else {
                $model->category_id = $category->primaryKey;

                if (isset($_FILES) && $this->module->settings['articleThumb']) {
                    $model->image = UploadedFile::getInstance($model, 'image');
                    if ($model->image && $model->validate(['image'])) {
                        $model->image = Image::upload($model->image, 'article');
                    } else {
                        $model->image = '';
                    }
                }

                if ($model->save()) {
                    $this->flash('success', Yii::t('admin/article', 'Статья создана'));
                    return $this->redirect(['/admin/'.$this->module->id.'/item/edit', 'id' => $model->primaryKey]);
                } else {
                    $this->flash('error', Yii::t('admin', 'Ошибка. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->render('create', [
                'model' => $model,
                'category' => $category,
            ]);
        }
    }

    public function actionEdit($id)
    {
        if(!($model = Item::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else {
                if (isset($_FILES) && $this->module->settings['articleThumb']) {
                    $model->image = UploadedFile::getInstance($model, 'image');
                    if ($model->image && $model->validate(['image'])) {
                        $model->image = Image::upload($model->image, 'article');
                    } else {
                        $model->image = $model->oldAttributes['image'];
                    }
                }

                if ($model->save()) {
                    $this->flash('success', Yii::t('admin/article', 'Статья обновлена'));
                    return $this->redirect(['/admin/'.$this->module->id.'/item/edit', 'id' => $model->primaryKey]);
                } else {
                    $this->flash('error', Yii::t('admin', 'Ошибка при обновлении записи. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->render('edit', [
                'model' => $model,
            ]);
        }
    }

    public function actionPhotos($id)
    {
        if(!($model = Item::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        return $this->render('photos', [
            'model' => $model,
        ]);
    }

    public function actionClearImage($id)
    {
        $model = Item::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('admin', 'Запись не найдена'));
        }
        elseif($model->image){
            $model->image = '';
            if($model->update()){
                $this->flash('success', Yii::t('admin', 'Изображение сброшено'));
            } else {
                $this->flash('error', Yii::t('admin', 'Ошибка при обновлении записи. {0}', $model->formatErrors()));
            }
        }
        return $this->goBack();
    }

    public function actionDelete($id)
    {
        if(($model = Item::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('admin', 'Запись не найдена');
        }
        return $this->formatResponse(Yii::t('admin/article', 'Статья удалена'));
    }

    public function actionUp($id, $category_id)
    {
        return $this->move($id, 'up', ['category_id' => $category_id]);
    }

    public function actionDown($id, $category_id)
    {
        return $this->move($id, 'down', ['category_id' => $category_id]);
    }

    public function actionOn($id)
    {
        return $this->changeStatus($id, Item::STATUS_ON);
    }

    public function actionOff($id)
    {
        return $this->changeStatus($id, Item::STATUS_OFF);
    }
}