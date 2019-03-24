<?


$uploads_current = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'uploads';
$uploads_tmp = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'uploads_tmp';
$uploads_old = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'uploads_old';

if (file_exists($uploads_old)) {
    if (file_exists($uploads_current)) {
        rename($uploads_current, $uploads_tmp);
    }
    yii\helpers\FileHelper::copyDirectory($uploads_old, $uploads_current);
    yii\helpers\FileHelper::removeDirectory($uploads_tmp);
}

//Сбрасываем ресурсы
foreach (glob(Yii::$app->assetManager->basePath . DIRECTORY_SEPARATOR . '*') as $item) {
    if (is_link($item)) {
        unlink($item);
    } elseif (is_dir($item)) {
        yii\helpers\FileHelper::removeDirectory($item);
    } else {
        unlink($item);
    }
}
