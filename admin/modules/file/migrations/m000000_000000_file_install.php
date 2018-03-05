<?

use yii\db\Schema;
use admin\modules\file;

class m000000_000000_file_install extends \yii\db\Migration {

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up() {
        //FILE MODULE
        $this->createTable(file\models\File::tableName(), [
            'id' => 'pk',
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'file' => Schema::TYPE_STRING . '(255) NOT NULL',
            'size' => Schema::TYPE_INTEGER . ' NOT NULL',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'downloads' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'time' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'order_num' => Schema::TYPE_INTEGER,
                ], $this->engine);
        $this->createIndex('slug', file\models\File::tableName(), 'slug', true);
    }

    public function down() {
        $this->dropTable(file\models\File::tableName());
    }

}
