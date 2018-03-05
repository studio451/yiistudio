<?
use admin\modules\faq\api\Faq;
use admin\modules\page\api\Page;

$page = Page::get('page-faq');

$this->title = $page->seo('title', $page->model->title);
$this->params['breadcrumbs'][] = $page->model->title;
?>
<h1><?= $page->seo('h1', $page->title) ?></h1>
<br/>

<? foreach(Faq::items() as $item) : ?>
    <p><b>Вопрос: </b><?= $item->question ?></p>
    <blockquote><b>Ответ: </b><?= $item->answer ?></blockquote>
<? endforeach; ?>