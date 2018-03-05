<?

use admin\models\Setting;

$this->title = \admin\AdminModule::NAME;
$appAsset = Yii::$app->assetManager->getBundle('admin\assets\AdminAsset');
?>
<div class="row pt-30 pb-20">       
    <div class="col-md-12 text-center">
        <div class="logo">
            <a href="https://studio451.ru" target="_blank" title="https://studio451.ru">
                <img style="margin: auto" src="<?= $appAsset->baseUrl ?>/img/logo.png" class="img-responsive" alt="<?= \admin\AdminModule::NAME ?>">                    
            </a>
        </div> 
        <br>
        <h4><?= Yii::t('admin', 'Добро пожаловать в {name}!', ['name' => \admin\AdminModule::NAME]) ?></h4>
        <br>
        <?= Yii::t('admin', 'Версия') ?>: <b><?= Setting::get('admin_version') ?></b>
    </div>        
</div>

