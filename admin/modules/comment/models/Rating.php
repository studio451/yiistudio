<?

namespace admin\modules\comment\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use admin\modules\comment\models\queries\RatingQuery;

class Rating extends ActiveRecord {


    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'admin_module_comment_rating';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['entity', 'entityId'], 'required'],
            [['entity', 'session'], 'string'],
            ['value', 'in', 'range' => [1, 2, 3, 4, 5]],
            ['value', 'default', 'value' => 1],
            ['entityId', 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'blameable' => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'createdBy',
                'updatedByAttribute' => 'updatedBy',
            ],
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
            ],
        ];
    }

    /**
     * @return RatingQuery
     */
    public static function find() {
        return new RatingQuery(get_called_class());
    }

    /**
     * Get rating for entity
     *
     * @param string $entity
     * @param string $entityId
     *
     * @return array|ActiveRecord[]
     */
    public static function getRating($entity, $entityId) {
        $query = static::find()
                ->andWhere([
                    'entityId' => $entityId,
                    'entity' => $entity,
                ]);
        
        return $query->sum('value');
    }
   

    /**
     * @return int
     */
    public function getRatingsCount() {
        return (int) static::find()
                        ->andWhere(['entity' => $this->entity, 'entityId' => $this->entityId])
                        ->count();
    }
}
