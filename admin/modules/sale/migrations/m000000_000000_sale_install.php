<?
use yii\db\Schema;

use admin\modules\sale;

class m000000_000000_sale_install extends \yii\db\Migration
{
    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';
    
    public function up()
    {
        //SALE MODULE
        $this->createTable(sale\models\Sale::tableName(), [
            'id' => 'pk',
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'image' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'short' => Schema::TYPE_STRING . '(1024) DEFAULT NULL',
            'banner_background_color' => Schema::TYPE_STRING . '(64) DEFAULT NULL',
            'banner_border_color' => Schema::TYPE_STRING . '(64) DEFAULT NULL',            
            'banner_title' => Schema::TYPE_STRING . '(512) DEFAULT NULL',
            'banner_content_text' => Schema::TYPE_STRING . '(1024) DEFAULT NULL',
            'banner_content_button' => Schema::TYPE_STRING . '(512) DEFAULT NULL',
            'text' => Schema::TYPE_TEXT . ' NOT NULL',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'time' => Schema::TYPE_INTEGER .  " DEFAULT '0'",
            'views' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'"
        ], $this->engine);
        $this->createIndex('slug', sale\models\Sale::tableName(), 'slug', true);            
    }

    public function down()
    {
        $this->dropTable(sale\models\Sale::tableName());        
    }
}
