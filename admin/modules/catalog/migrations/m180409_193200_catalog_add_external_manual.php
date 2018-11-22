<?

use yii\db\Schema;
use admin\modules\catalog;

class m180409_193200_catalog_add_external_manual extends \yii\db\Migration {

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up() {
        $this->addColumn(catalog\models\Item::tableName(), 'external_manual', Schema::TYPE_INTEGER . ' DEFAULT NULL');
    }

    public function down() {
        $this->dropColumn(catalog\models\Item::tableName(), 'external_manual');
    }

}
