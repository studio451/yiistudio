<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Links */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('admin', 'Редактирование разрешения: ') . ' ' . $permit->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'Разрешения пользователей'), 'url' => ['permission']];
$this->params['breadcrumbs'][] = Yii::t('admin', 'Редактирование разрешения');
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="links-form">

        <?
        if (!empty($error)) {
            ?>
            <div class="error-summary">
                <?
                echo implode('<br>', $error);
                ?>
            </div>
        <?
        }
        ?>

        <? $form = ActiveForm::begin(); ?>

        <div class="form-group">
            <?= Html::label(Yii::t('admin', 'Текстовое описание')); ?>
            <?= Html::textInput('description', $permit->description); ?>
        </div>

        <div class="form-group">
            <?= Html::label(Yii::t('admin', 'Разрешение пользователя')); ?>
            <?= Html::textInput('name', $permit->name); ?>
            <?= Yii::t('admin', '<br>* Формат: <strong>module/controller/action</strong><br><strong>site/article</strong> - доступ к странице "site/article"<br><strong>site</strong> - доступ к любым action контроллера "site"'); ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-success']) ?>
        </div>

        <? ActiveForm::end(); ?>

    </div>
</div>