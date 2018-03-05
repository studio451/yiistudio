<?

use yii\helpers\Url;

$this->title = Yii::t('admin', 'Пользователи');
?>

<?= $this->render('_menu') ?>

<? if ($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th width="50">#</th>
                <th><?= Yii::t('admin', 'E-mail') ?></th>
                <th width="150"></th>
                <th width="30"></th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($data->models as $user) : ?>
                <tr>
                    <td><?= $user->id ?></td>
                    <td><a href="<?= Url::to(['/admin/users/edit', 'id' => $user->id]) ?>"><?= $user->email ?></a></td>
                    <td><a href="<?= Url::to(['/admin/users/login', 'id' => $user->id]) ?>"><?= Yii::t('admin', 'Авторизоватся') ?></a></td>
                    <td><a href="<?= Url::to(['/admin/users/delete', 'id' => $user->id]) ?>" class="fa fa-times text-red" title="<?= Yii::t('admin', 'Удалить запись') ?>"></a></td>
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