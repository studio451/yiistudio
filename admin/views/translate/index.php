<?

use yii\helpers\Url;

$this->title = Yii::t('admin', 'Локализация');
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
    <?= Yii::t('admin', 'Исходный язык') ?>: <b><?= \Yii::$app->sourceLanguage ?></b>
    <br>
    <?= Yii::t('admin', 'Текущий язык перевода') ?>: <b><?= \Yii::$app->language ?></b> [<?=Yii::t('admin','Настройка language, например, в файле {config}',['config' => Yii::getAlias('@admin/config/admin.php')])?> ]
</p>
<p>
    <?= Yii::t('admin', 'Выгрузить сообщения для локализации из Панели управления и модулей') . ' [' . Yii::getAlias('@admin') . ']' ?><br/>
    <?= Yii::t('admin', 'Команда') . ':' ?>
    <div class="alert-console p-20">
            ./yii message @admin/config/messages.php<br>
            <?
            foreach (Yii::$app->getModule('admin')->activeModules as $name => $module) {
                $message_config = '@admin/modules/' . $name . '/messages/config.php';
                if (file_exists(Yii::getAlias($message_config))) {
                    echo './yii message ' . $message_config . ' /config/messages.php<br>';
                }
            }
            ?>
    </div><br/>
    <a href="<?= Url::to(['/admin/translate/message-extract-admin']) ?>" class="btn btn-success mt-5"><?= Yii::t('admin', 'Выполнить') ?></a>
</p>
<br>
<p>
    <?= Yii::t('admin', 'Выгрузить сообщения для локализации из Приложения') . ' [' . Yii::getAlias('@app') . ']' ?><br/>
    <?= Yii::t('admin', 'Команда') . ':' ?>
    <div class="alert-console p-20">./yii message @app/config/messages.php</div><br/>
    <a href="<?= Url::to(['/admin/translate/message-extract-app']) ?>" class="btn btn-success mt-5"><?= Yii::t('admin', 'Выполнить') ?></a>
</p>