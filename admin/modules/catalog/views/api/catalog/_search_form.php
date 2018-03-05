<?

use yii\helpers\Html;
use yii\helpers\Url;
?>
<?= Html::beginForm(Url::to(['/catalog/search']), 'get') ?>
<div class="input-group <?= $sm?'input-group-sm':'' ?>" style="margin-top: 2px;">
    <?= Html::textInput('text', $text, ['class' => 'form-control border-radius-0 box-shadow-none', 'placeholder' => Yii::t('admin', 'Поиск')]) ?>
    <span class="input-group-btn">
        <button class="btn btn-default <?= $sm?'btn-sm':'' ?> border-radius-0" type="submit"><i class="fa fa-search"></i><?= $sm?'':' '.Yii::t('admin', 'Поиск') ?></button>
    </span>
</div>
<?= Html::endForm() ?>