$(function () {
    var body = $('body');
    body.on('click', '.text-red', function () {
        var button = $(this).addClass('disabled');
        var title = button.attr('title');

        if (confirm(title ? title + '?' : 'Confirm the deletion')) {
            if (button.data('reload')) {
                return true;
            }
            $.getJSON(button.attr('href'), function (response) {
                button.removeClass('disabled');
                if (response.result === 'success') {
                    notify.success(response.message);
                    button.closest('tr').fadeOut(function () {
                        this.remove();
                    });
                } else {
                    alert(response.error);
                }
            });
        }
        return false;
    });

    body.on('click', '.move-up, .move-down', function () {
        var button = $(this).addClass('disabled');

        $.getJSON(button.attr('href'), function (response) {
            button.removeClass('disabled');
            if (response.result === 'success' && response.swap_id) {
               
                location.reload();

            } else if (response.error) {
                alert(response.error);
            }
        });

        return false;
    });

    $('.switch').switcher({copy: {en: {yes: '', no: ''}}}).on('change', function () {
        var checkbox = $(this);
        checkbox.switcher('setDisabled', true);

        $.getJSON(checkbox.data('link') + '/' + (checkbox.is(':checked') ? 'on' : 'off') + '/' + checkbox.data('id'), function (response) {
            if (response.result === 'error') {
                alert(response.error);
            }
            if (checkbox.data('reload')) {
                location.reload();
            } else {
                checkbox.switcher('setDisabled', false);
            }
        });
    });

    $(document).bind('keydown', function (e) {
        if (e.ctrlKey && e.which === 83) { // Check for the Ctrl key being pressed, and if the key = [S] (83)
            $('.model-form').submit();
            e.preventDefault();
            return false;
        }
    });
});