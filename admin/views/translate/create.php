<?

use yii\helpers\Html;

$this->title = Yii::t('admin', 'Добавить перевод');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'Переводы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_form', [
'model' => $model,
]) ?>


