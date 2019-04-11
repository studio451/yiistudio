<?

namespace admin\modules\page\api;

use Yii;
use admin\helpers\Data;
use admin\modules\page\models\Page as PageModel;

/**
 * Page module Api
 * @package admin\modules\page\api
 *
 * @method static PageObject get(mixed $id_slug) Get page object by id or slug
 */
class Page extends \admin\base\Api {

    private $_pages = [];

    public function init() {
        parent::init();

        $this->_pages = Data::cache(PageModel::CACHE_KEY, 3600, function() {
                    return PageModel::find()->all();
                });
    }

    public function api_get($id_slug) {
        foreach ($this->_pages as $page) {
            if ($page->slug == $id_slug || $page->id == $id_slug) {
                return new PageObject($page);
            }
        }
        return $this->notFound($id_slug);
    }

    private function notFound($id_slug) {
        $page = new PageModel([
            'slug' => $id_slug
        ]);
        return new PageObject($page);
    }

}
