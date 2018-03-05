<?

use yii\db\Schema;
use admin\modules\block;

class m000000_000000_block_install extends \yii\db\Migration {

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up() {
        //BLOCK MODULE
        $this->createTable(block\models\Block::tableName(), [
            'id' => 'pk',
            'text' => Schema::TYPE_TEXT . ' NOT NULL',
            'assets_css' => Schema::TYPE_TEXT . ' NOT NULL',
            'assets_js' => Schema::TYPE_TEXT . ' NOT NULL',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL'
                ], $this->engine);
        $this->createIndex('slug', block\models\Block::tableName(), 'slug', true);
    }

    public function down() {
        $this->dropTable(block\models\Block::tableName());
    }

}
