<?php
/**
 * Sidebar
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/sidebar.php.
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

pure_tag( array(
	'open'    => '<aside %s>',
	'context' => 'shop-sidebar',
) );

do_action( 'pure_before_sidebar_shop');
pure_dynamic_sidebar( 'shop-sidebar', __( 'Shop Sidebar', 'pure-woocommerce' ) );
do_action( 'pure_after_sidebar_shop' );

pure_tag( array(
	'close'    => '</aside>',
	'context' => 'shop-sidebar',
) );

