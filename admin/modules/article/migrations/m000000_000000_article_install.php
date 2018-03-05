<?
use yii\db\Schema;

use admin\modules\article;

class m000000_000000_article_install extends \yii\db\Migration
{
    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';
    
    public function up()
    {
        //ARTICLE MODULE
        $this->createTable(article\models\Category::tableName(), [
            'id' => 'pk',
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'image' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'order_num' => Schema::TYPE_INTEGER,
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'tree' => Schema::TYPE_INTEGER,
            'lft' => Schema::TYPE_INTEGER,
            'rgt' => Schema::TYPE_INTEGER,
            'depth' => Schema::TYPE_INTEGER,
            'time' => Schema::TYPE_INTEGER . " DEFAULT '0'",            
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'"
        ], $this->engine);
        $this->createIndex('slug', article\models\Category::tableName(), 'slug', true);

        $this->createTable(article\models\Item::tableName(), [
            'id' => 'pk',
            'category_id' => Schema::TYPE_INTEGER,
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'image' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'short' => Schema::TYPE_STRING . '(1024) DEFAULT NULL',
            'text' => Schema::TYPE_TEXT . ' NOT NULL',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'time' => Schema::TYPE_INTEGER .  " DEFAULT '0'",
            'views' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'"
        ], $this->engine);
        $this->createIndex('slug', article\models\Item::tableName(), 'slug', true);  
    }

    public function down()
    {        
        $this->dropTable(article\models\Category::tableName());
        $this->dropTable(article\models\Item::tableName());        
    }
}
