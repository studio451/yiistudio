<?

namespace admin\controllers;

use Yii;
use admin\helpers\WebConsole;
use admin\models\Setting;
use admin\helpers\Upload;
use yii\helpers\FileHelper;

class SystemController extends \admin\components\Controller {

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionUpdate() {
        Setting::set('admin_version', \admin\AdminModule::VERSION);
        Yii::$app->cache->flush();

        return $this->formatResponse($result, true, true);
    }

    public function actionMigrate() {
        $result = WebConsole::migrate('admin');

        return $this->formatResponse($result, true, true);
    }

    public function actionMigrateDown() {
        $result = WebConsole::migrateDown('admin');
        return $this->formatResponse($result, true, true);
    }
    
    public function actionMigrateApp() {
        $result = WebConsole::migrate('app');

        return $this->formatResponse($result, true, true);
    }

    public function actionMigrateAppDown() {
        $result = WebConsole::migrateDown('app');
        return $this->formatResponse($result, true, true);
    }

    public function actionClearItems() {
        Yii::$app->db->createCommand()->truncateTable('admin_module_catalog_item_data')->execute();
        Yii::$app->db->createCommand()->truncateTable('admin_module_catalog_item')->execute();
        Yii::$app->db->createCommand()->truncateTable('admin_module_catalog_group')->execute();

        $this->flash('success', Yii::t('admin', 'Элементы каталога удалены'));
        return $this->back();
    }

    public function actionClearPhotos() {
        Yii::$app->db->createCommand()->truncateTable('admin_photos')->execute();

        foreach (glob(Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . Upload::$UPLOADS_DIR . DIRECTORY_SEPARATOR . 'photos' . DIRECTORY_SEPARATOR . '*') as $item) {
            if (is_link($item)) {
                unlink($item);
            } elseif (is_dir($item)) {
                FileHelper::removeDirectory($item);
            } else {
                unlink($item);
            }
        }

        $this->flash('success', Yii::t('admin', 'Фотографии удалены'));
        return $this->back();
    }

    public function actionClearTmp() {
        foreach (glob(Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . Upload::$UPLOADS_DIR . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . '*') as $item) {
            if (is_link($item)) {
                unlink($item);
            } elseif (is_dir($item)) {
                FileHelper::removeDirectory($item);
            } else {
                unlink($item);
            }
        }

        $this->flash('success', Yii::t('admin', 'Tmp директория очищена'));
        return $this->back();
    }

    public function actionClearThumbs() {
        foreach (glob(Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . Upload::$UPLOADS_DIR . DIRECTORY_SEPARATOR . 'thumbs' . DIRECTORY_SEPARATOR . '*') as $item) {
            if (is_link($item)) {
                unlink($item);
            } elseif (is_dir($item)) {
                FileHelper::removeDirectory($item);
            } else {
                unlink($item);
            }
        }

        $this->flash('success', Yii::t('admin', 'Thumbs директория очищена'));
        return $this->back();
    }

    public function actionFlushCache() {
        Yii::$app->cache->flush();
        $this->flash('success', Yii::t('admin', 'Кэш очищен'));
        return $this->back();
    }

    public function actionClearAssets() {
        foreach (glob(Yii::$app->assetManager->basePath . DIRECTORY_SEPARATOR . '*') as $item) {
            if (is_link($item)) {
                unlink($item);
            } elseif (is_dir($item)) {
                FileHelper::removeDirectory($item);
            } else {
                unlink($item);
            }
        }
        $this->flash('success', Yii::t('admin', 'Файлы ресурсов (.js, .css) обновлены'));
        return $this->back();
    }

    public function actionLiveEdit($id) {
        Yii::$app->session->set('admin_live_edit', $id);
        $this->back();
    }

    public function actionRecreateGroups() {
        $result = \admin\modules\catalog\helpers\WebConsole::catalogRecreateGroups();
        return $this->formatResponse($result, true, true);
    }   
    
    public function actionResaveItems() {
        $result = \admin\modules\catalog\helpers\WebConsole::catalogResaveItems();
        return $this->formatResponse($result, true, true);
    }     
    
    public function actionClearUsersNoOrder() {
        $result = \admin\modules\shopcart\helpers\WebConsole::catalogClearUsersNoOrder();
        return $this->formatResponse($result, true, true);
    }

    
    
}
