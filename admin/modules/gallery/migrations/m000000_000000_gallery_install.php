<?

use yii\db\Schema;
use admin\modules\gallery;

class m000000_000000_gallery_install extends \yii\db\Migration {

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up() {
        //GALLERY MODULE
        $this->createTable(gallery\models\Category::tableName(), [
            'id' => 'pk',
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'image' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'tree' => Schema::TYPE_INTEGER,
            'lft' => Schema::TYPE_INTEGER,
            'rgt' => Schema::TYPE_INTEGER,
            'depth' => Schema::TYPE_INTEGER,
            'order_num' => Schema::TYPE_INTEGER,
            'time' => Schema::TYPE_INTEGER . " DEFAULT '0'",         
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'"
                ], $this->engine);
        $this->createIndex('slug', gallery\models\Category::tableName(), 'slug', true);
    }

    public function down() {
        $this->dropTable(gallery\models\Category::tableName());
    }

}
