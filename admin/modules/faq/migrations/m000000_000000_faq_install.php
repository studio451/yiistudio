<?
use yii\db\Schema;

use admin\modules\faq;

class m000000_000000_faq_install extends \yii\db\Migration
{
    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';
    
    public function up()
    {
        //FAQ MODULE
        $this->createTable(faq\models\Faq::tableName(), [
            'id' => 'pk',
            'question' => Schema::TYPE_TEXT . ' NOT NULL',
            'answer' => Schema::TYPE_TEXT . ' NOT NULL',
            'order_num' => Schema::TYPE_INTEGER,
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'"
        ], $this->engine);         
    }

    public function down()
    {
        $this->dropTable(faq\models\Faq::tableName());        
    }
}
