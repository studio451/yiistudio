<?

use yii\db\Schema;
use admin\modules\catalog;

class m170926_142100_catalog_add_order_num extends \yii\db\Migration {

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up() {
        $this->addColumn(catalog\models\Item::tableName(), 'order_num', Schema::TYPE_INTEGER);
    }

    public function down() {
        $this->dropColumn(catalog\models\Item::tableName(), 'order_num');
    }

}
