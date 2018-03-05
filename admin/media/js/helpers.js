
var Notify = function () {
    var div = $('<div id="notify-alert"></div>').appendTo('body');
    var queue = [];
    var _this = this;

    this.success = function (text)
    {
        queue.push({type: 'success', text: text, icon: 'ok'});
        _this.proceedQueue();
    }
    this.error = function (text)
    {
        queue.push({type: 'danger', text: text, icon: 'info'});
        _this.proceedQueue();
    }

    this.proceedQueue = function ()
    {
        if (queue.length > 0 && !div.is(":visible"))
        {
            div.removeClass().addClass('alert alert-' + queue[0].type).html('<span class="fa fa-' + queue[0].icon + '"></span> ' + queue[0].text);
            div.fadeToggle();

            setTimeout(function () {
                queue.splice(0, 1);
                div.fadeToggle(function () {
                    _this.proceedQueue();
                });
            }, 5000);
        }
    }
};

$(function () {

    window.notify = new Notify();
   
    $(document).on('click', '.ajaxModalPopup', function () {
        var modal_size = 'modal-lg';
        if($(this).attr('data-modal-size'))
        {
            modal_size = $(this).attr('data-modal-size');
        }
        
        var modal_dialog = $('#modal').find('.modal-dialog');
        
        modal_dialog.removeClass('modal-lg');
        modal_dialog.removeClass('modal-sm');
        modal_dialog.addClass(modal_size);
        
        if ($('#modal').data('bs.modal').isShown) {
            $('#modal').find('#modalContent')
                    .load($(this).attr('data-url'));
            document.getElementById('modalHeader').innerHTML = '<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-fw fa-close"></i></button><h4>' + $(this).attr('title') + '</h4>';
        } else {
            $('#modal').modal('show')
                    .find('#modalContent')
                    .load($(this).attr('data-url'));
            document.getElementById('modalHeader').innerHTML = '<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-fw fa-close"></i></button><h4>' + $(this).attr('title') + '</h4>';
        }
    });
});

function _g_session(key, value)
{
    $.post({
        url: '/session/update-json',
        data: {key: key, value: value}
    });
}
function _g_obj_to_array(obj)
{
    return Object.keys(obj).map(function (key) {
        return obj[key];
    })
}
function _g_animate_number(selector, color_id, value, limit)
{
    $(selector).animate({num: value - 5}, {
        duration: 1000,
        step: function (num) {
            this.innerHTML = (num + 5).toFixed(2);
        },
        complete: function () {
            var invert = false;
            if (limit < 0)
            {
                invert = true;
                limit = -limit;
            }

            if (value >= limit)
            {
                if (invert)
                {
                    $(color_id).removeClass('bg-green');
                    $(color_id).addClass('bg-red');
                } else
                {
                    $(color_id).removeClass('bg-red');
                    $(color_id).addClass('bg-green');
                }
            } else
            {
                if (invert)
                {
                    $(color_id).removeClass('bg-red');
                    $(color_id).addClass('bg-green');
                } else
                {
                    $(color_id).removeClass('bg-green');
                    $(color_id).addClass('bg-red');
                }
            }

        }
    });
}
function _g_animate_width(selector, color_id, value, limit)
{
    $(selector).animate({width: value + '%'}, 1000, function () {
        var invert = false;
        if (limit < 0)
        {
            invert = true;
            limit = -limit;
        }

        if (value >= limit)
        {
            if (invert)
            {
                $(color_id).removeClass('bg-green');
                $(color_id).addClass('bg-red');
            } else
            {
                $(color_id).removeClass('bg-red');
                $(color_id).addClass('bg-green');
            }
        } else
        {
            if (invert)
            {
                $(color_id).removeClass('bg-red');
                $(color_id).addClass('bg-green');
            } else
            {
                $(color_id).removeClass('bg-green');
                $(color_id).addClass('bg-red');
            }
        }
    });
}
function _g_ajax_form_submit(selector, options)
{
    $(selector).on('beforeSubmit', function (e) {
        var form = $(this);
        var formData = new FormData(this);
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                if (options.reload === true)
                {
                    location.reload();
                }
                
                if (options.func !== undefined)
                {
                    options.func(data);
                }
            },
            error: function (error) {
                alert('Error, please try again! Message: ' + error.responseJSON.message);
            }
        });
    }).on('submit', function (e) {
        e.preventDefault();
    });
}