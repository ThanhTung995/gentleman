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

Kirki::add_section( 'pure_header', array(
	'title' => esc_attr__( 'Header', 'pure' ),
	'panel' => 'pure_panel',
) );

Kirki::add_field( 'pure', array(
	'type'            => 'image',
	'settings'        => 'desktop_logo',
	'label'           => esc_attr__( 'Desktop Logo', 'pure' ),
	'section'         => 'pure_header',
	'partial_refresh' => array(
		'desktop_logo' => array(
			'selector'        => '.site-header.desktop .site-title a',
			'render_callback' => 'pure_cz_desktop_logo',
		),
	),
) );

Kirki::add_field( 'pure', array(
	'type'      => 'dimension',
	'settings'  => 'desktop_logo_width',
	'label'     => esc_attr__( 'Desktop Logo Width', 'pure' ),
	'section'   => 'pure_header',
	'default'   => '100px',
	'transport' => 'auto',
	'output'    => array(
		array(
			'element'  => '.desktop .site-title img, .info__logo img',
			'property' => 'max-width',
		),
	),
) );

Kirki::add_field( 'pure', array(
	'type'            => 'image',
	'settings'        => 'mobile_logo',
	'label'           => esc_attr__( 'Mobile Logo', 'pure' ),
	'section'         => 'pure_header',
	'partial_refresh' => array(
		'mobile_logo' => array(
			'selector'        => '.site-header.mobile .site-title a',
			'render_callback' => 'pure_cz_mobile_logo',
		),
	),
) );

Kirki::add_field( 'pure', array(
	'type'      => 'dimension',
	'settings'  => 'mobile_logo_width',
	'label'     => esc_attr__( 'Mobile Logo Width', 'pure' ),
	'section'   => 'pure_header',
	'default'   => '100px',
	'transport' => 'auto',
	'output'    => array(
		array(
			'element'  => '.mobile .site-title img',
			'property' => 'max-width',
		),
	),
) );

Kirki::add_field( 'pure', array(
	'type'      => 'textarea',
	'settings'  => 'header_top_left',
	'label'     => esc_attr__( 'Top Left', 'pure' ),
	'section'   => 'pure_header',
	'default'   => '',
) );

Kirki::add_field( 'pure', array(
	'type'      => 'textarea',
	'settings'  => 'header_top_right',
	'label'     => esc_attr__( 'Top Right', 'pure' ),
	'section'   => 'pure_header',
	'default'   => '',
) );

function pure_cz_desktop_logo() {

	$logo_url = pure_get_option( 'desktop_logo', get_template_directory_uri() . '/lib/img/logo.png' );

	echo '<img src="' . $logo_url . '" />';
}

function pure_cz_mobile_logo() {

	$logo_url = pure_get_option( 'mobile_logo', get_template_directory_uri() . '/lib/img/logo.png' );

	echo '<img src="' . $logo_url . '" />';
}
