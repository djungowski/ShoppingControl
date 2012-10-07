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
            $(item).bind('click', {options: options}, $.fn.purchaseeditor.deleteHandler);
        });
        
        $('.' + options.purchaseClass).bind('click', {options: options}, $.fn.purchaseeditor.rowHandler);
    };
    
    $.fn.purchaseeditor.inEditMode = false;
    
    $.fn.purchaseeditor.rowHandler = function(event) {
        var row,
            cell,
            checkbox,
            currentState;
        
        row = $(event.currentTarget);
        if ($.fn.purchaseeditor.inEditMode) {
            checkbox = row.find('input[type="checkbox"]');
            inputCell = event.target;
            if (checkbox[0] != inputCell) {
                currentState = checkbox.attr('checked');
                checkbox.attr('checked', !currentState);
            }
        }
    };
    
    $.fn.purchaseeditor.editHandler = function(event) {
        event.preventDefault();
        event.stopPropagation();
        $('.delete-purchase').show();
        $('.edit-purchases-buttons .edit').hide();
        $('.edit-purchases-buttons .delete').show();
        $('.edit-purchases-buttons .cancel').show();
        $.fn.purchaseeditor.inEditMode = true;
    };
    
    $.fn.purchaseeditor.cancelHandler = function(event) {
        event.preventDefault();
        event.stopPropagation();
        $('.delete-purchase').hide();
        $('.edit-purchases-buttons .edit').show();
        $('.edit-purchases-buttons .delete').hide();
        $('.edit-purchases-buttons .cancel').hide();
        $.fn.purchaseeditor.inEditMode = false;
    };
    
    $.fn.purchaseeditor.deleteHandler = function(event) {
        event.preventDefault();
        event.stopPropagation();
        var reallyDelete = confirm(event.data.options.safetyQuestion);
        if (reallyDelete === true) {
            $('#edit-purchases-form').submit();
        }
    };
    
    $.fn.purchaseeditor.options = {
        safetyQuestion: 'Are you sure?',
        purchaseClass: null
    };
})(jQuery, jQuery);