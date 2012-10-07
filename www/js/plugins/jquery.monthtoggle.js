(function(jQuery, $) {
    $.fn.monthtoggle = function(options) {
        options = $.extend($.fn.monthtoggle.options, options);
        
        this.each(function(key, year) {
            $(year).on('click', $.fn.monthtoggle.clickhandler);
        });
    };
    
    $.fn.monthtoggle.clickhandler = function(event) {
        classes = event.target.className.split(' ');
        $('.month-' + classes[1]).toggle();
    };
    
    $.fn.monthtoggle.options = {
            
    };
})(jQuery, jQuery);