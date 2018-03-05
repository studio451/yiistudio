function shopcartCost(data)
{
    if (data.cost) {
        $('#shopcart').html('<i class="fa fa-shopping-cart"></i> ' + data.cost + ' <i class="fa fa-rub"></i>');
        notify.success('Товар добавлен в <b><a href="/shopcart" class="text-success"><u>корзину</u></a></b>');
    } else
    {
        $('#shopcart').html('<i class="fa fa-shopping-cart"></i>');
    }
}
$(document).ready(function () {
    $('[data-toggle="popover"]').popover();
    
    _g_ajax_form_submit(".form_add_to_cart", {reload: false, func: shopcartCost});    
});