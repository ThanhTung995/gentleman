<?php

if ( function_exists( 'acf_add_local_field_group' ) ):

	acf_add_local_field_group( array(
		'key'                   => 'group_5ab8730cd0208',
		'title'                 => 'Menu',
		'fields'                => array(
			array(
				'key'               => 'field_5ab873119a6e3',
				'label'             => 'Icon',
				'name'              => 'icon',
				'type'              => 'text',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'default_value'     => '',
				'placeholder'       => '',
				'prepend'           => '',
				'append'            => '',
				'maxlength'         => '',
			),
			array(
				'key'               => 'field_5ab8b66809aa4',
				'label'             => 'Icon Image',
				'name'              => 'icon_image',
				'type'              => 'image',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'return_format'     => 'url',
				'preview_size'      => 'thumbnail',
				'library'           => 'all',
				'min_width'         => '',
				'min_height'        => '',
				'min_size'          => '',
				'max_width'         => '',
				'max_height'        => '',
				'max_size'          => '',
				'mime_types'        => '',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'nav_menu_item',
					'operator' => '==',
					'value'    => 'all',
				),
			),
		),
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => 1,
		'description'           => '',
	) );

endif;

add_filter( 'wp_nav_menu_objects', 'pure_wp_nav_menu_objects', 10, 2 );
/**
 * Filter nav menu item to display icon.
 *
 * @param $items
 * @param $args
 *
 * @return mixed
 */
function pure_wp_nav_menu_objects( $items, $args ) {

	// loop
	foreach ( $items as &$item ) {

		// vars
		$icon       = get_field( 'icon', $item );
		$icon_image = get_field( 'icon_image', $item );

		if ( $icon_image ) {
			$item->title = '<img class="icon" src="' . $icon_image . '"> ' . $item->title;
		} elseif ( $icon ) {
			$item->title = '<i class="icon ' . $icon . '"></i> ' . $item->title;
		}
	}

	return $items;
}
