<?

use yii\db\Schema;
use admin\modules\catalog;

class m170915_204000_catalog_add_brand_short extends \yii\db\Migration {

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up() {
        $this->addColumn(catalog\models\Brand::tableName(), 'short', Schema::TYPE_TEXT . ' DEFAULT NULL');
    }

    public function down() {
        $this->dropColumn(catalog\models\Brand::tableName(), 'short');
    }

}
