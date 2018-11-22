<?

use yii\db\Schema;
use admin\modules\catalog;

class m180403_090600_catalog_add_image_alt extends \yii\db\Migration {

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up() {
        $this->addColumn(catalog\models\Item::tableName(), 'image_alt', Schema::TYPE_STRING . '(128) DEFAULT NULL');
    }

    public function down() {
        $this->dropColumn(catalog\models\Item::tableName(), 'image_alt');
    }

}
