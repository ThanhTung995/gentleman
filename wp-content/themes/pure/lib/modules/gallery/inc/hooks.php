<?php
/**
 * Gallery Hook
 *
 * @package    PureCore
 * @subpackage Gallery
 * @author     Boong
 * @link       https://boongstudio.com/plugins/gallery
 */

/**
 * Change sidebar layout for gallery.
 */
add_filter( 'pure_sidebar_layout', function ( $layout ) {

	if ( is_singular( 'gallery' ) || is_post_type_archive( 'gallery' ) || is_tax( 'gallery_category' ) ) {
		return 'c';
	}

	return $layout;

} );

add_filter( 'body_class', function ( $classes ) {

	if ( is_singular( 'gallery' ) || is_post_type_archive( 'gallery' ) || is_tax( 'gallery_category' ) ) {
		$classes[] = 'bg-secondary-bg';
	}

	return $classes;

} );

