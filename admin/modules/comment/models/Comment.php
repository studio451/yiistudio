<?

namespace admin\modules\comment\models;

use paulzi\adjacencyList\AdjacencyListBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use admin\behaviors\PurifyBehavior;
use admin\modules\comment\moderation\enums\Status;
use admin\modules\comment\moderation\ModerationBehavior;
use admin\modules\comment\moderation\ModerationQuery;

/**
 * Class Comment
 *
 * @property int $id
 * @property string $entity
 * @property int $entityId
 * @property string $content
 * @property int $parentId
 * @property int $level
 * @property int $createdBy
 * @property int $updatedBy
 * @property string $relatedTo
 * @property string $url
 * @property int $status
 * @property int $createdAt
 * @property int $updatedAt
 *
 * @method ActiveRecord makeRoot()
 * @method ActiveRecord appendTo($node)
 * @method ActiveQuery getDescendants()
 * @method ModerationBehavior markRejected()
 * @method AdjacencyListBehavior deleteWithChildren()
 */
class Comment extends ActiveRecord {

    /**
     * @var null|array|ActiveRecord[] comment children
     */
    protected $children;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'admin_module_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['entity', 'entityId'], 'required'],
                ['content', 'required', 'message' => Yii::t('admin/comment', 'Комментарий не может быть пустым')],
                [['content', 'entity', 'relatedTo', 'url'], 'string'],
                ['status', 'default', 'value' => Status::APPROVED],
                ['status', 'in', 'range' => Status::getConstantsByName()],
                ['level', 'default', 'value' => 1],
                ['parentId', 'validateParentID'],
                [['entityId', 'parentId', 'status', 'level'], 'integer'],
        ];
    }

    /**
     * @param $attribute
     */
    public function validateParentID($attribute) {
        if ($this->{$attribute} !== null) {
            $parentCommentExist = static::find()
                    ->approved()
                    ->andWhere([
                        'id' => $this->{$attribute},
                        'entity' => $this->entity,
                        'entityId' => $this->entityId,
                    ])
                    ->exists();

            if (!$parentCommentExist) {
                $this->addError('content', Yii::t('admin', 'Не удалось добавить комментарий. Попробуйте пожалуйста еще раз'));
            }
        }
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
            'purify' => [
                'class' => PurifyBehavior::class,
                'attributes' => ['content'],
                'config' => [
                    'HTML.SafeIframe' => true,
                    'URI.SafeIframeRegexp' => '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
                    'AutoFormat.Linkify' => true,
                    'HTML.TargetBlank' => true,
                    'HTML.Allowed' => 'a[href], iframe[src|width|height|frameborder], img[src]',
                ],
            ],
            'adjacencyList' => [
                'class' => AdjacencyListBehavior::class,
                'parentAttribute' => 'parentId',
                'sortable' => false,
            ],
            'moderation' => [
                'class' => ModerationBehavior::class,
                'moderatedByAttribute' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'content' => Yii::t('admin/comment', 'Комментарий'),
            'entity' => Yii::t('admin/comment', 'Модель'),
            'entityId' => Yii::t('admin/comment', 'ID коментируемой модели'),
            'parentId' => Yii::t('admin/comment', 'ID родителя'),
            'status' => Yii::t('admin/comment', 'Статус'),
            'level' => Yii::t('admin/comment', 'Уровень вложенности'),
            'createdBy' => Yii::t('admin/comment', 'Кем создан'),
            'updatedBy' => Yii::t('admin/comment', 'Кем обновлен'),
            'relatedTo' => Yii::t('admin/comment', 'Относится к'),
            'url' => Yii::t('admin', 'URL'),
            'createdAt' => Yii::t('admin/comment', 'Дата создания'),
            'updatedAt' => Yii::t('admin/comment', 'Дата обновления'),
        ];
    }

    /**
     * @return ModerationQuery
     */
    public static function find() {
        return new ModerationQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->parentId > 0) {
                $parentNodeLevel = static::find()->select('level')->where(['id' => $this->parentId])->scalar();
                $this->level += $parentNodeLevel;
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        if (!$insert) {
            if (array_key_exists('status', $changedAttributes)) {
                $this->beforeModeration();
            }
        }
    }

    /**
     * @return bool
     */
    public function saveComment() {
        if ($this->validate()) {
            if (empty($this->parentId)) {
                return $this->makeRoot()->save();
            } else {
                $parentComment = static::findOne(['id' => $this->parentId]);

                return $this->appendTo($parentComment)->save();
            }
        }

        return false;
    }

    /**
     * Author relation
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor() {
        return $this->hasOne(Yii::$app->getModule('admin')->activeModules['comment']->settings['userIdentityClass'], ['id' => 'createdBy']);
    }

    /**
     * Get comment tree.
     *
     * @param string $entity
     * @param string $entityId
     * @param null $maxLevel
     *
     * @return array|ActiveRecord[]
     */
    public static function getTree($entity, $entityId, $maxLevel = null) {
        $query = static::find()
                ->alias('c')
                ->approved()
                ->andWhere([
                    'c.entityId' => $entityId,
                    'c.entity' => $entity,
                ])
                ->orderBy(['c.parentId' => SORT_ASC, 'c.createdAt' => SORT_ASC])
                ->with(['author']);

        if ($maxLevel > 0) {
            $query->andWhere(['<=', 'c.level', $maxLevel]);
        }

        $models = $query->all();

        if (!empty($models)) {
            $models = static::buildTree($models);
        }

        return $models;
    }

    /**
     * Build comment tree.
     *
     * @param array $data comment list
     * @param int $rootID
     *
     * @return array|ActiveRecord[]
     */
    protected static function buildTree(&$data, $rootID = 0) {
        $tree = [];

        foreach ($data as $id => $node) {
            if ($node->parentId == $rootID) {
                unset($data[$id]);
                $node->children = self::buildTree($data, $node->id);
                $tree[] = $node;
            }
        }

        return $tree;
    }

    /**
     * @return array|null|ActiveRecord[]
     */
    public function getChildren() {
        return $this->children;
    }

    /**
     * @param $value
     */
    public function setChildren($value) {
        $this->children = $value;
    }

    /**
     * @return bool
     */
    public function hasChildren() {
        return !empty($this->children);
    }

    /**
     * @return string
     */
    public function getPostedDate() {
        return Yii::$app->formatter->asRelativeTime($this->createdAt);
    }

    /**
     * @return mixed
     */
    public function getAuthorName() {
        if ($this->author->hasMethod('getName')) {
            if ($this->author->getName()) {
                return $this->author->getName();
            }
        }

        return '';
    }

    /**
     * @return string
     */
    public function getContent() {
        return nl2br($this->content);
    }

    /**
     * Get avatar of the user
     *
     * @return string
     */
    public function getAvatar() {
        if ($this->author->hasMethod('getAvatar')) {
            if ($this->author->getAvatar()) {
                return $this->author->getAvatar();
            }
        }

        return 'http://www.gravatar.com/avatar?d=mm&f=y&s=50';
    }

    /**
     * Get list of all authors
     *
     * @return array
     */
    public static function getAuthors() {
        $query = static::find()
                ->alias('c')
                ->select(['c.createdBy', 'a.email'])
                ->joinWith('author a')
                ->groupBy(['c.createdBy', 'a.email'])
                ->orderBy('a.email')
                ->asArray()
                ->all();

        return ArrayHelper::map($query, 'createdBy', 'author.email');
    }

    /**
     * @return int
     */
    public function getCommentsCount() {
        return (int) static::find()
                        ->approved()
                        ->andWhere(['entity' => $this->entity, 'entityId' => $this->entityId])
                        ->count();
    }

    /**
     * @return string
     */
    public function getAnchorUrl() {
        return "#comment-{$this->id}";
    }

    /**
     * @return null|string
     */
    public function getViewUrl() {
        if (!empty($this->url)) {
            return $this->url . $this->getAnchorUrl();
        }

        return null;
    }

    /**
     * Before moderation event
     *
     * @return bool
     */
    public function beforeModeration() {
        $descendantIds = ArrayHelper::getColumn($this->getDescendants()->asArray()->all(), 'id');

        if (!empty($descendantIds)) {
            static::updateAll(['status' => $this->status], ['id' => $descendantIds]);
        }

        return true;
    }

}
