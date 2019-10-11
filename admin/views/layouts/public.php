<?
use admin\assets\AdminLteAsset;
use admin\assets\PublicAsset;

AdminLteAsset::register($this);
PublicAsset::register($this);
$appAsset = Yii::$app->assetManager->getBundle('admin\assets\AdminAsset');
?>
<? $this->beginContent('@admin/views/layouts/base.php'); ?>
<div class="login-box">    
    <div class="login-box-body text-center">
        <div class="logo">
            <a href="https://yiistudio.ru" target="_blank" title="https://yiistudio.ru">
                <img style="margin: auto" src="<?= $appAsset->baseUrl ?>/img/logo.png" class="img-responsive" alt="<?= \admin\AdminModule::NAME ?>">                    
            </a>
        </div> 
        <br>
        <?= $content ?>
    </div>
</div>
<? $this->endContent(); ?>
