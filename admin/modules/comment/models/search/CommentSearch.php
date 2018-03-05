<?

namespace admin\modules\comment\models\search;

use yii\data\ActiveDataProvider;
use admin\modules\comment\models\Comment;

/**
 * Class CommentSearch
 *
 */
class CommentSearch extends Comment
{
    /**
     * @var int the default page size
     */
    public $pageSize = 50;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['createdBy', 'status'], 'integer'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search(array $params)
    {
        $query = Comment::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->pageSize,
            ],
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['id' => SORT_DESC],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'createdBy' => $this->createdBy,
            'status' => $this->status,
        ]);

        return $dataProvider;
    }
}
