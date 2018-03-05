<?

use yii\db\Schema;
use admin\modules\payment;

class m000000_000000_payment_install extends \yii\db\Migration {

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up() {
        //PAYMENT MODULE
        $this->createTable(payment\models\Payment::tableName(), [
            'id' => 'pk',
            'title' => Schema::TYPE_STRING . '(256) DEFAULT NULL',
            'description' => Schema::TYPE_TEXT . ' DEFAULT NULL',
            'available_to' => Schema::TYPE_FLOAT . " DEFAULT '0'",
            'is_manual' => Schema::TYPE_BOOLEAN . " DEFAULT '1'",
            'order_num' => Schema::TYPE_INTEGER,
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'",
            'class' => Schema::TYPE_STRING . '(256) DEFAULT NULL',
            'data' => Schema::TYPE_TEXT . ' NOT NULL',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL'
                ], $this->engine);

        $this->createTable(payment\models\Checkout::tableName(), [
            'id' => 'pk',
            'time' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'status' => Schema::TYPE_INTEGER,
            'payment_id' => Schema::TYPE_INTEGER,
            'payment_title' => Schema::TYPE_STRING . '(256) DEFAULT NULL',
            'order_id' => Schema::TYPE_INTEGER,
            'description' => Schema::TYPE_TEXT . ' DEFAULT NULL',
            'request' => Schema::TYPE_TEXT . ' DEFAULT NULL',
                ], $this->engine);
    }

    public function down() {
        $this->dropTable(payment\models\Payment::tableName());
        $this->dropTable(payment\models\Checkout::tableName());
    }

}
