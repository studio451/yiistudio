<?
use yii\db\Schema;

class m000000_000000_yml_install extends \yii\db\Migration
{
    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up() {
        //YML MODULE
        $this->createTable('admin_module_yml_export', [
            'id' => 'pk',
            'title' => Schema::TYPE_STRING . '(256) DEFAULT NULL',
            'data' => Schema::TYPE_TEXT . ' NOT NULL',            
            ], $this->engine);
        
         $this->createTable('admin_module_yml_import', [
            'id' => 'pk',
            'title' => Schema::TYPE_STRING . '(256) DEFAULT NULL',
            'data' => Schema::TYPE_TEXT . ' NOT NULL',            
            ], $this->engine);
    }

    public function down() {
        $this->dropTable('admin_module_yml_export');
        $this->dropTable('admin_module_yml_import');
    }
}
