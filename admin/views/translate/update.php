<?

use yii\helpers\Html;

$this->title = Yii::t('admin', 'Обновить') . ': ' . $model->translateSourceMessage->message;
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'Переводы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id, 'language' => $model->language]];
$this->params['breadcrumbs'][] = Yii::t('admin', 'Обновить');
?>
<?=

$this->render('_form', [
    'model' => $model,
])
?>


