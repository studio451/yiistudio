<?

namespace admin\modules\comment\assets;

use yii\web\AssetBundle;

/**
 * Class CommentAsset
 *
 */
class RatingAsset extends AssetBundle
{
    
    public $sourcePath = '@admin/modules/comment/media';
    public $css = [
        'css/rating.css',
    ];
    public $js = [
        'js/rating.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];    
}
