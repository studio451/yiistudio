<?

use yii\db\Schema;
use admin\modules\catalog;

class m170822_114800_catalog_add_gift_new extends \yii\db\Migration {

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up() {
        $this->addColumn(catalog\models\Item::tableName(), 'gift', Schema::TYPE_INTEGER . " DEFAULT '0'");
        $this->addColumn(catalog\models\Item::tableName(), 'new', Schema::TYPE_INTEGER . " DEFAULT '0'");
    }

    public function down() {
        $this->dropColumn(catalog\models\Item::tableName(), 'gift');
        $this->dropColumn(catalog\models\Item::tableName(), 'new');
    }

}
