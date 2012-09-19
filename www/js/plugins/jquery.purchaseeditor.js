(function(jQuery, $) {
    $.fn.purchaseeditor = function(options) {
        options = $.extend($.fn.purchaseeditor.options, options);
        
        this.find('.edit').each(function(key, item) {
            $(item).bind('click', $.fn.purchaseeditor.editHandler);
        });
        
        this.find('.cancel').each(function(key, item) {
            $(item).bind('click', $.fn.purchaseeditor.cancelHandler);
        });
        
        this.find('.delete').each(function(key, item) {
            $(item).bind('click', $.fn.purchaseeditor.deleteHandler);
        });
    };
    
    $.fn.purchaseeditor.editHandler = function(event) {
        event.preventDefault();
        event.stopPropagation();
        $('.delete-purchase').show();
        $('.edit-purchases-buttons .edit').hide();
        $('.edit-purchases-buttons .delete').show();
        $('.edit-purchases-buttons .cancel').show();
    };
    
    $.fn.purchaseeditor.cancelHandler = function(event) {
        event.preventDefault();
        event.stopPropagation();
        $('.delete-purchase').hide();
        $('.edit-purchases-buttons .edit').show();
        $('.edit-purchases-buttons .delete').hide();
        $('.edit-purchases-buttons .cancel').hide();
    };
    
    $.fn.purchaseeditor.deleteHandler = function(event) {
        event.preventDefault();
        event.stopPropagation();
        // @TODO: Implement form submission here
        $('#edit-purchases-form').submit();
    };
    
    $.fn.purchaseeditor.options = {
            
    };
})(jQuery, jQuery);