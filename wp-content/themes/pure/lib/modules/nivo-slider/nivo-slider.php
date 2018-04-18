<?php
/**
 * Nivo Slider.
 *
 * @package    PureCore
 * @subpackage NivoSlider
 * @author     Boong
 * @link       https://boongstudio.com/plugins/nivo-slider
 */

$modules_dir = trailingslashit( PARENT_DIR ) . 'lib/modules/';

require_once $modules_dir . 'nivo-slider/inc/helpers.php' ;
require_once $modules_dir . 'nivo-slider/inc/hooks.php'   ;
require_once $modules_dir . 'nivo-slider/inc/posttype.php';
require_once $modules_dir . 'nivo-slider/inc/options.php' ;

add_action( 'wp_enqueue_scripts', 'pure_nivo_slider_register_scripts' );
/**
 * Enqueue scripts and styles
 */
function pure_nivo_slider_register_scripts() {

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';
	$modules_uri = get_template_directory_uri() . '/lib/modules/';

	wp_register_style( 'nivo-slider', $modules_uri . "nivo-slider/assets/css/nivo-slider{$suffix}.css", array(), '3.2' );
	wp_register_script( 'nivo-slider', $modules_uri . "nivo-slider/assets/js/jquery.nivo.slider{$suffix}.js", array( 'jquery' ), '3.2', true );

	if( ! empty( pure_nivo_slider_get_all_sliders() ) ) {
		if( is_singular() || is_front_page() ) {
			wp_enqueue_style( 'nivo-slider' );
		}
	}
}

require_once $modules_dir . 'nivo-slider/views/shortcode.php' ;
