<?
use yii\db\Schema;

use admin\modules\news;

class m000000_000000_news_install extends \yii\db\Migration
{
    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';
    
    public function up()
    {
        //NEWS MODULE
        $this->createTable(news\models\News::tableName(), [
            'id' => 'pk',
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'image' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'short' => Schema::TYPE_STRING . '(1024) DEFAULT NULL',
            'text' => Schema::TYPE_TEXT . ' NOT NULL',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'time' => Schema::TYPE_INTEGER .  " DEFAULT '0'",
            'views' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'"
        ], $this->engine);
        $this->createIndex('slug', news\models\News::tableName(), 'slug', true);            
    }

    public function down()
    {
        $this->dropTable(news\models\News::tableName());        
    }
}
