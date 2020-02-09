<?

use yii\db\Schema;
use admin\models;

class m191205_104300_module_add_type extends \yii\db\Migration {

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up() {
        $this->addColumn(models\Module::tableName(), 'type', Schema::TYPE_STRING . '(128) DEFAULT NULL');
    }

    public function down() {
        $this->dropColumn(models\Module::tableName(), 'type');
    }

}
