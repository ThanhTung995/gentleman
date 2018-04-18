(function ($) {
    "use strict";
    jQuery(document).ready(function(){
        jQuery('h3.title_des_meta').on('click',function(){
            jQuery('.woocommerce-product-details__short-description').slideToggle();
            jQuery('h3.title_des_meta').toggleClass('show');
        });

        jQuery('h3.title_mes_meta').on('click',function(){
            jQuery('.woocommerce-tabs.h-tab').slideToggle();
            jQuery('h3.title_mes_meta').toggleClass('show');
        });

        jQuery('.short_code_product_filter .header_filter .content ul li').on('click',function () {
            jQuery('.short_code_product_filter .header_filter .content ul li').removeClass('active');
            jQuery(this).addClass('active');
        });
    });
})(jQuery);
