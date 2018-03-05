<?

namespace admin\modules\sitemap\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use admin\modules\sitemap\models\Sitemap;

/**
 * Class SitemapGenerateController
 */
class SitemapController extends Controller {

    private $_module = null;

    /**
     * Location sitemap files
     * @var string
     */
    private $_baseDir = null;

    /**
     * Location pages files
     * @var string
     */
    private $_pageDir = null;

    /**
     * URI to sitemap file
     * @var string
     */
    private $_pathMainFile = null;

    /**
     * Init command
     */
    public function init() {
        $this->_module = Yii::$app->getModule('admin')->getModule('sitemap');
        $this->_baseDir = Yii::getAlias('@webroot');
        $this->_pageDir = $this->_baseDir .DIRECTORY_SEPARATOR. $this->_module->settings['pageDir'];
        $this->_pathMainFile = $this->_baseDir .DIRECTORY_SEPARATOR. $this->_module->settings['mainFile'];
    }

    /**
     * List commands
     */
    public function actionIndex() {
        echo "sitemap/generate - Generate new sitemap\n";
        echo "sitemap/delete   - Delete all files of sitemap\n";
    }

    /**
     * Delete all files
     */
    public function actionDelete() {
        $this->deleteFiles();
    }

    /**
     * Building sitemat
     */
    private $_models = [];

    public function actionGenerate() {
        // Delete old files
        $this->deleteFiles();


        $sitemaps = Sitemap::find()->all();

        $pages = [];
        foreach ($sitemaps as $sitemap) {
            $model = new $sitemap->class;
            $config = [
                'filePaths' => $sitemap->priority,
                'filePaths' => Yii::getAlias('@webroot'),
                'viewPath' => $this->_module->viewPath,
                'perPage' => $this->_module->settings['perPage'],
            ];
            $pages = ArrayHelper::merge($pages, $model->buildPages($config));            
        }

        $xmlData = Yii::$app->view->renderPhpFile(
                $this->_module->viewPath . '/templates/main-template.php', ['urls' => $pages]
        );

        if (file_put_contents($this->_pathMainFile, $xmlData)) {
            echo "{$this->_pathMainFile}\n";
            echo "DONE\n";
        }
    }

    /**
     * Delete all files of sitemap
     */
    private function deleteFiles() {
        // Clear old files
        FileHelper::removeDirectory($this->_pageDir);
        FileHelper::createDirectory($this->_pageDir, 0777);

        // Delete sitemap file
        if (file_exists($this->_pathMainFile)) {
            unlink($this->_pathMainFile);
        }
    }

}
