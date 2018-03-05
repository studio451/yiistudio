<?

namespace admin\modules\comment\events;

use yii\base\Event;
use admin\modules\comment\models\Comment;

/**
 * Class CommentEvent
 *
 * @package admin\modules\comment\events
 */
class CommentEvent extends Event
{
    /**
     * @var Comment
     */
    private $_commentModel;

    /**
     * @return Comment
     */
    public function getCommentModel()
    {
        return $this->_commentModel;
    }

    /**
     * @param Comment $commentModel
     */
    public function setCommentModel(Comment $commentModel)
    {
        $this->_commentModel = $commentModel;
    }
}
