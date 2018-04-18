<?php
/**
 * Pure Framework.
 *
 * WARNING: This is part of the Pure Framework. DO NOT EDIT this file under any circumstances.
 * Please do all your modifications in a child theme.
 *
 * @package Pure
 * @author  Boong
 * @link    https://boongstudio.com/themes/pure
 */

add_action( 'pure_sidebar', 'pure_sidebar_widget_area' );
/**
 * Widgets for primary sidebar.
 */
function pure_sidebar_widget_area() {

	pure_dynamic_sidebar( 'sidebar-primary', __( 'Primary Sidebar', 'pure' ) );
}

add_action( 'pure_sidebar_secondary', 'pure_sidebar_secondary_widget_area' );
/**
 * Widgets for secondary sidebar.
 */
function pure_sidebar_secondary_widget_area() {

	pure_dynamic_sidebar( 'sidebar-secondary', __( 'Secondary Sidebar', 'pure' ) );
}

add_action( 'pure_before_footer', 'pure_sidebar_bottom_widget_area' );
/**
 * Widgets for bottom sidebar.
 */
function pure_sidebar_bottom_widget_area() {

	// We don't need this on front page.
	if( is_front_page() ) {
		return;
	}

	echo '<div class="sidebar-bottom">';
	echo '<div class="wrap">';
	pure_dynamic_sidebar( 'sidebar-bottom', __( 'Bottom Sidebar', 'pure' ) );
	echo '</div>';
	echo '</div>';
}

add_filter( 'pure_sidebar_layout', function ( $layout ) {

	if ( is_404() ) {
		return 'c';
	}

	return $layout;
} );