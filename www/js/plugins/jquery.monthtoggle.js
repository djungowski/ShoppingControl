(function(jQuery, $) {
    $.fn.monthtoggle = function(options) {
        options = $.extend($.fn.monthtoggle.options, options);
        
        this.each(function(key, year) {
            $(year).bind('click', $.fn.monthtoggle.clickhandler);
        });
    };
    
    $.fn.monthtoggle.clickhandler = function(event) {
        var arrow;
        
        classes = event.currentTarget.className.split(' ');
        $('.month-' + classes[1]).toggle();
        arrow = $(event.currentTarget).find('img');
        if (arrow.hasClass('left')) {
            arrow.removeClass('left');
            arrow.addClass('down');
            arrow.attr('src', arrow.attr('src').replace('left', 'down'));
        } else {
            arrow.addClass('left');
            arrow.removeClass('down');
            arrow.attr('src', arrow.attr('src').replace('down', 'left'));
        }
    };
    
    $.fn.monthtoggle.options = {
            
    };
})(jQuery, jQuery);