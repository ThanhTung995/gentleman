<?php
/**
 * Nivo Slider Options
 *
 * @package    PureCore
 * @subpackage NivoSlider
 * @author     Boong
 * @link       https://boongstudio.com/plugins/nivo-slider
 */

if ( !defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

if ( function_exists( 'acf_add_local_field_group' ) ):

	acf_add_local_field_group( array(
		'key'                   => 'group_5a88dd2d91764',
		'title'                 => 'Slider',
		'fields'                => array(
			array(
				'key'               => 'field_5a88dd3c72d00',
				'label'             => 'Slides',
				'name'              => 'slides',
				'type'              => 'repeater',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'collapsed'         => '',
				'min'               => 0,
				'max'               => 0,
				'layout'            => 'row',
				'button_label'      => '',
				'sub_fields'        => array(
					array(
						'key'               => 'field_5a88dd6072d01',
						'label'             => 'Image',
						'name'              => 'image',
						'type'              => 'image',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'return_format'     => 'array',
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
					array(
						'key'               => 'field_5a88dd7272d02',
						'label'             => 'Link',
						'name'              => 'url',
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
				),
			),
			array(
				'key'               => 'field_5a88dd8372d03',
				'label'             => 'Duration',
				'name'              => 'duration',
				'type'              => 'number',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'default_value'     => 3000,
				'placeholder'       => '',
				'prepend'           => '',
				'append'            => '',
				'min'               => '',
				'max'               => '',
				'step'              => '',
			),
			array(
				'key'               => 'field_5a88dda172d04',
				'label'             => 'Open link in new tab?',
				'name'              => 'link_blank',
				'type'              => 'checkbox',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'choices'           => array(
					1 => 'Yes',
				),
				'allow_custom'      => 0,
				'save_custom'       => 0,
				'default_value'     => array(),
				'layout'            => 'horizontal',
				'toggle'            => 0,
				'return_format'     => 'value',
			),
			isset( $_GET[ 'post' ] ) ? array(
				'key'               => 'field_5a88ddc972d05',
				'label'             => 'Note',
				'name'              => '',
				'type'              => 'message',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'message'           => sprintf( __( 'User this shortcode in post/page to display slider: <code>[pure_nivo_slider id=%d]</code>', 'pure' ), $_GET[ 'post' ] ),
				'new_lines'         => 'wpautop',
				'esc_html'          => 0,
			) : array(
				'type'              => '',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'nivo_slider',
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
