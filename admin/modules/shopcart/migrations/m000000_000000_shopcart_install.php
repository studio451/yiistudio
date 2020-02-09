<?

use yii\db\Schema;
use admin\modules\shopcart;

class m000000_000000_shopcart_install extends \yii\db\Migration {

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up() {
        //SHOPCART MODULE
        $this->createTable(shopcart\models\Order::tableName(), [
            'id' => 'pk',
            'user_id' => Schema::TYPE_INTEGER,
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'address' => Schema::TYPE_STRING . '(255) NOT NULL',
            'phone' => Schema::TYPE_STRING . '(64) NOT NULL',
            'email' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'comment' => Schema::TYPE_STRING . '(1024) NOT NULL',
            'remark' => Schema::TYPE_STRING . '(1024) DEFAULT NULL',
            'delivery_id' => Schema::TYPE_INTEGER,
            'delivery_cost' => Schema::TYPE_FLOAT . " DEFAULT '0'",
            'delivery_details' => Schema::TYPE_STRING . '(1024) NULL',
            'payment_id' => Schema::TYPE_INTEGER,
            'payment_details' => Schema::TYPE_STRING . '(1024) NULL',
            'paid_status' => Schema::TYPE_BOOLEAN . " DEFAULT '0'",
            'paid_time' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'paid_details' => Schema::TYPE_STRING . '(1024) NULL',
            'discount' => Schema::TYPE_FLOAT . " DEFAULT '0'",
            'data' => Schema::TYPE_TEXT . ' NOT NULL',
            'access_token' => Schema::TYPE_STRING . '(32) NOT NULL',
            'ip' => Schema::TYPE_STRING . '(16) NOT NULL',
            'time' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'new' => Schema::TYPE_BOOLEAN . " DEFAULT '0'",
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '0'"
                ], $this->engine);

        $this->createTable(shopcart\models\Good::tableName(), [
            'id' => 'pk',
            'order_id' => Schema::TYPE_INTEGER,
            'item_id' => Schema::TYPE_INTEGER,
            'count' => Schema::TYPE_INTEGER,
            'options' => Schema::TYPE_STRING . '(255) NOT NULL',
            'price' => Schema::TYPE_FLOAT . " DEFAULT '0'",
            'discount' => Schema::TYPE_INTEGER . " DEFAULT '0'",
                ], $this->engine);
    }

    public function down() {
        $this->dropTable(shopcart\models\Order::tableName());
        $this->dropTable(shopcart\models\Good::tableName());
    }

}
