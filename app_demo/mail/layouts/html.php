<?

use yii\helpers\Html;
use admin\helpers\Schema;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<? $this->beginPage() ?>
<!DOCTYPEhtml PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
        <title><?= Html::encode($this->title) ?></title>
        <? $this->head() ?>
    </head>
    <body>
        <img src="<?= $message->embed(Yii::getAlias('@app') . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'logo.png'); ?>">
            <? $this->beginBody() ?>
            <hr>
            <?= $content ?>
            <? $this->endBody() ?>
            <hr>
            <?= Schema::localBusiness() ?>
    </body>
</html>
<? $this->endPage() ?>