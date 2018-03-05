<?

use yii\db\Schema;
use admin\modules\subscribe;

class m180220_121700_subscribe_add_mailing_list_status extends \yii\db\Migration {

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up() {
        $this->addColumn(subscribe\models\History::tableName(), 'mailing_list', Schema::TYPE_TEXT . ' DEFAULT NULL');
        $this->addColumn(subscribe\models\History::tableName(), 'status', Schema::TYPE_INTEGER . " DEFAULT '0'");
    }

    public function down() {
        $this->dropColumn(subscribe\models\History::tableName(), 'mailing_list');
        $this->dropColumn(subscribe\models\History::tableName(), 'status');
    }

}
