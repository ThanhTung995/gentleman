<?php
/**
 * Nivo Slider Hook
 *
 * @package    PureCore
 * @subpackage NivoSlider
 * @author     Boong
 * @link       https://boongstudio.com/plugins/nivo-slider
 */

add_action( 'init', 'pure_nivo_slider_posttype', 0 );
/**
 * Register slider post type.
 */
function pure_nivo_slider_posttype() {

	$labels = array(
		'name'                  => _x( 'Slides', 'Post Type General Name', 'pure' ),
		'singular_name'         => _x( 'Slide', 'Post Type Singular Name', 'pure' ),
		'menu_name'             => __( 'Nivo Slider', 'pure' ),
		'name_admin_bar'        => __( 'Nivo Slider', 'pure' ),
		'archives'              => __( 'Item Archives', 'pure' ),
		'attributes'            => __( 'Item Attributes', 'pure' ),
		'parent_item_colon'     => __( 'Parent Item:', 'pure' ),
		'all_items'             => __( 'All Items', 'pure' ),
		'add_new_item'          => __( 'Add New Item', 'pure' ),
		'add_new'               => __( 'Add New', 'pure' ),
		'new_item'              => __( 'New Item', 'pure' ),
		'edit_item'             => __( 'Edit Item', 'pure' ),
		'update_item'           => __( 'Update Item', 'pure' ),
		'view_item'             => __( 'View Item', 'pure' ),
		'view_items'            => __( 'View Items', 'pure' ),
		'search_items'          => __( 'Search Item', 'pure' ),
		'not_found'             => __( 'Not found', 'pure' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'pure' ),
		'featured_image'        => __( 'Featured Image', 'pure' ),
		'set_featured_image'    => __( 'Set featured image', 'pure' ),
		'remove_featured_image' => __( 'Remove featured image', 'pure' ),
		'use_featured_image'    => __( 'Use as featured image', 'pure' ),
		'insert_into_item'      => __( 'Insert into item', 'pure' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'pure' ),
		'items_list'            => __( 'Items list', 'pure' ),
		'items_list_navigation' => __( 'Items list navigation', 'pure' ),
		'filter_items_list'     => __( 'Filter items list', 'pure' ),
	);
	$args = array(
		'label'                 => __( 'Slide', 'pure' ),
		'description'           => __( 'Nivo Slider with beautiful transition.', 'pure' ),
		'labels'                => $labels,
		'supports'              => array( 'title', ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 25,
		'menu_icon'             => 'dashicons-images-alt2',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,		
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'rewrite'               => false,
		'capability_type'       => 'page',
	);
	register_post_type( 'nivo_slider', $args );

}
