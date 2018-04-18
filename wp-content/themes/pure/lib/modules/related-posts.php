<?php

if ( function_exists( 'acf_add_local_field_group' ) ):

	acf_add_local_field_group( array(
		'key'                   => 'group_5a98d6fe2c14a',
		'title'                 => 'Post Options',
		'fields'                => array(
			array(
				'key'               => 'field_5a98d70811a2a',
				'label'             => 'Related Posts',
				'name'              => 'related_posts',
				'type'              => 'relationship',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'post_type'         => array(
					0 => 'post',
				),
				'taxonomy'          => array(),
				'filters'           => array(
					0 => 'search',
					1 => 'taxonomy',
				),
				'elements'          => array(
					0 => 'featured_image',
				),
				'min'               => '',
				'max'               => '',
				'return_format'     => 'id',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'post',
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