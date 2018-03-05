<?

use yii\helpers\Url;
use admin\modules\yml\YmlModule;

$marketcategories = [];
?>
<?= $xmlHeader; ?>
<yml_catalog date="<?= date('Y-m-d H:i'); ?>">
    <shop>
        <name><?= $model->shop_name; ?></name>
        <company><?= $model->shop_company; ?></company>
        <url><?= $model->shop_url; ?></url>
        <platform><?= \admin\AdminModule::NAME ?></platform>
        <version><?= \admin\AdminModule::VERSION ?></version>
        <agency><?= $model->shop_agency; ?></agency>
        <email><?= $model->shop_email; ?></email>
        <currencies>
            <? foreach ($currencies as $currency): ?>
                <currency id="<?= $currency; ?>" <?= $currency === 'RUB' ? 'rate="1"' : ''; ?>/>
            <? endforeach; ?>
        </currencies>
        <categories>
            <? foreach ($categories as $category): ?>
                <category id="<?= $category->id ?>"><?= $category->title; ?></category>
            <? endforeach; ?>
        </categories>
        <delivery-options>
            <option cost="<?= $model->all_delivery_options_cost ?>" days="<?= $model->all_delivery_options_days ?>"/>
        </delivery-options>
        <cpa><?= $model->shop_cpa; ?></cpa>
        <offers>
            <? foreach ($items as $item) : ?>
                <offer id="<?= $item->id ?>" type="vendor.model" available="<?= $item->available ? 'true' : 'false'; ?>">
                    <url><?= $item->url ?></url>
                    <price><?= $item->price ?></price>
                    <?
                    if ($item->old_price > $item->price) {
                        ?>
                        <oldprice><?= $item->old_price ?></oldprice>
                        <?
                    }
                    ?>
                    <currencyId><?= 'RUB' ?></currencyId>
                    <categoryId><?= $item->category->id; ?></categoryId>
                    <store>true</store>
                    <pickup>true</pickup>
                    <delivery>true</delivery>
                    <?
                    if ($item->price < $model->delivery_free_from) {
                        ?>
                        <delivery-options>
                            <option cost="<?= $model->delivery_options_cost ?>" days="<?= $model->delivery_options_days ?>"/>
                        </delivery-options>
                        <?
                    }
                    ?>
                    <? foreach ($item->photos as $photo) { ?>
                        <picture><?= $photo ?></picture>                       
                    <? } ?>
                    <typePrefix><?= $item->type ?></typePrefix>
                    <model><?= $item->name ?></model>
                    <vendor><?= $item->brand ?></vendor>
                    <market_category><?= $item->marketcategory ?></market_category>
                    <description><?= htmlspecialchars(strip_tags($item->description)); ?></description>
                    <?
                    //Доп.параметры   
                    foreach (YmlModule::getAdditionalFields(true) as $field) {
                        if (!empty($item->data[$field['attribute']])) {
                            ?>
                            <param name="<?= $field['header'] ?>"><?= htmlspecialchars(strip_tags($item->data[$field['attribute']])) ?></param>
                            <?
                        }
                    }
                    ?>                 
                </offer>
            <? endforeach; ?>
        </offers>
    </shop>
</yml_catalog>