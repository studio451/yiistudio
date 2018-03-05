<?
use yii\db\Schema;

use admin\modules\page;

class m000000_000000_page_install extends \yii\db\Migration
{
    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';
    
    public function up()
    {        
        //PAGE MODULE
        $this->createTable(page\models\Page::tableName(), [
            'id' => 'pk',
            'title' => Schema::TYPE_STRING . '(256) NOT NULL',
            'text' => Schema::TYPE_TEXT . ' NOT NULL',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL'
        ], $this->engine);
        $this->createIndex('slug', page\models\Page::tableName(), 'slug', true);           
    }

    public function down()
    {
        $this->dropTable(page\models\Page::tableName());        
    }
}
