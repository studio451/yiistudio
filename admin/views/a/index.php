<?

use admin\models\Setting;

$this->title = Yii::t('admin', 'Панель управления') . ' - ' . Setting::get('contact_name');
$appAsset = Yii::$app->assetManager->getBundle('admin\assets\AdminAsset');
?>
<div class="row pt-30 pb-20">       
    <div class="col-md-12 text-center">
        <div class="logo">
            <a href="https://yiistudio.ru" target="_blank" title="https://yiistudio.ru">
                <img style="margin: auto" src="<?= $appAsset->baseUrl ?>/img/logo.png" class="img-responsive" alt="<?= \admin\AdminModule::NAME ?>">                    
            </a>
        </div> 
        <br>
        <h4><?= Yii::t('admin', 'Панель управления') . ' - ' . Setting::get('contact_name') ?></h4>
        <br>
        <a href="https://yiistudio.ru" target="_blank" title="https://yiistudio.ru"><?= \admin\AdminModule::NAME ?></a>
        <br>
        <?= Yii::t('admin', 'Версия') ?>: <b><?= Setting::get('admin_version') ?></b>
    </div>        
</div>

