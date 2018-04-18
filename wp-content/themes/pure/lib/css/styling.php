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

add_action( 'wp_head', 'pure_styling', 100 );
/**
 * Get dynamic style then append to main stylesheet.
 */
function pure_styling() {

	echo '<style>';
	echo pure_generate_style();
	echo '</style>';
}

/**
 * Generate dynamic CSS.
 *
 * @return string
 */
function pure_generate_style() {

	$border_color        = pure_get_option( 'border_color' );
	$primary_color       = pure_get_option( 'primary_color' );
	$output              = '';

	$output .= 'input[type="color"]:hover, input[type="date"]:hover, input[type="datetime"]:hover, input[type="datetime-local"]:hover, input[type="email"]:hover, input[type="month"]:hover, input[type="number"]:hover, input[type="password"]:hover, input[type="search"]:hover, input[type="tel"]:hover, input[type="text"]:hover, input[type="time"]:hover, input[type="url"]:hover, input[type="week"]:hover, input:not([type]):hover, textarea:hover { border-color: ' . shade( $border_color, 0.8 ) . ';}';
	$output .= 'input[type="color"]:focus, input[type="date"]:focus, input[type="datetime"]:focus, input[type="datetime-local"]:focus, input[type="email"]:focus, input[type="month"]:focus, input[type="number"]:focus, input[type="password"]:focus, input[type="search"]:focus, input[type="tel"]:focus, input[type="text"]:focus, input[type="time"]:focus, input[type="url"]:focus, input[type="week"]:focus, input:not([type]):focus, textarea:focus { border-color: ' . hex2rgba( $primary_color, 0.5 ) . ';}';

	if ( function_exists( 'pure_child_theme_style' ) ) {
		$output .= pure_child_theme_style();
	}

	return $output;
}