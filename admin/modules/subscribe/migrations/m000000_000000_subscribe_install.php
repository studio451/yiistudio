<?

use yii\db\Schema;
use admin\modules\subscribe;

class m000000_000000_subscribe_install extends \yii\db\Migration {

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up() {
        //SUBSCRIBE MODULE
        $this->createTable(subscribe\models\Subscriber::tableName(), [
            'id' => 'pk',
            'email' => Schema::TYPE_STRING . '(128) NOT NULL',
            'ip' => Schema::TYPE_STRING . '(16) NOT NULL',
            'time' => Schema::TYPE_INTEGER . " DEFAULT '0'"
                ], $this->engine);
        $this->createIndex('email', subscribe\models\Subscriber::tableName(), 'email', true);

        $this->createTable(subscribe\models\History::tableName(), [
            'id' => 'pk',
            'subject' => Schema::TYPE_STRING . '(128) NOT NULL',
            'body' => Schema::TYPE_TEXT . ' NOT NULL',
            'sent' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'time' => Schema::TYPE_INTEGER . " DEFAULT '0'"
                ], $this->engine);
    }

    public function down() {
        $this->dropTable(subscribe\models\Subscriber::tableName());
        $this->dropTable(subscribe\models\History::tableName());
    }

}
