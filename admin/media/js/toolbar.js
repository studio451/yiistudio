$(function(){
    $('.cms-edit').each(function(i, element){
        var $this = $(element);
        $this.append('<a href="'+$this.data('edit')+'" class="cms-goedit" style="width: '+$this.width()+'px; height: '+$this.height()+'px;" target="_blank"></a>');
    });
    $('.admin-toolbar input').switcher({copy: {en: {yes: '', no: ''}}}).on('change', function(){
        var checkbox = $(this);
        checkbox.switcher('setDisabled', true);
        location.href = checkbox.attr('data-link') + '/' + (checkbox.is(':checked') ? 1 : 0);
    });;
});