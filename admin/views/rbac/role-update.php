<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('admin/rbac', 'Редактирование роли: ') . ' ' . $role->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin/rbac', 'Управление ролями'), 'url' => ['role']];
$this->params['breadcrumbs'][] = Yii::t('admin/rbac', 'Редактирование');
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
            <?= Html::label(Yii::t('admin/rbac', 'Название роли')); ?>
            <?= Html::textInput('name', $role->name); ?>
        </div>

        <div class="form-group">
            <?= Html::label(Yii::t('admin/rbac', 'Текстовое описание')); ?>
            <?= Html::textInput('description', $role->description); ?>
        </div>

        <div class="form-group">
            <?= Html::label(Yii::t('admin/rbac', 'Разрешенные доступы')); ?>
            <?= Html::checkboxList('permissions', $role_permit, $permissions, ['separator' => '<br>']); ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-success']) ?>
        </div>

        <? ActiveForm::end(); ?>

    </div>
</div>
