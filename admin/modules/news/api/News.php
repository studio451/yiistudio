<?

namespace admin\modules\news\api;

use Yii;
use yii\data\ActiveDataProvider;
use admin\models\Tag;
use yii\widgets\LinkPager;
use admin\modules\news\models\News as NewsModel;

/**
 * News module Api
 * @package admin\modules\news\api
 *
 * @method static NewsObject get(mixed $id_slug) Get news object by id or slug
 * @method static array items(array $options = []) Get list of news as NewsObject objects
 * @method static mixed last(int $limit = 1) Get last news
 * @method static \stdClass pagination() returns yii\data\Pagination object.
 */
class News extends \admin\base\Api {

    private $_adp;
    private $_items;
    private $_item = [];

    public function api_items($options = []) {
        if (!$this->_items) {
            $this->_items = [];

            $with = ['seoText'];
            if (Yii::$app->getModule('admin')->activeModules['news']->settings['enableTags']) {
                $with[] = 'tags';
            }
            $query = NewsModel::find()->with($with)->status(NewsModel::STATUS_ON);

            if (!empty($options['where'])) {
                $query->andFilterWhere($options['where']);
            }
            if (!empty($options['tags'])) {
                $query
                        ->innerJoinWith('tags', false)
                        ->andWhere([Tag::tableName() . '.name' => (new NewsModel)->filterTagValues($options['tags'])])
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
                $this->_items[] = new NewsObject($model);
            }
        }
        return $this->_items;
    }

    public function api_get($id_slug) {
        if (!isset($this->_item[$id_slug])) {
            $this->_item[$id_slug] = $this->findNews($id_slug);
        }
        return $this->_item[$id_slug];
    }

    public function api_last($limit = 1) {
        $cache = Yii::$app->cache;

        $key = 'news_last_' . $limit;
        $news_last = $cache->get($key);
        if ($news_last === false) {

            $with = ['seoText'];
            if (Yii::$app->getModule('admin')->activeModules['news']->settings['enableTags']) {
                $with[] = 'tags';
            }

            $news_last = [];
            foreach (NewsModel::find()->with($with)->status(NewsModel::STATUS_ON)->sortDate()->limit($limit)->all() as $item) {
                $news_last[] = new NewsObject($item);
            }
            $cache->set($key, $news_last, 3600);
        }
        if ($limit > 1) {
            return $news_last;
        } else {
            return count($news_last) ? $news_last[0] : null;
        }
    }

    public function api_pagination() {
        return $this->_adp ? $this->_adp->pagination : null;
    }

    public function api_pages($options = []) {
        return $this->_adp ? LinkPager::widget(array_merge($options, ['pagination' => $this->_adp->pagination])) : '';
    }

    private function findNews($id_slug) {
        $news = NewsModel::find()->where(['or', 'id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->status(NewsModel::STATUS_ON)->one();
        if ($news) {
            $news->updateCounters(['views' => 1]);
            return new NewsObject($news);
        } else {
            return null;
        }
    }

}
