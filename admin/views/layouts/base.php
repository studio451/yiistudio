<?

use yii\helpers\Html;
use admin\helpers\AjaxModalPopup;

$appAssetPath = '\\' . Yii::$app->id . '\assets\AppAsset';
$appAsset = $appAssetPath::register($this);

?>
<? $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>        
        <meta charset="<?= Yii::$app->charset ?>">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <meta content="<?= Html::encode($this->params['description']) ?>" name="description">
        <meta content="<?= Html::encode($this->params['keywords']) ?>" name="keywords">
        <link rel="shortcut icon" href="<?= $appAsset->baseUrl ?>/favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?= $appAsset->baseUrl ?>/favicon.ico" type="image/x-icon">        
        <? $this->head() ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini <? if (Yii::$app->getSession()->has('sidebar_collapse')) { ?>sidebar-collapse<? } ?>">
        <? $this->beginBody() ?>
        <?= $content ?>
        <? $this->endBody() ?>
        <? AjaxModalPopup::renderModal() ?>
        <?= \admin\widgets\Counters::widget(); ?>
    </body>
</html>
<? $this->endPage() ?>