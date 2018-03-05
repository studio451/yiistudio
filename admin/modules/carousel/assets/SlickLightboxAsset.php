<?
/**
 * Asset manager for Slick widget.
 *
 */

namespace admin\modules\carousel\assets;

use yii\web\AssetBundle;

class SlickLightboxAsset extends AssetBundle
{
    public $sourcePath = '@bower/slick-lightbox/dist/';

    public $css = [
        'slick-lightbox.css',
    ];

    public $js = [
        'slick-lightbox.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
} 