<?

use yii\helpers\Html;

$this->title = $name;
?>
<h1><?= Html::encode($this->title) ?></h1>
<h3>
    <?= Yii::t('app','Ой! Что-то пошло не так') ?>
</h3>
<div class="alert alert-danger">
<?= nl2br(Html::encode($message)) ?>
</div>