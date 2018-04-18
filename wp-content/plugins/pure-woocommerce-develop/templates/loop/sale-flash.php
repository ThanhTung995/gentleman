<?php
/**
 * Single Product Sale Flash
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/sale-flash.php.
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
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

$sale = false;

if ( ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) ) && $product->is_on_sale() ) {

	$product       = wc_get_product( $product->get_id() );
	$regular_price = $product->get_regular_price();
	$sale_price    = $product->get_sale_price();

	if ( $regular_price != '' ) {
		$sale = ceil( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
	}
} elseif ( $product->is_type( 'variable' ) && $product->is_on_sale() ) {

	$available_variations = $product->get_available_variations();

	$sale = array();

	foreach ( $available_variations as $val ) {
		if ( $val['display_price'] > 0 ) {
			$regular_price = $val['display_regular_price'];
			$sale_price    = $val['display_price'];

			$sale[] = ceil( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
		} else {
			$sale[] = '100';
		}
	}

	$sale = max( $sale );
}

if ( $product->is_on_sale() && $sale ) {
	echo apply_filters( 'woocommerce_sale_flash', '<span class="sale-badge sale">-' . $sale . '%</span>', $post, $product );
}