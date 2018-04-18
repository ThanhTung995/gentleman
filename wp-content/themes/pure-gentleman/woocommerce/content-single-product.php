<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

/**
 * woocommerce_before_single_product hook.
 *
 * @hooked wc_print_notices - 10
 */


do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form();

	return;
}

$thumb_position = pure_get_option( 'wc_single_thumbnails_position', 'bottom' );

?>

    <div id="product-<?php the_ID(); ?>" <?php post_class( 'pure-woocommerce-single style-1' ); ?> itemscope
         itemtype="http://schema.org/Product">

        <div class="pure-woocommerce-single__upper">

            <div class="pure-woocommerce-single__gallery-wrapper <?php echo esc_attr( $thumb_position ); ?>">

				<?php
				/**
				 * woocommerce_before_single_product_summary hook.
				 *
				 * @hooked woocommerce_show_product_sale_flash - 10
				 * @hooked woocommerce_show_product_images - 20
				 */
				do_action( 'woocommerce_before_single_product_summary' );
				?>

            </div><!-- /.pure-woocommerce-single__gallery-wrapper -->

            <div class="summary entry-summary">
                <div class="insider">
					<?php
					/**
					 * woocommerce_single_product_summary hook.
					 *
					 * @hooked  woocommerce_template_single_title - 5
					 * @hooked  woocommerce_template_single_meta - 6
					 * @hooked  woocommerce_template_single_price - 10
					 * @hooked  woocommerce_template_single_rating - 11
					 * @hooked  woocommerce_template_single_excerpt - 20
					 * @hoooked pure_woocommerce_promotion - 25
					 * @hooked  woocommerce_template_single_add_to_cart - 30
					 * @hooked  WC_Structured_Data::generate_product_data() - 60
					 */
					do_action( 'woocommerce_single_product_summary' );
					?>
                </div>
            </div><!-- .summary -->

        </div><!-- /.pure-woocommerce-single__upper -->

		<?php
		/**
		 * woocommerce_after_single_product_summary hook.
		 *
         * @hooked pure_woocommere_single_under_open - 5
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked pure_woocommere_single_under_open - 13
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
		?>

    </div><!-- #product-<?php the_ID(); ?> -->
<?php
do_action( 'woocommerce_after_single_product' );
