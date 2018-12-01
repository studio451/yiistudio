<?

namespace admin\modules\yml\commands;

use Yii;
use yii\console\Controller;
use admin\modules\yml\models\Import;
use admin\modules\yml\models\Export;

/**
 * Default controller.
 */
class YmlController extends Controller {

    public $id;
    public $file_name;

    public function options($actionID) {
        if ($actionID == 'import') {
            return [
                'id', 'interactive'
            ];
        }
        if ($actionID == 'full-import') {
            return [
                'id', 'interactive'
            ];
        }
        if ($actionID == 'export') {
            return [
                'id', 'interactive'
            ];
        }
        if ($actionID == 'load-items-from-excel-file') {
            return [
                'id', 'file_name', 'interactive'
            ];
        }
        if ($actionID == 'add-items-from-excel-file') {
            return [
                'file_name', 'interactive'
            ];
        }
        if ($actionID == 'update-items-from-excel-file') {
            return [
                'file_name', 'interactive'
            ];
        }
        if ($actionID == 'load-categories-from-excel-file') {
            return [
                'file_name', 'interactive'
            ];
        }
        if ($actionID == 'load-news-from-excel-file') {
            return [
                'file_name', 'interactive'
            ];
        }
        if ($actionID == 'load-users-from-excel-file') {
            return [
                'file_name', 'interactive'
            ];
        }
    }

    //Импорт в Excel-файл
    public function actionImport() {

        if (($model = Import::findOne($this->id))) {
            $class = $model->class;
            $implementation = $class::findOne($this->id);
            $this->stdout($implementation->title . "\n");
            $this->stdout($implementation->url . "\n");
            $this->stdout($implementation->class . "\n");
            $this->stdout("Number of rows:" . $implementation->count . "\n");
            $this->stdout(Yii::t('admin/yml',"Старт импорта в excel...")."\n");

            $fileName = $implementation->saveToExcelFile();

            $this->stdout("Excel file created: " . $fileName . "\n");
            $this->stdout(Yii::t('admin/yml',"ВЫПОЛНЕНО")."\n");
        } else {
            $this->stdout("Import model not found\n", 91);
        }
    }

    //Импорт в Excel-файл, затем импорт из excel
    public function actionFullImport() {

        if (($model = Import::findOne($this->id))) {
            $class = $model->class;
            $implementation = $class::findOne($this->id);
            $this->stdout($implementation->title . "\n");
            $this->stdout($implementation->url . "\n");
            $this->stdout($implementation->class . "\n");
            $this->stdout("Number of rows:" . $implementation->count . "\n");
            $this->stdout("Start import to excel file...\n");

            $fileName = $implementation->saveToExcelFile(true);
            $this->stdout("Excel file created: " . $fileName . "\n");
            $this->stdout("Start load from excel file " . $fileName . "\n");
            $result = $implementation->loadItemsFromExcelFile($fileName);
            foreach ($result['errors'] as $error) {
                $this->stdout("Error: " . $error . "\n");
            }
            $this->stdout("All: " . $result['all_amount'] . "\n");
            $this->stdout("Saved: " . $result['save_amount'] . "\n");

            $this->stdout("Try export to Yandex.Market...\n");
            $exportYandexMarketId = (int)Yii::$app->getModule('admin')->activeModules['yml']->settings['exportYandexMarketId'];
            $this->stdout("exportYandexMarketId: ".$exportYandexMarketId."\n");
            if ($exportYandexMarketId) {
                if ($model = Export::findOne($exportYandexMarketId)) {
                    $class = $model->class;
                    $implementation = $class::findOne($exportYandexMarketId);
                    $this->stdout($implementation->title . "\n");
                    $this->stdout($implementation->class . "\n");
                    $this->stdout("Number of rows:" . $implementation->count . "\n");

                    $fileName = $implementation->saveToYmlFile();

                    $this->stdout("File created: " . $fileName . "\n");
                    $this->stdout("Export completed\n");
                } else {
                    $this->stdout("Export model not found\n", 91);
                }
            }

            $this->stdout("DONE\n");
        } else {
            $this->stdout("Import model not found\n", 91);
        }
    }

