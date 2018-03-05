$(document).ready(function () {
    $('.shopcart_count_increase').on('click', function () {

        var good_id = $(this).attr('data-good-id');
        var quantity = parseInt($('#shopcart_count_input_' + good_id).val()) + 1;
        $('#shopcart_count_text_' + good_id).text(quantity);
        $('#shopcart_count_input_' + good_id).val(quantity);
        $('#shopcart_refresh_button').show();
    });
    $('.shopcart_count_decrease').on('click', function () {

        var good_id = $(this).attr('data-good-id');
        var quantity = parseInt($('#shopcart_count_input_' + good_id).val());
        if (parseInt($('#shopcart_count_input_' + good_id).val()) > 1) {
            --quantity;
            $('#shopcart_count_text_' + good_id).text(quantity);
            $('#shopcart_count_input_' + good_id).val(quantity);
            $('#shopcart_refresh_button').show();
        }
    });
    $('#shopcart_refresh_button').on('click', function ()
    {
        $.post({
            url: '/session/update-json',
            data: {key: 'shopcartUserInputData', value: {
                    name: $('#name').val(),
                    phone: $('#phone').val(),
                    comment: $('#comment').val(),
                    delivery: $('input[name=delivery_id]:checked').attr('data-slug'),
                    payment: $('input[name="payment_id[' + $('input[name=delivery_id]:checked').val() + ']"]:checked').attr('data-slug'),
                }}
        },
                function () {
                    $('#shopcart_update').submit();
                }
        );
    });
    $('.shopcart_remove_button').on('click', function ()
    {
        var data_remove_id= $(this).attr('data-remove-id');
        $.post({
            url: '/session/update-json',
            data: {key: 'shopcartUserInputData', value: {
                    name: $('#name').val(),
                    phone: $('#phone').val(),
                    comment: $('#comment').val(),
                    delivery: $('input[name=delivery_id]:checked').attr('data-slug'),
                    payment: $('input[name="payment_id[' + $('input[name=delivery_id]:checked').val() + ']"]:checked').attr('data-slug'),
                }}
        },
                function () {
                    $.get({
                        url: '/shopcart/remove/' + data_remove_id
                    },
                            function () {
                                location.reload();
                            }
                    );
                }
        );
    });
});

