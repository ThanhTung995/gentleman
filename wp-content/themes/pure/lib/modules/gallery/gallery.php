<?php
/**
 * Gallery.
 *
 * @package    PureCore
 * @subpackage Gallery
 * @author     Boong
 * @link       https://boongstudio.com/plugins/gallery
 */

$modules_dir = trailingslashit( PARENT_DIR ) . 'lib/modules/';

require_once $modules_dir . 'gallery/inc/helpers.php' ;
require_once $modules_dir . 'gallery/inc/hooks.php'   ;
require_once $modules_dir . 'gallery/inc/posttype.php';
require_once $modules_dir . 'gallery/inc/options.php' ;

//add_action( 'wp_enqueue_scripts', 'pure_gallery_register_scripts' );
/**
 * Enqueue scripts and styles
 */
function pure_gallery_register_scripts() {

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';
	$modules_uri = get_template_directory_uri() . '/lib/modules/';

}

require_once $modules_dir . 'gallery/views/archive.php' ;
require_once $modules_dir . 'gallery/views/single.php' ;
