/**
 * Rating plugin
 */
(function ($) {

    $.fn.rating = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.rating');
            return false;
        }
    };

    // Default settings
    var defaults = {
        formSelector: '#rating-form',
        pjaxContainerId: '#rating-pjax-container',
        pjaxSettings: {
            timeout: 10000,
            scrollTo: false,
            url: window.location.href
        }
    };

    var ratingData = {};

    // Methods
    var methods = {
        init: function (options) {
            return this.each(function () {
                var $rating = $(this);
                var settings = $.extend({}, defaults, options || {});
                var id = $rating.attr('id');
                if (ratingData[id] === undefined) {
                    ratingData[id] = {};
                } else {
                    return;
                }
                ratingData[id] = $.extend(ratingData[id], {settings: settings});

                var formSelector = ratingData[id].settings.formSelector;
                var eventParams = {formSelector: formSelector, wrapperSelector: id};

                $rating.on('click.rating', '[data-action="create"]', eventParams, createRating);
            });
        },
        data: function () {
            var id = $(this).attr('id');
            return ratingData[id];
        }
    };


    /**
     * Create a rating
     * @returns {boolean}
     */
    function createRating(event) {
        var $this = $(this);
        var settings = ratingData[event.data.wrapperSelector].settings;
        var $ratingForm = $(settings.formSelector);
        var pjaxSettings = $.extend({container: settings.pjaxContainerId}, settings.pjaxSettings);
        var formData = $ratingForm.serializeArray();
        formData.push({'name': 'Rating[value]', 'value': $this.data('value')});
        // creating a rating and errors handling
        $.post($ratingForm.attr('action'), formData, function (data) {
            if (data.status == 'success') {
                $.pjax(pjaxSettings);
            }
        }).fail(function (xhr, status, error) {
            alert(error);
            $.pjax(pjaxSettings);
        });

        return false;
    }

})(window.jQuery);
