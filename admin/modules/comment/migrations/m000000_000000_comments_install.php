<?

use yii\db\Schema;
use admin\modules\comment;

class m000000_000000_comments_install extends \yii\db\Migration {

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up() {
        //COMMENTS MODULE
        $this->createTable(comment\models\Comment::tableName(), [
            'id' => 'pk',
            'entity' => Schema::TYPE_STRING . "(10) NOT NULL",
            'entityId' => Schema::TYPE_INTEGER . " NOT NULL",
            'content' => Schema::TYPE_TEXT . " NOT NULL",
            'parentId' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'level' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT '1'",
            'createdBy' => Schema::TYPE_INTEGER . " NOT NULL",
            'updatedBy' => Schema::TYPE_INTEGER . " NOT NULL",
            'status' => Schema::TYPE_BOOLEAN . " NOT NULL DEFAULT '1'",
            'createdAt' => Schema::TYPE_INTEGER . " NOT NULL",
            'updatedAt' => Schema::TYPE_INTEGER . " NOT NULL",
            'relatedTo' => Schema::TYPE_STRING . "(500) NOT NULL",
            'url' => Schema::TYPE_TEXT,
                ], $this->engine);

        $this->createIndex('entity', comment\models\Comment::tableName(), 'entity');
        $this->createIndex('status', comment\models\Comment::tableName(), 'status');

        $this->createTable(comment\models\Rating::tableName(), [
            'id' => 'pk',
            'entity' => Schema::TYPE_STRING . "(10) NOT NULL",
            'entityId' => Schema::TYPE_INTEGER . " NOT NULL",
            'session' => Schema::TYPE_STRING . '(128) NOT NULL',
            'value' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT '1'",
            'createdBy' => Schema::TYPE_INTEGER . " DEFAULT NULL",
            'updatedBy' => Schema::TYPE_INTEGER . "  DEFAULT NULL",            
            'createdAt' => Schema::TYPE_INTEGER . " NOT NULL",
            'updatedAt' => Schema::TYPE_INTEGER . " NOT NULL",
                ], $this->engine);

        $this->createIndex('entity', comment\models\Rating::tableName(), 'entity');
        $this->createIndex('entityId', comment\models\Rating::tableName(), 'entityId');
        $this->createIndex('session', comment\models\Rating::tableName(), 'session');
    }

    public function down() {
        $this->dropTable(comment\models\Comment::tableName());
        $this->dropTable(comment\models\Rating::tableName());
    }

}
