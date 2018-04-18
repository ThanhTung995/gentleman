<?php
/**
 * Quick view template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
?>

<div id="product-<?php the_ID(); ?>" <?php post_class( 'quick-view' ); ?>>
	<div class="insider">
		<div class="quick-view-left">
			<div class="pure-woocommerce-single__gallery-wrapper">
				<?php woocommerce_show_product_sale_flash(); ?>
				<?php wc_get_template( 'single-product/product-image.php' ); ?>
			</div><!-- /.pure-woocommerce-single__gallery-wrapper -->
		</div>
		<div class="quick-view-right">
			<div class="summary entry-summary">

				<?php the_title( '<h1 class="product_title entry-title">', '</h1>' ); ?>

				<?php
				/**
				 * pure_quick_view_product_summary hook.
				 *
				 * @hooked woocommerce_template_single_rating - 10
				 * @hooked woocommerce_template_single_price - 20
				 * @hooked woocommerce_template_single_excerpt - 30
				 */
				do_action( 'pure_quick_view_product_summary' );

				if ( $product->is_type( 'simple' ) ) {
					woocommerce_template_single_add_to_cart();
				}
				?>

				<div class="actions text-center">
					<div class="view-detail">
						<a href="<?php the_permalink(); ?>" class="tu fw-700" title="<?php the_title(); ?>">
							<span class="txt"><?php echo __( 'View Detail', 'pure-woocommerce' ); ?></span>
							<i class="fa fa-long-arrow-right"></i>
						</a>
					</div>
					<hr/>
					<div class="js-close-quick-view ttu close-quick-view-link">
						<span class="txt db"><?php echo __( 'Continue Shopping', 'pure-woocommerce' ); ?></span>
						<i class="fa fa-long-arrow-down"></i>
					</div>
				</div>
			</div>
		</div>
		<a href="#" class="close-quick-view-icon js-close-quick-view">
			<i class="fa fa-times   "></i>
		</a>
	</div>
</div>
