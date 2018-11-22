<?
/**
 * Asset manager for Slick widget.
 *
 */

namespace admin\modules\carousel\assets;

use yii\web\AssetBundle;

class SlickLightboxAsset extends AssetBundle
{
    public $sourcePath = '@admin/modules/carousel/media/slickLightbox';

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