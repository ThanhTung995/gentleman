<?php
/*
Plugin Name: Pure WooCommerce
Plugin URI: http://vietmoz.com
Version: 1.0.0
Author: VietMoz Site
Author URI: http://vietmoz.com
Text Domain: pure-woocommerce
*/

// Check if supported theme is activated.
if ( 'pure' !== get_option( 'template' ) ) {
	return;
}

define( 'PURE_WOOCOMMERCE_VERSION', '1.0.0' );

define( 'PURE_WOOCOMMERCE_URL', plugin_dir_url( __FILE__ ) );

define( 'PURE_WOOCOMMERCE_PATH', plugin_dir_path( __FILE__ ) );

if ( !function_exists( 'is_plugin_active' ) ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if ( !function_exists( 'lunar_core_load_lastly' ) ) {
	add_action( 'activated_plugin', 'lunar_core_load_lastly' );
	add_action( 'deactivated_plugin', 'lunar_core_load_lastly' );
	/**
	 * Make plugin run lastly.
	 */
	function lunar_core_load_lastly() {

		$wp_path_to_this_file = preg_replace( '/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR . "/$2", __FILE__ );
		$this_plugin          = plugin_basename( trim( $wp_path_to_this_file ) );
		$active_plugins       = get_option( 'active_plugins' );
		$this_plugin_key      = array_search( $this_plugin, $active_plugins );

		array_splice( $active_plugins, $this_plugin_key, 1 );
		array_push( $active_plugins, $this_plugin );
		update_option( 'active_plugins', $active_plugins );
	}
}

if ( !class_exists( 'WooCommerce' ) ) {
	return;
}

add_action( 'init', 'pure_woocommerce_load_textdomain' );
/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function pure_woocommerce_load_textdomain() {

	load_plugin_textdomain( 'pure-woocommerce', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

/*  [ Require Files. ]
- - - - - - - - - - - - - - - - - - - */
require_once PURE_WOOCOMMERCE_PATH . 'includes/helpers.php';

require_once PURE_WOOCOMMERCE_PATH . 'includes/hooks.php';

add_action( 'wp_enqueue_scripts', 'pure_woocommerce_enqueue' );
/**
 * Enqueue style
 */
function pure_woocommerce_enqueue() {

	wp_enqueue_style( 'pure-woocommerce', PURE_WOOCOMMERCE_URL . 'assets/css/woocommerce.css', array(), PURE_WOOCOMMERCE_VERSION );

	wp_enqueue_style( 'magnific-popup', PURE_WOOCOMMERCE_URL . 'assets/css/magnific-popup.css', array(), '1.1.2' );

	wp_enqueue_script( 'magnific-popup', PURE_WOOCOMMERCE_URL . 'assets/js/jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.2', true );

	wp_enqueue_script( 'jquery-inview', PURE_WOOCOMMERCE_URL . 'assets/js/jquery.inview.min.js', array( 'jquery' ), '1.1.2', true );

	wp_enqueue_script( 'sticky-kit', PURE_WOOCOMMERCE_URL . 'assets/js/jquery.sticky-kit.min.js', array( 'jquery' ), '1.1.3', true );

	wp_enqueue_script( 'pure-woocommerce', PURE_WOOCOMMERCE_URL . 'assets/js/woocommerce.js', array(
		'flickity',
		'jquery-inview',
		'sticky-kit',
		'magnific-popup',
		'slideout',
	), PURE_WOOCOMMERCE_VERSION, true );

	wp_localize_script( 'pure-woocommerce', 'PUREWOOCOMMERCE', array(
		'siteURL'                 => trailingslashit( site_url() ),
		'ajaxURL'                 => admin_url( 'admin-ajax.php' ),
		'addToCartAdditionalText' => pure_get_option( 'wc_add_to_cart_additional_text', '' ),
	) );
}
