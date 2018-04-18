<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
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
 * @version     2.0.0
 */
?>
<?php if ( is_product() ) : ?>
    <ul class="products loop-wrapper">
<?php else: ?>
<ul class="products loop-wrapper loop--grid <?php echo esc_attr( pure_get_option( 'wc_listting_style' ) ); ?>
	<?php if ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) {
		echo 'loop--columns-' . esc_attr( get_option( 'woocommerce_catalog_columns' ) );
	} ?>
">
        <!--		<li class="grid-sizer"></li>-->
<?php endif;
