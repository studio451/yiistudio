<?

namespace admin\modules\catalog\controllers;

use Yii;
use admin\behaviors\StatusController;
use yii\helpers\Html;
use admin\modules\catalog\models\Category;
use admin\modules\catalog\models\Item;
use admin\behaviors\SortableController;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use admin\modules\catalog\api\Catalog;
use admin\modules\catalog\models\FilterForm;
use admin\modules\catalog\models\Brand;
use admin\models\Setting;
use yii\helpers\Json;

class ItemController extends \admin\base\admin\Controller {

    public function behaviors() {
        return [
                [
                'class' => SortableController::className(),
                'model' => Item::className(),
            ],
                [
                'class' => StatusController::className(),
                'model' => Item::className()
            ]
        ];
    }

    public function actionIndex($id) {
        if (!($category = Category::findOne($id))) {
            return $this->redirect(['/admin/' . $this->module->id]);
        }

        $filterForm = new FilterForm();
        $filters = [];

        if (Yii::$app->session->has('filters')) {
            $filters = Yii::$app->session->get('filters');
        }

        if ($filterForm->load(Yii::$app->request->get()) && $filterForm->validate()) {
            $filters = $filterForm->parse($filters);
            Yii::$app->session->set('filters', $filters);
        }


        $flat = Category::flat($category->slug);
        $ids[] = $this->id;
        foreach ($flat as $item) {
            $ids[] = $item->id;
        }

        $query = Item::find()->with(['seoText', 'category'])->where(['in', 'category_id', $ids]);

        if (Yii::$app->getModule('admin')->activeModules['catalog']->settings['generateComplexTitle']) {
            $query = $query->orderBy(['brand_id' => SORT_ASC, 'name' => SORT_ASC]);
        } else {
            $query = $query->sort();
        }

        if (array_key_exists('status', $filters)) {
            $query->andFilterWhere(['=', 'status', (int) $filters['status']]);
            unset($filters['status']);
        }

        $query = Catalog::applyFiltersForItems($filters, $query);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 50,]
        ]);

        $brands[''] = Yii::t('admin', '(не выбрано)');
        $query = Brand::find();
        $subQuery2 = Item::find()->select('brand_id')->where(['in', 'category_id', $ids]);
        $_brands = $query->join('INNER JOIN', ['i' => $subQuery2], 'i.brand_id = ' . Brand::tableName() . '.id')->all();
        foreach ($_brands as $_brand) {
            $brands[$_brand->primaryKey] = $_brand->title;
        }

        return $this->render('index', [
                    'category_id' => $id,
                    'model' => $category,
                    'status' => ['' => Yii::t('admin/catalog', '(все)'), '0' => Yii::t('admin/catalog', 'не активные'), '1' => Yii::t('admin/catalog', 'активные')],
                    'brands' => $brands,
                    'dataProvider' => $dataProvider,
                    'filterForm' => $filterForm
        ]);
    }

    public function actionCreate($id) {
        if (!($category = Category::findOne($id))) {
            return $this->redirect(['/admin/' . $this->module->id]);
        }

        $model = new Item;

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                $model->category_id = $category->primaryKey;

                $model->data = Yii::$app->request->post('Data');

                if ($model->save()) {
                    $this->flash('success', Yii::t('admin/catalog', 'Новый элемент каталога создан. Проверьте цену, характеристики, активность элемента. Загрузите фото элемента. После загрузки фото нажмите кнопку Сохранить.'));
                    return $this->redirect(['/admin/' . $this->module->id . '/item/edit/', 'id' => $model->primaryKey]);
                } else {
                    $this->flash('error', Yii::t('admin', 'Ошибка. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        } else {

            $model->available = 1;
            $model->base_price = 0;
            $model->category_id = $id;

            return $this->render('create', [
                        'model' => $model,
                        'category' => $category,
                        'dataForm' => $this->generateForm($category->fields)
            ]);
        }
    }

    public function actionEdit($id) {
        if (!($model = Item::findOne($id))) {
            return $this->redirect(['/admin/' . $this->module->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                $model->data = Yii::$app->request->post('Data');

                if ($model->save()) {
                    $this->flash('success', Yii::t('admin/catalog', 'Элемент обновлен'));
                    return $this->redirect(['/admin/' . $this->module->id . '/item', 'id' => $model->category_id]);
                } else {
                    $this->flash('error', Yii::t('admin', 'Ошибка при обновлении записи. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        } else {
            return $this->render('edit', [
                        'model' => $model,
                        'dataForm' => $this->generateForm($model->category->fields, $model->data)
            ]);
        }
    }

    public function actionCopy($id, $item_id = null) {
        if (!($category = Category::findOne($id))) {
            return $this->redirect(['/admin/' . $this->module->id]);
        }
        if (!($item = Item::findOne($item_id))) {
            return $this->redirect(['/admin/' . $this->module->id]);
        }
        $model = new Item;

        if ($model->load(Yii::$app->request->post())) {
            $model->slug = null;
            if (Yii::$app->request->isAjax) {

                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                $model->category_id = $category->primaryKey;

                $model->data = Yii::$app->request->post('Data');

                if ($model->save()) {
                    $this->flash('success', Yii::t('admin/catalog', 'Элемент создан'));
                    return $this->redirect(['/admin/' . $this->module->id . '/item/edit/', 'id' => $model->primaryKey]);
                } else {
                    $this->flash('error', Yii::t('admin', 'Ошибка. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        } else {

            $model->attributes = $item->attributes;
            $model->slug = null;
            $model->time = time();
        }
        return $this->render('create', [
                    'model' => $model,
                    'category' => $category,
                    'dataForm' => $this->generateForm($category->fields, $item->data)
        ]);
    }

    public function actionDelete($id) {
        if (($model = Item::findOne($id))) {
            $model->delete();
        } else {
            $this->error = Yii::t('admin', 'Запись не найдена');
        }
        return $this->formatResponse(Yii::t('admin/catalog', 'Элемент удален'));
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
                $model = Item::findOne($item);
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
    
    public function actionNewJson() {

        $data = Yii::$app->request->post('data');
        if (isset($data)) {
            if (!is_array($data)) {
                return Json::encode([
                    'status' => 'error'
                ]);
            }
            foreach ($data as $item) {
                $model = Item::findOne($item);
                if ($model) {
                    $model->setNewFlag();
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
    
    public function actionGiftJson() {

        $data = Yii::$app->request->post('data');
        if (isset($data)) {
            if (!is_array($data)) {
                return Json::encode([
                    'status' => 'error'
                ]);
            }
            foreach ($data as $item) {
                $model = Item::findOne($item);
                if ($model) {
                    $model->setGiftFlag();
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

    public function actionManualJson() {

        $data = Yii::$app->request->post('data');
        if (isset($data)) {
            if (!is_array($data)) {
                return Json::encode([
                    'status' => 'error'
                ]);
            }
            foreach ($data as $item) {
                $model = Item::findOne($item);
                if ($model) {
                    $model->setManualFlag();
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
    
    public function actionUp($id, $category_id) {
        return $this->move($id, 'up', ['category_id' => $category_id]);
    }

    public function actionDown($id, $category_id) {
        return $this->move($id, 'down', ['category_id' => $category_id]);
    }

    public function actionOn($id) {
        return $this->changeStatus($id, Item::STATUS_ON);
    }

    public function actionOff($id) {
        return $this->changeStatus($id, Item::STATUS_OFF);
    }

    private function generateForm($fields, $data = null) {
        $result = '';
        foreach ($fields as $field) {
            $value = !empty($data->{$field->name}) ? $data->{$field->name} : null;
            if ($field->type === 'string') {
                $result .= '<div class="form-group"><label>' . $field->title . '</label>' . Html::input('text', "Data[{$field->name}]", $value, ['class' => 'form-control']) . '</div>';
            } elseif ($field->type === 'text') {
                $result .= '<div class="form-group"><label>' . $field->title . '</label>' . Html::textarea("Data[{$field->name}]", $value, ['class' => 'form-control']) . '</div>';
            } elseif ($field->type === 'boolean') {
                $result .= '<div class="checkbox"><label>' . Html::checkbox("Data[{$field->name}]", $value, ['uncheck' => 0]) . ' ' . $field->title . '</label></div>';
            } elseif ($field->type === 'select' || $field->type === 'ymlColor') {
                $options = ['' => Yii::t('admin/catalog', '(не выбрано)')];
                foreach ($field->options as $option) {
                    $options[$option] = $option;
                }
                $result .= '<div class="form-group"><label>' . $field->title . '</label><select name="Data[' . $field->name . ']" class="form-control">' . Html::renderSelectOptions($value, $options) . '</select></div>';
            } elseif ($field->type === 'checkbox') {
                $options = '';
                foreach ($field->options as $option) {
                    $checked = $value && in_array($option, $value);
                    $options .= '<br><label>' . Html::checkbox("Data[{$field->name}][]", $checked, ['value' => $option]) . ' ' . $option . '</label>';
                }
                $result .= '<div class="checkbox well well-sm"><b>' . $field->title . '</b>' . $options . '</div>';
            }
        }
        return $result;
    }

    public function actionExportToExcel($id) {
        if (!($category = Category::findOne($id))) {
            return $this->redirect(['/admin/' . $this->module->id]);
        }

        $filterForm = new FilterForm();
        $filters = [];
        if ($filterForm->load(Yii::$app->request->get()) && $filterForm->validate()) {
            $filters = $filterForm->parse($filters);
        }

        $flat = Category::flat($category->slug);
        $ids[] = $this->id;
        foreach ($flat as $item) {
            $ids[] = $item->id;
        }

        $model = new \admin\modules\yml\external_export\Shop();
        $model->title = $category->title . ' - ' . Setting::get('contact_name');
        $model->to_excel = true;
        $model->asAttachment = true;
        $model->status = $filters['status'];
        $model->brands = $filters['brand_id'];
        $model->categories = $ids;

        $model->saveToExcelFile();
    }

}
