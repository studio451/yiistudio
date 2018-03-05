<?
namespace admin\modules\catalog\assets;

class FieldsAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@admin/modules/catalog/media';
    public $css = [
        'css/fields.css',
    ];
    public $js = [
        'js/fields.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
