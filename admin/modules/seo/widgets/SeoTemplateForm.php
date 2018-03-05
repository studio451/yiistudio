<?
namespace admin\modules\seo\widgets;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;

class SeoTemplateForm extends Widget
{
    public $model;

    public function init()
    {
        parent::init();

        if (empty($this->model)) {
            throw new InvalidConfigException('Required `model` param isn\'t set.');
        }
    }

    public function run()
    {
        echo $this->render('seo_template_form', [
            'model' => $this->model->_seoTemplate
        ]);
    }

}