    public function actionExport() {

        if ($model = Export::findOne($this->id)) {
            $class = $model->class;
            $implementation = $class::findOne($this->id);
            $this->stdout($implementation->title . "\n");
            $this->stdout($implementation->class . "\n");
            $this->stdout("Number of rows:" . $implementation->count . "\n");
            $this->stdout("Start export...\n");

            if ($implementation->to_excel) {
                $fileName = $implementation->saveToExcelFile();
            } else {
                $fileName = $implementation->saveToYmlFile();
            }

            $this->stdout("File created: " . $fileName . "\n");
            $this->stdout("DONE\n");
        } else {
            $this->stdout("Export model not found\n", 91);
        }
    }

    //Добавляются новые бренды, добавляются новые и обновляются старые элементы каталога, категории должны быть созданы, в категориях должен быть указан "Тип элемента"
    public function actionLoadItemsFromExcelFile() {
        if ($model = Import::findOne($this->id)) {
            $class = $model->class;
            $implementation = $class::findOne($this->id);
            $this->stdout($implementation->title . "\n");
            $this->stdout($implementation->url . "\n");
            $this->stdout($implementation->class . "\n");
            $this->stdout("Number of rows:" . $implementation->count . "\n");
            $this->stdout("Start load from excel file...\n");

            $result = $implementation->loadItemsFromExcelFile($this->file_name);
            foreach ($result['errors'] as $error) {
                $this->stdout("Error: " . $error . "\n");
            }
            $this->stdout("All: " . $result['all_amount'] . "\n");
            $this->stdout("Saved: " . $result['save_amount'] . "\n");
            $this->stdout("DONE\n");
        } else {
            $this->stdout("Import model not found\n", 91);
        }
    }

    //Добавляются новые бренды, добавляются новые элементы каталога, категории должны быть созданы, в категориях должен быть указан "Тип элемента"
    public function actionAddItemsFromExcelFile() {

        $this->stdout("Start add new items from excel file...\n");

        $result = Import::addItemsFromExcelFile($this->file_name);
        foreach ($result['errors'] as $error) {
            $this->stdout("Error: " . $error . "\n");
        }
        $this->stdout("All: " . $result['all_amount'] . "\n");
        $this->stdout("Added: " . $result['save_amount'] . "\n");
        $this->stdout("DONE\n");
    }
    
    //Обновляются существующие элементы каталога, колонки "Закупочная цена","Цена","Статус","Описание" и колонки с доп. характеристиками
    public function actionUpdateItemsFromExcelFile() {

        $this->stdout("Start update from excel file...\n");

        $result = Import::updateItemsFromExcelFile($this->file_name);
        foreach ($result['errors'] as $error) {
            $this->stdout("Error: " . $error . "\n");
        }
        $this->stdout("All: " . $result['all_amount'] . "\n");
        $this->stdout("Saved: " . $result['save_amount'] . "\n");
        $this->stdout("DONE\n");
    }

    public function actionLoadCategoriesFromExcelFile() {

        $this->stdout("Start load from excel file...\n");

        $result = Import::loadCategoriesFromExcelFile($this->file_name);
        foreach ($result['errors'] as $error) {
            $this->stdout("Error: " . $error . "\n");
        }
        $this->stdout("All: " . $result['all_amount'] . "\n");
        $this->stdout("Saved: " . $result['save_amount'] . "\n");
        $this->stdout("DONE\n");
    }

    public function actionLoadNewsFromExcelFile() {

        $this->stdout("Start load from excel file...\n");

        $result = Import::loadNewsFromExcelFile($this->file_name);
        foreach ($result['errors'] as $error) {
            $this->stdout("Error: " . $error . "\n");
        }
        $this->stdout("All: " . $result['all_amount'] . "\n");
        $this->stdout("Saved: " . $result['save_amount'] . "\n");
        $this->stdout("DONE\n");
    }

    public function actionLoadUsersFromExcelFile() {

        $this->stdout("Start load from excel file...\n");

        $result = Import::loadUsersFromExcelFile($this->file_name);
        foreach ($result['errors'] as $error) {
            $this->stdout("Error: " . $error . "\n");
        }
        $this->stdout("All: " . $result['all_amount'] . "\n");
        $this->stdout("Saved: " . $result['save_amount'] . "\n");
        $this->stdout("DONE\n");
    }

}
