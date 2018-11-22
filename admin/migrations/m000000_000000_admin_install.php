<?

use yii\db\Schema;
use admin\models;

class m000000_000000_admin_install extends \yii\db\Migration {

    const VERSION = 0.925;

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up() {

        //TRANSLATE
        $this->createTable(models\TranslateSourceMessage::tableName(), [
            'id' => $this->primaryKey(),
            'category' => $this->string(),
            'message' => $this->text(),
                ], $this->engine);

        $this->createTable(models\TranslateMessage::tableName(), [
            'id' => $this->integer()->notNull(),
            'language' => $this->string(16)->notNull(),            
            'translation' => $this->text(),
                ], $this->engine);

        $this->addPrimaryKey('pk_message_id_language', models\TranslateMessage::tableName(), ['id', 'language']);
        $this->addForeignKey('fk_message_source_message', models\TranslateMessage::tableName(), 'id', models\TranslateSourceMessage::tableName(), 'id', 'CASCADE', 'RESTRICT');
        $this->createIndex('idx_source_message_category', models\TranslateSourceMessage::tableName(), 'category');
        $this->createIndex('idx_message_language', models\TranslateMessage::tableName(), 'language');


        //USERS
        $this->createTable(models\User::tableName(), [
            'id' => 'pk',
            'email' => Schema::TYPE_STRING . '(255) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . '(255) NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING . '(255) NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'access_token' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'data' => Schema::TYPE_TEXT . ' NOT NULL',
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '0'",
            'created_at' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'updated_at' => Schema::TYPE_INTEGER . " DEFAULT '0'",
                ], $this->engine);

        $this->createIndex('access_token', models\User::tableName(), 'access_token', true);

        //LOGINFORM
        $this->createTable(models\api\LoginForm::tableName(), [
            'id' => 'pk',
            'email' => Schema::TYPE_STRING . '(128) NOT NULL',
            'password' => Schema::TYPE_STRING . '(128) NOT NULL',
            'ip' => Schema::TYPE_STRING . '(16) NOT NULL',
            'user_agent' => Schema::TYPE_STRING . '(1024) NOT NULL',
            'time' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'success' => Schema::TYPE_BOOLEAN . " DEFAULT '0'"
                ], $this->engine);

        //MODULES
        $this->createTable(models\Module::tableName(), [
            'id' => 'pk',
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'class' => Schema::TYPE_STRING . '(128) NOT NULL',
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'icon' => Schema::TYPE_STRING . '(32) NOT NULL',
            'settings' => Schema::TYPE_TEXT . ' NOT NULL',
            'notice' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'order_num' => Schema::TYPE_INTEGER,
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '0'"
                ], $this->engine);
        $this->createIndex('name', models\Module::tableName(), 'name', true);

        //PHOTO
        $this->createTable(models\Photo::tableName(), [
            'id' => 'pk',
            'class' => Schema::TYPE_STRING . '(128) NOT NULL',
            'item_id' => Schema::TYPE_INTEGER . " NOT NULL",
            'image' => Schema::TYPE_STRING . '(512) NOT NULL',
            'description' => Schema::TYPE_STRING . '(1024) NOT NULL',
            'order_num' => Schema::TYPE_INTEGER . " NOT NULL",
                ], $this->engine);
        $this->createIndex('model_item', models\Photo::tableName(), ['class', 'item_id']);

        //SETTINGS
        $this->createTable(models\Setting::tableName(), [
            'id' => 'pk',
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'value' => Schema::TYPE_STRING . '(1024) DEFAULT NULL',
            'visibility' => Schema::TYPE_BOOLEAN . " DEFAULT '0'",
                ], $this->engine);
        $this->createIndex('name', models\Setting::tableName(), 'name', true);

        //TAGS
        $this->createTable(models\Tag::tableName(), [
            'id' => 'pk',
            'name' => Schema::TYPE_STRING . '(128) NOT NULL',
            'frequency' => Schema::TYPE_INTEGER . " DEFAULT '0'"
                ], $this->engine);
        $this->createIndex('name', models\Tag::tableName(), 'name', true);

        //TAGASSIGN
        $this->createTable(models\TagAssign::tableName(), [
            'class' => Schema::TYPE_STRING . '(128) NOT NULL',
            'item_id' => Schema::TYPE_INTEGER . " NOT NULL",
            'tag_id' => Schema::TYPE_INTEGER . " NOT NULL",
                ], $this->engine);
        $this->createIndex('class', models\TagAssign::tableName(), 'class');
        $this->createIndex('item_tag', models\TagAssign::tableName(), ['item_id', 'tag_id']);

        //INSERT VERSION
        $this->delete(models\Setting::tableName(), ['name' => 'admin_version']);
        $this->insert(models\Setting::tableName(), [
            'name' => 'admin_version',
            'value' => self::VERSION,
            'title' => 'Version',
            'visibility' => models\Setting::VISIBLE_NONE
        ]);
    }

    public function down() {
        $this->dropTable(models\User::tableName());
        $this->dropTable(models\LoginForm::tableName());
        $this->dropTable(models\Module::tableName());
        $this->dropTable(models\Photo::tableName());
        $this->dropTable(models\SeoText::tableName());
        $this->dropTable(models\SearchText::tableName());
        $this->dropTable(models\Setting::tableName());
        $this->dropTable(models\Tag::tableName());
        $this->dropTable(models\TagAssign::tableName());

        $this->dropTable(models\TranslateMessage::tableName());
        $this->dropTable(models\TranslateSourceMessage::tableName());
    }

}
