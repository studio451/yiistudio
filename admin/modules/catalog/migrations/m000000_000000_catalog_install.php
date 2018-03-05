<?

use yii\db\Schema;
use admin\modules\catalog;

class m000000_000000_catalog_install extends \yii\db\Migration {

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up() {
        //CATALOG
        $this->createTable(catalog\models\Category::tableName(), [
            'id' => 'pk',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'image' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'description' => Schema::TYPE_TEXT . ' DEFAULT NULL',
            'fields' => Schema::TYPE_TEXT . ' NOT NULL',
            'tree' => Schema::TYPE_INTEGER,
            'lft' => Schema::TYPE_INTEGER,
            'rgt' => Schema::TYPE_INTEGER,
            'depth' => Schema::TYPE_INTEGER,
            'order_num' => Schema::TYPE_INTEGER,
            'time' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'",
                ], $this->engine);
        $this->createIndex('slug', catalog\models\Category::tableName(), 'slug', true);

        $this->createTable(catalog\models\Group::tableName(), [
            'id' => 'pk',
            'category_id' => Schema::TYPE_INTEGER,
            'brand_id' => Schema::TYPE_INTEGER,            
            'name' => Schema::TYPE_STRING . '(128) NOT NULL',
            'time' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'",
            'external_name' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
                ], $this->engine);

        $this->createTable(catalog\models\Item::tableName(), [
            'id' => 'pk',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'image' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'description' => Schema::TYPE_TEXT . ' DEFAULT NULL',
            'group_id' => Schema::TYPE_INTEGER,
            'category_id' => Schema::TYPE_INTEGER,
            'type' => Schema::TYPE_STRING . '(128) NOT NULL',
            'brand_id' => Schema::TYPE_INTEGER,
            'name' => Schema::TYPE_STRING . '(128) NOT NULL',
            'article' => Schema::TYPE_STRING . '(128) NOT NULL',
            'available' => Schema::TYPE_INTEGER . " DEFAULT '1'",
            'price' => Schema::TYPE_FLOAT . " DEFAULT '0'",
            'base_price' => Schema::TYPE_FLOAT . " DEFAULT '0'",
            'discount' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'data' => Schema::TYPE_TEXT . ' NOT NULL',
            'time' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'",
            'external_id' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'external_name' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
                ], $this->engine);
        $this->createIndex('slug', catalog\models\Item::tableName(), 'slug', true);

        $this->createTable(catalog\models\ItemData::tableName(), [
            'id' => 'pk',
            'item_id' => Schema::TYPE_INTEGER,
            'name' => Schema::TYPE_STRING . '(128) NOT NULL',
            'value' => Schema::TYPE_STRING . '(1024) DEFAULT NULL',
                ], $this->engine);
        $this->createIndex('item_id_name', catalog\models\ItemData::tableName(), ['item_id', 'name']);
        $this->createIndex('value', catalog\models\ItemData::tableName(), 'value(300)');

        //Brands
        $this->createTable(catalog\models\Brand::tableName(), [
            'id' => 'pk',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'image' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'description' => Schema::TYPE_TEXT . ' DEFAULT NULL',
            'time' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'"
                ], $this->engine);
        $this->createIndex('slug', catalog\models\Brand::tableName(), 'slug', true);
    }

    public function down() {
        $this->dropTable(catalog\models\Category::tableName());
        $this->dropTable(catalog\models\Group::tableName());
        $this->dropTable(catalog\models\Item::tableName());
        $this->dropTable(catalog\models\ItemData::tableName());
        $this->dropTable(catalog\models\Brand::tableName());
    }

}
