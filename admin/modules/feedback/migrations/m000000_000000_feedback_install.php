<?
use yii\db\Schema;

use admin\modules\feedback;

class m000000_000000_feedback_install extends \yii\db\Migration
{
    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';
    
    public function up()
    {
        //FEEDBACK MODULE
        $this->createTable(feedback\models\Feedback::tableName(), [
            'id' => 'pk',
            'title' => Schema::TYPE_STRING . '(256) DEFAULT NULL',
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'email' => Schema::TYPE_STRING . '(128) NOT NULL',
            'phone' => Schema::TYPE_STRING . '(64) DEFAULT NULL',
            'type' => Schema::TYPE_INTEGER,
            'text' => Schema::TYPE_TEXT . ' NOT NULL',
            'answer_subject' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'answer_text' => Schema::TYPE_TEXT . ' DEFAULT NULL',
            'time' => Schema::TYPE_INTEGER .  " DEFAULT '0'",
            'ip' => Schema::TYPE_STRING . '(16) NOT NULL',
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '0'",
        ], $this->engine);         
    }

    public function down()
    {
        $this->dropTable(feedback\models\Feedback::tableName());        
    }
}
