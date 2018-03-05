<?

use admin\modules\page\api\Page;
use yii\helpers\Url;

$page = Page::get('page-shopcart-success');

$this->title = $page->seo('title');
$this->params['description'] = $page->seo('description');
$this->params['keywords'] = $page->seo('keywords');
$this->params['breadcrumbs'][] = $page->title;
?>
<h1><?= $page->seo('h1') ?></h1>

<br>
<p>
    <?= Yii::t('admin/shopcart', 'Ваш заказ №' . $id . ' успешно создан!') ?> 
    <?= Yii::t('admin/shopcart', 'В ближайшее время мы свяжемся с Вами для уточнения деталей.') ?>
</p>
<p>
    <strong>Спасибо, что выбрали нас!</strong>
<p>
    <?= $page->text ?>
</p>