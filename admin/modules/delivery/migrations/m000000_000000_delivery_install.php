<?

use yii\db\Schema;
use admin\modules\delivery;

class m000000_000000_delivery_install extends \yii\db\Migration {

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up() {
        //DELIVERY MODULE
        $this->createTable(delivery\models\Delivery::tableName(), [
            'id' => 'pk',
            'title' => Schema::TYPE_STRING . '(256) DEFAULT NULL',
            'description' => Schema::TYPE_TEXT . ' DEFAULT NULL',
            'price' => Schema::TYPE_FLOAT . " DEFAULT '0'",
            'free_from' => Schema::TYPE_FLOAT . " DEFAULT '0'",
            'available_from' => Schema::TYPE_FLOAT . " DEFAULT '0'",
            'need_address' => Schema::TYPE_BOOLEAN . " DEFAULT '0'",
            'order_num' => Schema::TYPE_INTEGER,
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'",
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL'
                ], $this->engine);

        $this->createTable(delivery\models\DeliveryPayment::tableName(), [
            'delivery_id' => Schema::TYPE_INTEGER,
            'payment_id' => Schema::TYPE_INTEGER,
                ], $this->engine);
    }

    public function down() {
        $this->dropTable(delivery\models\Delivery::tableName());
        $this->dropTable(delivery\models\DeliveryPayment::tableName());
    }

}
