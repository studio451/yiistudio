<?

namespace admin\modules\block\api;

use Yii;
use admin\helpers\Data;
use admin\modules\block\models\Block as BlockModel;

/**
 * Block module Api
 * @package admin\modules\block\api
 *
 * @method static BlockObject get(mixed $id_slug) Get block object by id or slug
 */
class Block extends \admin\base\Api {

    private $_blocks = [];

    public function init() {
        parent::init();

        $this->_blocks = Data::cache(BlockModel::CACHE_KEY, 3600, function() {
                    return BlockModel::find()->all();
                });
    }

    public function api_get($id_slug) {
        foreach ($this->_blocks as $block) {
            if ($block->slug == $id_slug || $block->id == $id_slug) {
                return new BlockObject($block);
            }
        }
        return $this->notFound($id_slug);
    }

    private function notFound($id_slug) {
        $block = new BlockModel([
            'slug' => $id_slug
        ]);
        return new BlockObject($block);
    }

}
