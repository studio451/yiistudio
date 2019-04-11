<?

namespace admin\modules\catalog\api;

use Yii;
use yii\helpers\Url;
use admin\base\Api;
use admin\modules\catalog\api\Catalog;

class BrandObject extends \admin\base\ApiObject {

    public $slug;
    public $image;
    public $short;
    private $_catalog;
    private $_groups;
    private $_categories;

    public function getTitle() {
        return $this->model->title;
    }

    public function getDescription() {
        return LIVE_EDIT ? Api::liveEdit($this->model->description, $this->editLink, 'div') : $this->model->description;
    }

    public function getEditLink() {
        return Url::to(['/admin/catalog/brand/edit', 'id' => $this->id]);
    }

    public function catalog() {
        if (!$this->_catalog) {
            $this->_catalog = Catalog::category();
        }
        return $this->_catalog;
    }

    public function categories($options = []) {
        if (!$this->_categories) {
            $this->_categories = $this->catalog()->categories(array_merge($options, ['brand_id' => $this->id]));
        }
        return $this->_categories;
    }

    public function categoriesOptions($firstOption = '', $withSlug = false) {
        $options = [];
        if ($firstOption) {
            $options[''] = $firstOption;
        }

        if ($withSlug) {
            foreach ($this->categories() as $category) {
                $options[$category->slug] = $category->model->title;
            }
        } else {
            foreach ($this->categories() as $category) {
                $options[$category->id] = $category->model->title;
            }
        }
        return $options;
    }

    public function groups_pages($options = []) {
        return $this->catalog()->groups_pages($options);
    }

    public function groups_pagination() {
        return $this->catalog()->groups_pagination;
    }

    public function groups($options = []) {
        if (!$this->_groups) {
            $this->_groups = $this->catalog()->groups(array_merge($options, ['where' => ['brand_id' => $this->id]]));
        }
        return $this->_groups;
    }

}
