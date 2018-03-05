<?

use admin\models\Setting;
use yii\helpers\Url;

$this->title = Yii::t('admin', 'Настройки');
?>

<?= $this->render('_menu') ?>

<? if ($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th width="50">#</th>
                <th><?= Yii::t('admin', 'Название') ?></th>
                <th><?= Yii::t('admin', 'Код') ?></th>
                <th><?= Yii::t('admin', 'Значение') ?></th>
                <th width="30"></th>

            </tr>
        </thead>
        <tbody>
            <? foreach ($data->models as $setting) : ?>
                <tr <? if ($setting->visibility == Setting::VISIBLE_ROOT) echo 'class="warning"' ?>>
                    <td><?= $setting->primaryKey ?></td>
                    <td><a href="<?= Url::to(['/admin/settings/edit', 'id' => $setting->primaryKey]) ?>" title="<?= Yii::t('admin', 'Редактировать') ?>"><?= $setting->title ?></a></td>
                    <td><?= $setting->name ?></td>
                    <td style="overflow: hidden"><?= $setting->value ?></td>
                    <td><a href="<?= Url::to(['/admin/settings/delete', 'id' => $setting->primaryKey]) ?>" class="fa fa-times text-red" title="<?= Yii::t('admin', 'Удалить запись') ?>"></a></td>
                </tr>
            <? endforeach; ?>
        </tbody>
        <?=
        yii\widgets\LinkPager::widget([
            'pagination' => $data->pagination
        ])
        ?>
    </table>
<? else : ?>
    <p><?= Yii::t('admin', 'Записи не найдены') ?></p>
<? endif; ?>
<br>
<br>
<a href="<?= Url::to(['/admin/settings/update-settings']) ?>" class="pull-right text-warning"><i class="glyphicon glyphicon-flash"></i> <?= Yii::t('admin', 'Обновить настройки') ?></a>
