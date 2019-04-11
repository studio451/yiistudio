<?

namespace admin\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use PDO;
use PDOException;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
use admin\models\storage\Dump;
use admin\models\storage\Restore;
use admin\helpers\WebConsole;
use admin\models\Setting;

class DumpController extends \admin\base\admin\Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['post'],
                    'delete' => ['post'],
                    'delete-all' => ['post'],
                    'restore' => ['get', 'post'],
                    '*' => ['get'],
                ],
            ],
        ];
    }

    public function beforeAction($action) {
        if (!parent::beforeAction($action))
            return false;

        //Отключение функциональности в демо-режиме
        if (DEMO === true) {

            if ($action->id == 'delete' ||
                    $action->id == 'delete-all' ||
                    $action->id == 'create') {
                $this->flash('warning', Yii::t('admin', 'Недоступно в демо-версии!'));
                $this->goBack();
                return false;
            }
        }
        return true;
    }

    public function actionIndex() {
        $dataArray = $this->prepareFileData();
        $model = new Dump($this->customDumpOptions);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $dataArray,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'model' => $model,
        ]);
    }

    public function actionTestConnection($dbname) {
        $info = $this->getDbInfo($dbname);
        try {
            new PDO($info['dsn'], $info['username'], $info['password']);
            $this->flash('sussess', Yii::t('admin', 'Соединение установлено!'));
        } catch (PDOException $e) {
            $this->flash('error', Yii::t('admin','Ошибка соединения') . ': ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionDownload($id) {
        $dumpPath = $this->path . basename(ArrayHelper::getValue($this->getFileList(), $id));

        return Yii::$app->response->sendFile($dumpPath);
    }

    public function actionDelete($id) {
        $dumpFile = $this->path . basename(ArrayHelper::getValue($this->getFileList(), $id));
        if (unlink($dumpFile)) {
            $this->flash('success', Yii::t('admin', 'Дамп удален'));
        } else {
            $this->flash('error', Yii::t('admin', 'Ошибка при удалении дампа'));
        }

        return $this->redirect(['index']);
    }

    public function actionDeleteAll() {
        if (is_array($this->getFileList())) {
            $fail = [];
            foreach ($this->getFileList() as $file) {
                if (!unlink($file)) {
                    $fail[] = $file;
                }
            }
            if (empty($fail)) {
                $this->flash('success', Yii::t('admin', 'Все дампы удалены'));
            } else {
                $this->flash('error', Yii::t('admin', 'Ошибка при удалении дампов'));
            }
        }

        return $this->redirect(['index']);
    }

    public function actionCreate() {
        $dump = Yii::$app->request->post('Dump');
        $result = WebConsole::dumpCreate($dump['isArchive'], $dump['schemaOnly']);

        return $this->formatResponse($result, true, true);
    }

    public function actionSelect($id) {
        $dumpFile = $this->path . basename(ArrayHelper::getValue($this->getFileList(), $id));
        $model = new Restore();

        return $this->render('restore', [
                    'model' => $model,
                    'file' => $dumpFile,
                    'id' => $id,
        ]);
    }

    public function actionRestore($id) {
        $restore = Yii::$app->request->post('Restore');
        $result = WebConsole::dumpRestore($restore['initData'], $restore['demoData'], $restore['restoreScript'], $id);
        return $this->formatResponse($result, true, true);
    }

    protected function prepareFileData() {
        $dataArray = [];
        foreach ($this->getFileList() as $id => $file) {
            $columns = [];
            $columns['id'] = $id;
            $columns['type'] = pathinfo($file, PATHINFO_EXTENSION);
            $columns['name'] = basename($file);
            $columns['size'] = Yii::$app->formatter->asSize(filesize($file));
            $columns['create_at'] = Yii::$app->formatter->asDatetime(filectime($file));
            $dataArray[] = $columns;
        }
        ArrayHelper::multisort($dataArray, ['create_at'], [SORT_DESC]);

        return $dataArray;
    }

    /**
     * Path for backup directory
     *
     * @var string $path
     */
    public $path = '';

    /**
     * You can setup favorite dump options presets foreach db
     *
     * @example
     *    'customDumpOptions' => [
     *        'preset1' => '--triggers --single-transaction',
     *        'preset2' => '--replace --lock-all-tables',
     *    ],
     * @var array $customDumpOptions
     */
    public $customDumpOptions = [];

    /**
     * @see $customDumpOptions
     * @var array $customRestoreOptions
     */
    public $customRestoreOptions = [];

    /**
     * @var callable|Closure $createManagerCallback
     * argument - dbInfo; expected reply - instance of bs\dbManager\contracts\IDumpManager or false, for default
     * @example
     * 'createManagerCallback' => function($dbInfo) {
     *     if($dbInfo['dbName'] == 'exclusive') {
     *         return new MyExclusiveManager;
     *     } else {
     *         return false;
     *     }
     * }
     */
    public $createManagerCallback;

    /**
     * @var array
     */
    protected $dbInfo = [];

    /**
     * @var array
     */
    protected $fileList = [];

    /**
     * @throws InvalidConfigException
     * @throws \yii\base\InvalidConfigException
     */
    public function init() {
        parent::init();

        $this->path = Yii::getAlias(Setting::get('path_dumps'));
        if (!StringHelper::endsWith($this->path, '/', false)) {
            $this->path .= '/';
        }
        if (!is_dir($this->path)) {
            throw new InvalidConfigException('Path is not directory');
        }
        if (!is_writable($this->path)) {
            throw new InvalidConfigException('Path is not writable! Check chmod!');
        }
        $this->fileList = FileHelper::findFiles($this->path, ['only' => ['*.sql', '*.gz']]);
    }

    public function getDbInfo() {

        $this->dbInfo['driverName'] = Yii::$app->db->driverName;
        $this->dbInfo['dsn'] = Yii::$app->db->dsn;
        $this->dbInfo['host'] = $this->getDsnAttribute('host', Yii::$app->db->dsn);
        $this->dbInfo['port'] = $this->getDsnAttribute('port', Yii::$app->db->dsn);
        $this->dbInfo['dbName'] = $this->getDsnAttribute('dbname', Yii::$app->db->dsn);
        $this->dbInfo['username'] = Yii::$app->db->username;
        $this->dbInfo['password'] = Yii::$app->db->password;
        $this->dbInfo['prefix'] = Yii::$app->db->tablePrefix;

        return $this->dbInfo;
    }

    /**
     * @return array
     */
    public function getFileList() {
        return $this->fileList;
    }

    /**
     * @param array $dbInfo
     * @return IDumpManager
     * @throws NotSupportedException
     */
    public function createManager($dbInfo) {
        if (is_callable($this->createManagerCallback)) {
            $result = call_user_func($this->createManagerCallback, $dbInfo);
            if ($result !== false) {
                return $result;
            }
        }
        if ($dbInfo['driverName'] === 'mysql') {
            return new \admin\models\storage\MysqlDumpManager();
        } elseif ($dbInfo['driverName'] === 'pgsql') {
            return new \admin\models\storage\PostgresManagerClass();
        } else {
            throw new NotSupportedException($dbInfo['driverName'] . ' driver unsupported!');
        }
    }

    /**
     * @param $name
     * @param $dsn
     * @return null
     */
    protected function getDsnAttribute($name, $dsn) {
        if (preg_match('/' . $name . '=([^;]*)/', $dsn, $match)) {
            return $match[1];
        } else {
            return null;
        }
    }

}
