<?

use yii\bootstrap\BootstrapPluginAsset;
use yii\helpers\Html;

BootstrapPluginAsset::register($this);
?>
<p>
    <a class="collapsed" data-toggle="collapse" href="#seo-template-form" aria-expanded="false" aria-controls="seo-template-form"><?= Yii::t('admin/seo', 'SEO шаблоны') ?></a>
</p>
<div class="collapse" id="seo-template-form">
    <div class="form-group">
        <label class="control-label"><?= Yii::t('admin/seo', 'SEO шаблон для категорий') ?></label>
        <?= Html::activeDropDownList($model, 'template_id', admin\modules\seo\models\SeoTemplate::listAll('id', 'slug', true, [],Yii::t('admin', '(не выбрано)')), ['multiple' => false, 'class' => 'form-control']) ?>
    </div>
    <div class="form-group">
        <label class="control-label"><?= Yii::t('admin/seo', 'SEO шаблон для элементов') ?></label>
        <?= Html::activeDropDownList($model, 'item_template_id', admin\modules\seo\models\SeoTemplate::listAll('id', 'slug', true, [],Yii::t('admin', '(не выбрано)')), ['multiple' => false, 'class' => 'form-control']) ?>
    </div>
</div>