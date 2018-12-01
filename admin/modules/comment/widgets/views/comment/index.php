<?

use yii\helpers\ArrayHelper;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $commentModel \admin\modules\comment\models\Comment */
/* @var $maxLevel null|integer comment max level */
/* @var $encryptedEntity string */
/* @var $pjaxContainerId string */
/* @var $formId string comment form id */
/* @var $commentDataProvider \yii\data\ArrayDataProvider */
/* @var $listViewConfig array */
/* @var $commentWrapperId string */
?>
<div class="comment-wrapper" id="<? echo $commentWrapperId; ?>">
    <? Pjax::begin(['enablePushState' => false, 'timeout' => 20000, 'id' => $pjaxContainerId]); ?>
    <div class="comment row">
        <div class="col-md-12 col-sm-12">
            <div class="title-block clearfix">
                <h3 class="h3-body-title">
                    <? echo Yii::t('admin/comment', 'Комментарии ({0})', $commentModel->getCommentsCount()); ?>
                </h3>
                <div class="title-separator"></div>
            </div>
            <?
            echo ListView::widget(ArrayHelper::merge(
                            [
                        'dataProvider' => $commentDataProvider,
                        'layout' => "{items}\n{pager}",
                        'itemView' => '_list',
                        'viewParams' => [
                            'maxLevel' => $maxLevel,
                        ],
                        'options' => [
                            'tag' => 'ol',
                            'class' => 'comment-list',
                        ],
                        'itemOptions' => [
                            'tag' => false,
                        ],
                            ], $listViewConfig
            ));
            ?>
            <? if (!Yii::$app->user->isGuest) { ?>
                <?
                echo $this->render('_form', [
                    'commentModel' => $commentModel,
                    'formId' => $formId,
                    'encryptedEntity' => $encryptedEntity,
                ]);
                ?>
            <? } else { ?>
                <noindex>
                    <?= Yii::t('admin/comment', 'Чтобы оставить комментарий, нужно ') ?> <a rel="nofollow" href="javascript:void(0);" title="<?= Yii::t('admin', 'Войти в личный кабинет') ?>" data-url="<?= Url::to(['/user/login']) ?>" class="dotted ajaxModalPopup"><?= Yii::t('admin', 'войти в личный кабинет') ?></a> <?= Yii::t('admin', 'или') ?> <a rel="nofollow" href="javascript:void(0);" title="<?= Yii::t('admin', 'Зарегистрироваться') ?>" data-url="<?= Url::to(['/user/registration']) ?>" class="dotted ajaxModalPopup"><?= Yii::t('admin', 'зарегистрироваться') ?></a>.
                </noindex>
            <? } ?>
        </div>
    </div>
    <? Pjax::end(); ?>
</div>
