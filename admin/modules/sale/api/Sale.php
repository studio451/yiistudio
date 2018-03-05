<?

namespace admin\modules\sale\api;

use Yii;
use yii\data\ActiveDataProvider;
use admin\models\Tag;
use yii\widgets\LinkPager;
use admin\modules\sale\models\Sale as SaleModel;

class Sale extends \admin\components\API {

    private $_adp;
    private $_items;
    private $_item = [];

    public function api_items($options = []) {
        if (!$this->_items) {
            $this->_items = [];

            $with = ['seoText'];
            if (Yii::$app->getModule('admin')->activeModules['sale']->settings['enableTags']) {
                $with[] = 'tags';
            }
            $query = SaleModel::find()->with($with)->status(SaleModel::STATUS_ON);

            if (!empty($options['where'])) {
                $query->andFilterWhere($options['where']);
            }
            if (!empty($options['tags'])) {
                $query
                        ->innerJoinWith('tags', false)
                        ->andWhere([Tag::tableName() . '.name' => (new SaleModel)->filterTagValues($options['tags'])])
                        ->addGroupBy('item_id');
            }
            if (!empty($options['orderBy'])) {
                $query->orderBy($options['orderBy']);
            } else {
                $query->sortDate();
            }

            $this->_adp = new ActiveDataProvider([
                'query' => $query,
                'pagination' => !empty($options['pagination']) ? $options['pagination'] : []
            ]);

            foreach ($this->_adp->models as $model) {
                $this->_items[] = new SaleObject($model);
            }
        }
        return $this->_items;
    }

    public function api_get($id_slug) {
        if (!isset($this->_item[$id_slug])) {
            $this->_item[$id_slug] = $this->findSale($id_slug);
        }
        return $this->_item[$id_slug];
    }

    public function api_last($limit = 1) {
        $cache = Yii::$app->cache;

        $key = 'sale_last';
        $sale_last = $cache->get($key);
        if ($sale_last === false) {

            $with = ['seoText'];
            if (Yii::$app->getModule('admin')->activeModules['sale']->settings['enableTags']) {
                $with[] = 'tags';
            }

            $sale_last = [];
            foreach (SaleModel::find()->with($with)->status(SaleModel::STATUS_ON)->sortDate()->limit($limit)->all() as $item) {
                $sale_last[] = new SaleObject($item);
            }
            $cache->set($key, $sale_last, 3600);
        }
        if ($limit > 1) {
            return $sale_last;
        } else {
            return count($sale_last) ? $sale_last[0] : null;
        }
    }

    public function api_pagination() {
        return $this->_adp ? $this->_adp->pagination : null;
    }

    public function api_pages() {
        return $this->_adp ? LinkPager::widget(['pagination' => $this->_adp->pagination]) : '';
    }

    private function findSale($id_slug) {
        $sale = SaleModel::find()->where(['or', 'id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->status(SaleModel::STATUS_ON)->one();
        if ($sale) {
            $sale->updateCounters(['views' => 1]);
            return new SaleObject($sale);
        } else {
            return null;
        }
    }

}
