<?

use admin\modules\page\api\Page;
use admin\modules\shopcart\api\Shopcart;
use yii\helpers\Html;
use yii\helpers\Url;

$page = Page::get('page-shopcart-success-payment');

$this->title = $page->seo('title');
$this->params['description'] = $page->seo('description');
$this->params['keywords'] = $page->seo('keywords');
$this->params['breadcrumbs'][] = $page->title;
?>
<h1><?= $page->seo('h1') ?></h1>
<br>
<p>
<?= Yii::t('admin/shopcart', 'Ваш заказ №' . $id . ' успешно оплачен!') ?>
</p>
<p>
<strong>Спасибо, что выбрали нас!</strong>
<p>
<?= $page->text ?>
</p>