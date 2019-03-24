$(function () {
    $('.cms-edit').each(function (i, element) {
        var $this = $(element);
        var w = $this.width();
        var h = $this.height();
        if (w !== 0 && h !== 0)
        {
            $this.append('<a href="' + $this.data('edit') + '" class="cms-goedit" style="width: ' + w + 'px; height: ' + h + 'px;" target="_blank"></a>');
        }
    });
    $('.admin-toolbar input').switcher({copy: {en: {yes: '', no: ''}}}).on('change', function () {
        var checkbox = $(this);
        checkbox.switcher('setDisabled', true);
        location.href = checkbox.attr('data-link') + '/' + (checkbox.is(':checked') ? 1 : 0);
    });

    $('.collapse').on('shown.bs.collapse', function () {        
        $(this).children('.cms-edit').each(function (i, element) {
            var $this = $(element);
            $this.append('<a href="' + $this.data('edit') + '" class="cms-goedit" style="width: ' + $this.width() + 'px; height: ' + $this.height() + 'px;" target="_blank"></a>');
            
        });
    });
    
    $('.collapse').on('hidden.bs.collapse', function () {        
        $(this).find('.cms-goedit').remove();
    });
});