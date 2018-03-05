<?

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::t('admin/sitemap', 'Редактировать класс');
$module = $this->context->module->id;
?>
<?= $this->render('_menu') ?>
<?= $this->render('_form', ['model' => $model]) ?>
