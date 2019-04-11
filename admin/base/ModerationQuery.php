<?

namespace admin\base;

use Yii;
use yii\db\ActiveQuery;
use yii2mod\moderation\enums\Status;

/**
 * ModerationQuery adds the ability of getting only approved, rejected, postponed or pending models.
 *
 * This class provides the following methods:
 *
 * ```php
 * Post::find()->approved()->all() // It will return all Approved Posts
 *
 * Post::find()->rejected()->all() // It will return all Rejected Posts
 *
 * Post::find()->postponed()->all() // It will return all Postponed Posts
 *
 * Post::find()->pending()->all() // It will return all Pending Posts
 *
 * Post::find()->approvedWithPending()->all() // It will return all Approved and Pending Posts
 * ```
 *
 * @author Igor Chepurnoy <igorzfort@gmail.com>
 *
 * @since 1.0
 * 
 * @package admin\base
 */
class ModerationQuery extends ActiveQuery
{
    /**
     * @var string status attribute
     */
    protected $statusAttribute;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->statusAttribute = Yii::$container->get($this->modelClass)->statusAttribute;
    }

    /**
     * Get a new active query object that includes approved resources.
     *
     * @return ActiveQuery
     */
    public function approved()
    {
        return $this->andWhere([$this->statusAttribute => Status::APPROVED]);
    }

    /**
     * Get a new active query object that includes rejected resources.
     *
     * @return ActiveQuery
     */
    public function rejected()
    {
        return $this->andWhere([$this->statusAttribute => Status::REJECTED]);
    }

    /**
     * Get a new active query object that includes postponed resources.
     *
     * @return ActiveQuery
     */
    public function postponed()
    {
        return $this->andWhere([$this->statusAttribute => Status::POSTPONED]);
    }

    /**
     * Get a new active query object that includes pending resources.
     *
     * @return ActiveQuery
     */
    public function pending()
    {
        return $this->andWhere([$this->statusAttribute => Status::PENDING]);
    }

    /**
     * Get a new active query object that includes approved and pending resources.
     *
     * @return ActiveQuery
     */
    public function approvedWithPending()
    {
        return $this->andWhere([$this->statusAttribute => [Status::APPROVED, Status::PENDING]]);
    }
}
