<?
/**
 * Asset manager for Slick widget.
 *
 */

namespace admin\modules\carousel\assets;

use yii\web\AssetBundle;

class SlickAsset extends AssetBundle
{
    public $sourcePath = '@bower/slick-carousel/slick/';

    public $css = [
        'slick.css',
        'slick-theme.css',
    ];

    public $js = [
        'slick.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
} 