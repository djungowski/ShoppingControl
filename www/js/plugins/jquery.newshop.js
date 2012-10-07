(function(jQuery, $) {
    $.fn.newshop = function(options) {
        options = $.extend($.fn.newshop.options, options);
        
        this.change(function(eventData, event) {
            var select,
                textfield;
            
            select = $(this);
            if (select.val() == 0) {
                textfield = $('#' + options.textfieldId);
                textfield.show();
                textfield.focus();
                select.hide();
            }
        });
    };
    
    $.fn.newshop.options = {
        textfieldId: null
    };
})(jQuery, jQuery);