<?
use yii\db\Schema;

class m000000_000000_sitemap_install extends \yii\db\Migration
{
    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up() {
        //SITEMAP MODULE
        $this->createTable('admin_module_sitemap', [
            'id' => 'pk',
            'class' => Schema::TYPE_STRING . '(256) DEFAULT NULL',
            'data' => Schema::TYPE_TEXT . ' NOT NULL',            
            ], $this->engine);
    }

    public function down() {
        $this->dropTable('admin_module_sitemap');
    }
}
