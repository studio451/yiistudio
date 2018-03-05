<?

namespace admin\modules\comment\moderation;

use Yii;
use yii\base\Behavior;
use yii\base\ModelEvent;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use admin\modules\comment\moderation\enums\Status;

/**
 * Class ModerationBehavior allows you to Approve or Reject resources like posts, comments, users, etc.
 *
 * To use ModerationBehavior, insert the following code to your ActiveRecord class:
 *
 * ```php
 * use yii2mod\moderation\ModerationBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         ModerationBehavior::class,
 *     ];
 * }
 * ```
 *
 * By default, ModerationBehavior will automatically set the `moderated_by` attribute.
 *
 * For the above implementation to work with MySQL database, please declare the columns(`status`, `moderated_by`) as int.
 *
 * If your attribute names are different, you may configure the [[statusAttribute]] and [[moderatedByAttribute]]
 * properties like the following:
 *
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => ModerationBehavior::class,
 *             'statusAttribute' => 'status_id',
 *             'moderatedByAttribute' => 'moderator_id',
 *         ],
 *     ];
 * }
 * ```
 *
 * ModerationBehavior provides the following methods for model moderation:
 *
 * ```php
 * $model->markApproved(); // Change model status to Approved
 *
 * $model->markRejected(); // Change model status to Rejected
 *
 * $model->markPostponed(); // Change model status to Postponed
 *
 * $model->markPending(); // Change model status to Pending
 * ```
 *
 * ModerationBehavior also provides the following methods for checking the moderation status:
 *
 * ```php
 * $model->isPending(); // Check if a model is pending
 *
 * $model->isApproved(); // Check if a model is approved
 *
 * $model->isRejected(); // Check if a model is rejected
 *
 * $model->isPostponed(); // Check if a model is postponed
 * ```
 *
 * @author Igor Chepurnoy <igorzfort@gmail.com>
 *
 * @since 1.0
 */
class ModerationBehavior extends Behavior
{
    /**
     * @event ModelEvent an event that is triggered before moderating a record.
     */
    const EVENT_BEFORE_MODERATION = 'beforeModeration';

    /**
     * @var BaseActiveRecord
     */
    public $owner;

    /**
     * @var string the attribute that will receive status value
     */
    public $statusAttribute = 'status';

    /**
     * @var string the attribute that will receive current user ID value
     * Set this property to false if you do not want to record the creator ID
     */
    public $moderatedByAttribute = 'moderated_by';

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
        ];
    }

    /**
     * @param $event
     */
    public function beforeInsert($event)
    {
        $this->fillModeratedByAttribute();
    }

    /**
     * @param $event
     */
    public function beforeUpdate($event)
    {
        $this->fillModeratedByAttribute();
    }

    /**
     * This method is invoked before moderating a record.
     *
     * @return bool
     */
    public function beforeModeration()
    {
        if (method_exists($this->owner, 'beforeModeration')) {
            if (!$this->owner->beforeModeration()) {
                return false;
            }
        }

        $event = new ModelEvent();
        $this->owner->trigger(self::EVENT_BEFORE_MODERATION, $event);

        return $event->isValid;
    }

    /**
     * Change model status to Approved
     *
     * @return bool|false|int
     */
    public function markApproved()
    {
        $this->owner->{$this->statusAttribute} = Status::APPROVED;

        if ($this->beforeModeration()) {
            return $this->owner->save();
        }

        return false;
    }

    /**
     * Change model status to Rejected
     *
     * @return bool|false|int
     */
    public function markRejected()
    {
        $this->owner->{$this->statusAttribute} = Status::REJECTED;

        if ($this->beforeModeration()) {
            return $this->owner->save();
        }

        return false;
    }

    /**
     * Change model status to Postponed
     *
     * @return bool|false|int
     */
    public function markPostponed()
    {
        $this->owner->{$this->statusAttribute} = Status::POSTPONED;

        if ($this->beforeModeration()) {
            return $this->owner->save();
        }

        return false;
    }

    /**
     * Change model status to Pending
     *
     * @return bool|false|int
     */
    public function markPending()
    {
        $this->owner->{$this->statusAttribute} = Status::PENDING;

        if ($this->beforeModeration()) {
            return $this->owner->save();
        }

        return false;
    }

    /**
     * Determine if the model instance has been approved.
     *
     * @return bool
     */
    public function isApproved()
    {
        return $this->owner->{$this->statusAttribute} == Status::APPROVED;
    }

    /**
     * Determine if the model instance has been rejected.
     *
     * @return bool
     */
    public function isRejected()
    {
        return $this->owner->{$this->statusAttribute} == Status::REJECTED;
    }

    /**
     * Determine if the model instance has been postponed.
     *
     * @return bool
     */
    public function isPostponed()
    {
        return $this->owner->{$this->statusAttribute} == Status::POSTPONED;
    }

    /**
     * Determine if the model instance has been pending.
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->owner->{$this->statusAttribute} == Status::PENDING;
    }

    /**
     * Fill `moderatedByAttribute`
     *
     * return void
     */
    protected function fillModeratedByAttribute()
    {
        if ($this->moderatedByAttribute !== false) {
            $this->owner->{$this->moderatedByAttribute} = $this->getModeratedByAttributeValue();
        }
    }

    /**
     * Get value for `moderatedByAttribute`
     */
    protected function getModeratedByAttributeValue()
    {
        $user = Yii::$app->get('user', false);

        return $user && !$user->isGuest ? $user->id : null;
    }
}
