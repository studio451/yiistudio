<?
use yii\bootstrap\BootstrapPluginAsset;
use yii\helpers\Html;

BootstrapPluginAsset::register($this);

$labelOptions = ['class' => 'control-label'];
$inputOptions = ['class' => 'form-control'];
?>
<p>
    <a class="collapsed" data-toggle="collapse" href="#seo-text-form" aria-expanded="false" aria-controls="seo-text-form"><?= Yii::t('admin/seo', 'SEO тексты')?></a>
</p>

<div class="collapse" id="seo-text-form">
    <div class="form-group">
        <?= Html::activeLabel($model, 'h1', $labelOptions) ?>
        <?= Html::activeTextInput($model, 'h1', $inputOptions) ?>
    </div>
    <div class="form-group">
        <?= Html::activeLabel($model, 'title', $labelOptions) ?>
        <?= Html::activeTextInput($model, 'title', $inputOptions) ?>
    </div>
    <div class="form-group">
        <?= Html::activeLabel($model, 'keywords', $labelOptions) ?>
        <?= Html::activeTextInput($model, 'keywords', $inputOptions) ?>
    </div>
    <div class="form-group">
        <?= Html::activeLabel($model, 'description', $labelOptions) ?>
        <?= Html::activeTextarea($model, 'description', $inputOptions) ?>
    </div>
</div>