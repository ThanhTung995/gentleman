(function ($) {
    "use strict";
    jQuery(document).ready(function(){
        jQuery.ajax({
            url: FilterAjax.ajaxurl,
            type: 'post',
            data: { action: 'ajax_product_filter'},
            success: function( respond ) {
                jQuery('.content_filter').html(respond);
            }
        });

        jQuery('.header_filter ul li').click(function ($) {
            var data = jQuery(this).attr('data-filter');
            jQuery.ajax({
                url: FilterAjax.ajaxurl,
                type: 'post',
                data: { action: 'ajax_product_filter', 'data' : data},
                success: function( respond ) {
                    jQuery('.content_filter').html(respond);
                }
            });
        });
    });
})(jQuery);
