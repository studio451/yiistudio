<?
use admin\base\CategoryModel;
use yii\helpers\Url;

\yii\bootstrap\BootstrapPluginAsset::register($this);

$this->title = Yii::$app->getModule('admin')->activeModules[$this->context->module->id]->title;

$baseUrl = '/admin/'.$this->context->moduleName;
?>

<?= $this->render('_menu') ?>

<? if(sizeof($categories) > 0) : ?>
    <table class="table table-hover">
        <tbody>
            <? foreach($categories as $category) : ?>
                <tr>
                    <td width="50"><?= $category->id ?></td>
                    <td style="padding-left:  <?= $category->depth * 20 ?>px;">
                        <? if(count($category->children)) : ?>
                            <i class="caret"></i>
                        <? endif; ?>
                        <? if(!count($category->children) || !empty(Yii::$app->controller->module->settings['itemsInFolder'])) : ?>
                            <a href="<?= Url::to([$baseUrl . $this->context->viewRoute, 'id' => $category->id]) ?>" <?= ($category->status == CategoryModel::STATUS_OFF ? 'class="text-muted"' : '') ?>><?= $category->title ?></a>
                        <? else : ?>
                            <span <?= ($category->status == CategoryModel::STATUS_OFF ? 'class="text-muted"' : '') ?>><?= $category->title ?></span>
                        <? endif; ?>
                    </td>
                    <td width="120" class="text-right">
                        <div class="dropdown actions">
                            <i id="dropdownMenu<?= $category->id ?>" data-toggle="dropdown" aria-expanded="true" title="<?= Yii::t('admin', 'Операции с категориями') ?>" class="fa fa-bars"></i>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropdownMenu<?= $category->id ?>">
                                <li><a href="<?= Url::to([$baseUrl.'/a/edit', 'id' => $category->id, 'parent' => $category->parent]) ?>"><i class="fa fa-pen fs-12"></i> <?= Yii::t('admin', 'Редактировать') ?></a></li>
                                <li><a href="<?= Url::to([$baseUrl.'/a/create', 'parent' => $category->id]) ?>"><i class="fa fa-plus fs-12"></i> <?= Yii::t('admin', 'Добавить подкатегорию') ?></a></li>
                                <li role="presentation" class="divider"></li>
                                <li><a href="<?= Url::to([$baseUrl.'/a/up', 'id' => $category->id]) ?>"><i class="fa fa-arrow-up fs-12"></i> <?= Yii::t('admin', 'Переместить вверх') ?></a></li>
                                <li><a href="<?= Url::to([$baseUrl.'/a/down', 'id' => $category->id]) ?>"><i class="fa fa-arrow-down fs-12"></i> <?= Yii::t('admin', 'Переместить вниз') ?></a></li>
                                <li role="presentation" class="divider"></li>
                                <? if($category->status == CategoryModel::STATUS_ON) :?>
                                    <li><a href="<?= Url::to([$baseUrl.'/a/off', 'id' => $category->id]) ?>" title="<?= Yii::t('admin', 'Выключить') ?>'"><i class="fa fa-eye-slash fs-12"></i> <?= Yii::t('admin', 'Деактивировать') ?></a></li>
                                <? else : ?>
                                    <li><a href="<?= Url::to([$baseUrl.'/a/on', 'id' => $category->id]) ?>" title="<?= Yii::t('admin', 'Включить') ?>"><i class="fa fa-eye fs-12"></i> <?= Yii::t('admin', 'Активировать') ?></a></li>
                                <? endif; ?>
                                <li><a href="<?= Url::to([$baseUrl.'/a/delete', 'id' => $category->id]) ?>" class="text-red" data-reload="1" title="<?= Yii::t('admin', 'Удалить категорию') ?>"><i class="fa fa-times fs-12"></i> <?= Yii::t('admin', 'Удалить категорию') ?></a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            <? endforeach;?>
        </tbody>
    </table>
<? else : ?>
    <p><?= Yii::t('admin', 'Записи не найдены') ?></p>
<? endif; ?>