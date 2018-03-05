<?

namespace admin\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\HtmlPurifier;

/**
 * Class PurifyBehavior
 *
 */
class PurifyBehavior extends Behavior
{
    /**
     * @var array attributes
     */
    public $attributes = [];

    /**
     * @var null The config to use for HtmlPurifier
     */
    public $config = null;

    /**
     * Declares event handlers for the [[owner]]'s events.
     *
     * Child classes may override this method to declare what PHP callbacks should
     * be attached to the events of the [[owner]] component.
     *
     * The callbacks will be attached to the [[owner]]'s events when the behavior is
     * attached to the owner; and they will be detached from the events when
     * the behavior is detached from the component.
     **/
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }

    /**
     * Before validate event
     */
    public function beforeValidate()
    {
        foreach ($this->attributes as $attribute) {
            $this->owner->$attribute = HtmlPurifier::process($this->owner->$attribute, $this->config);
        }
    }
}
