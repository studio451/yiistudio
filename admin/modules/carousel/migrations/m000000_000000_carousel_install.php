<?

use yii\db\Schema;
use admin\modules\carousel;

class m000000_000000_carousel_install extends \yii\db\Migration {

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up() {
        //CAROUSEL MODULE
        $this->createTable(carousel\models\Carousel::tableName(), [
            'id' => 'pk',
            'image' => Schema::TYPE_STRING . '(128) NOT NULL',
            'link' => Schema::TYPE_STRING . '(255) NOT NULL',
            'title' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'text' => Schema::TYPE_TEXT . ' DEFAULT NULL',
            'order_num' => Schema::TYPE_INTEGER,
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'"
                ], $this->engine);
    }

    public function down() {
        $this->dropTable(carousel\models\Carousel::tableName());
    }

}
