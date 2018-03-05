<?

namespace admin\modules\comment\assets;

use yii\web\AssetBundle;

/**
 * Class CommentAsset
 *
 */
class CommentAsset extends AssetBundle
{
    
    public $sourcePath = '@admin/modules/comment/media';
    public $css = [
        'css/comment.css',
    ];
    public $js = [
        'js/comment.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];    
}
