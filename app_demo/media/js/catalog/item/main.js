$(document).ready(function () {
    $('#count_increase').on('click', function () {
        var quantity = parseInt($('#count_input').val()) + 1;
        $('#count_text').text(quantity);
        $('#count_input').val(quantity);

        $('#help_count').text(quantity);
        $('#help_total_price').text(parseFloat($('#help_price').text()) * quantity);
    });

    $('#count_decrease').on('click', function () {
        var quantity = parseInt($('#count_input').val());
        if (parseInt($('#count_input').val()) > 1) {
            --quantity;
            $('#count_text').text(quantity);
            $('#count_input').val(quantity);

            $('#help_count').text(quantity);
            $('#help_total_price').text(parseFloat($('#help_price').text()) * quantity);
        }
    });
});