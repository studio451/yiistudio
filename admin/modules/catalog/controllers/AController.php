<?

namespace admin\modules\catalog\controllers;

use Yii;
use admin\components\CategoryController;
use admin\modules\catalog\models\Category;
use admin\helpers\Color;

class AController extends CategoryController {

    public $categoryClass = 'admin\modules\catalog\models\Category';
    public $moduleName = 'catalog';

    public function actionFields($id) {
        if (!($model = Category::findOne($id))) {
            return $this->redirect(['/admin/' . $this->module->id]);
        }

        if (Yii::$app->request->post('save')) {
            $fields = Yii::$app->request->post('Field') ?: [];

            $fields_data = [];
            $fields_without_data = [];

            foreach ($fields as $field) {
                $temp = json_decode($field);

                if ($temp === null && json_last_error() !== JSON_ERROR_NONE ||
                        empty($temp->name) ||
                        empty($temp->title) ||
                        empty($temp->type) ||
                        !($temp->name = trim($temp->name)) ||
                        !($temp->title = trim($temp->title)) ||
                        !array_key_exists($temp->type, Category::$fieldTypes)
                ) {
                    continue;
                }
                $options = '';
                if ($temp->type == 'select' || $temp->type == 'checkbox' || $temp->type == 'data' || $temp->type == 'ymlColor') {
                    if ($temp->type == 'ymlColor') {
                        $temp->options = Color::ymlColorsStringCommaSeparated();
                    }
                    if (empty($temp->options) || !($temp->options = trim($temp->options))) {
                        continue;
                    }
                    if ($temp->type == 'data') {
                        $options = $temp->options;
                    } else {
                        $options = [];
                        foreach (explode(',', $temp->options) as $option) {
                            $options[] = trim($option);
                        }
                    }
                }

                if ($temp->type == 'data') {
                    $fields_data[] = [
                        'name' => \yii\helpers\Inflector::slug($temp->name),
                        'title' => $temp->title,
                        'type' => $temp->type,
                        'options' => $options
                    ];
                } else {
                    $fields_without_data[] = [
                        'name' => \yii\helpers\Inflector::slug($temp->name),
                        'title' => $temp->title,
                        'type' => $temp->type,
                        'options' => $options
                    ];
                }
            }

            $model->fields = array_merge($fields_data, $fields_without_data);

            if ($model->save()) {

                foreach ($model->children()->all() as $child) {
                    $child_fields_without_data = $fields_without_data;
                    $child_fields_data = [];
                    foreach ($fields_data as $field_data) {
                        if ($child->getFieldOptions($field_data['name']) == null || $child->getFieldOptions($field_data['name']) == '-') {
                            $child_fields_data[] = $field_data;
                        } else {
                            $field = $child->getField($field_data['name']);
                            $field->type = $field_data['type'];
                            $field->title = $field_data['title'];
                            $child_fields_data[] = $field;
                        }
                    }

                    $child->fields = array_merge($child_fields_data, $child_fields_without_data);
                    $child->save();
                    
                    
                }

                $this->flash('success', Yii::t('admin/catalog', 'Категория обновлена'));
            } else {
                $this->flash('error', Yii::t('admin', 'Ошибка при обновлении записи. {0}', $model->formatErrors()));
            }
            return $this->refresh();
        } else {
            return $this->render('fields', [
                        'model' => $model
            ]);
        }
    }

    public function actionEdit($id, $parent = null) {
        $this->view->params['submenu'] = true;

        return parent::actionEdit($id, $parent);
    }

    public function actionRecreateGroups() {
        $result = WebConsole::catalogRecreateGroups();
        return $this->formatResponse($result, true, true);
    }

    public function actionResaveItems() {
        $result = WebConsole::catalogResaveItems();
        return $this->formatResponse($result, true, true);
    }
}
