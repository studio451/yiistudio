<?

use admin\modules\feedback\api\Feedback;
use admin\modules\page\api\Page;

$page = Page::get('page-contact');

$this->title = $page->seo('title');
$this->params['description'] = $page->seo('description');
$this->params['keywords'] = $page->seo('keywords');
$this->params['breadcrumbs'][] = $page->title;
?>
<h1><?= $page->seo('h1') ?></h1>

<div class="row">
    <div class="col-md-6">
        <h4><?= Yii::t('app', 'Вы можете связаться с нами через форму обратной связи:') ?></h4>
        <div class="border p-20">
            <?= Feedback::form() ?>
        </div>
        <?= $page->text ?>
    </div>
</div>

