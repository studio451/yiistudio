<?

use admin\modules\catalog\api\Catalog;
use admin\modules\page\api\Page;
use yii\helpers\Url;

$page = Page::get('page-brands');

$this->title = $page->seo('title');
$this->params['description'] = $page->seo('description');
$this->params['keywords'] = $page->seo('keywords');
$this->params['breadcrumbs'][] = $page->title;
?>
<div class="row">
    <div class="col-md-12">
        <h1>
            <?= $page->seo('h1') ?>
        </h1>
        <ul class="list-unstyled">
            <?
            foreach (Catalog::category()->brands() as $brand) {
                ?>
                <li><a href="<?= Url::to(['/brand', 'slug' => $brand->slug]) ?>"><?= $brand->title; ?></a></li>
                <?
            }
            ?>        
        </ul>
    </div>
</div>