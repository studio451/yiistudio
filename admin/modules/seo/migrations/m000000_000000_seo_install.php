<?

use yii\db\Schema;
use admin\modules\seo;

class m000000_000000_seo_install extends \yii\db\Migration {

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up() {
        //SEO
        $this->createTable(seo\models\SeoText::tableName(), [
            'id' => 'pk',
            'class' => Schema::TYPE_STRING . '(128) NOT NULL',
            'item_id' => Schema::TYPE_INTEGER . " NOT NULL",
            'h1' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'title' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'keywords' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'description' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
                ], $this->engine);
        $this->createIndex('model_item', seo\models\SeoText::tableName(), ['class', 'item_id'], true);

        $this->createTable(seo\models\SeoTemplateAssign::tableName(), [
            'class' => Schema::TYPE_STRING . '(128) NOT NULL',
            'item_id' => Schema::TYPE_INTEGER . " NOT NULL",
            'template_id' => Schema::TYPE_INTEGER . " DEFAULT NULL",
            'item_template_id' => Schema::TYPE_INTEGER . " DEFAULT NULL",
                ], $this->engine);
        $this->addPrimaryKey('pk_class_item_id', seo\models\SeoTemplateAssign::tableName(), ['class', 'item_id']);
        $this->createIndex('class', seo\models\SeoTemplateAssign::tableName(), 'class');
        $this->createIndex('item_template', seo\models\SeoTemplateAssign::tableName(), ['item_id', 'template_id', 'item_template_id']);


        $this->createTable(seo\models\SeoTemplate::tableName(), [
            'id' => 'pk',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'title' => Schema::TYPE_STRING . '(256) DEFAULT NULL',
            'h1' => Schema::TYPE_STRING . '(256) DEFAULT NULL',
            'description' => Schema::TYPE_STRING . '(256) DEFAULT NULL',
            'keywords' => Schema::TYPE_STRING . '(256) DEFAULT NULL',
                ], $this->engine);
        $this->createIndex('slug', seo\models\SeoTemplate::tableName(), 'slug', true);
    }

    public function down() {
        $this->dropTable(seo\models\SeoText::tableName());
        $this->dropTable(seo\models\SeoTemplate::tableName());
        $this->dropTable(seo\models\SeoTemplateAssign::tableName());
    }

}
