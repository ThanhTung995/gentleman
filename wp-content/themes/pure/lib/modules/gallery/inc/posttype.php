<?php
/**
 * Gallery Hook
 *
 * @package    PureCore
 * @subpackage Gallery
 * @author     Boong
 * @link       https://boongstudio.com/plugins/gallery
 */

add_action( 'init', 'pure_gallery_posttype', 5 );
/**
 * Register slider post type.
 */
function pure_gallery_posttype() {

	$labels = array(
		'name'                  => _x( 'Galleries', 'Post Type General Name', 'pure' ),
		'singular_name'         => _x( 'Gallery', 'Post Type Singular Name', 'pure' ),
		'menu_name'             => __( 'Gallery', 'pure' ),
		'name_admin_bar'        => __( 'Gallery', 'pure' ),
	);

	$has_archive  = true;
	$hierarchical = false;
	$supports     = array( 'title', 'editor', 'comments', 'thumbnail' );

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'query_var'           => true,
		'show_in_admin_bar'   => true,
		'capability_type'     => 'post',
		'has_archive'         => $has_archive,
		'hierarchical'        => $hierarchical,
		'supports'            => $supports,
		'menu_position'       => 21,
		'menu_icon'           => 'dashicons-tickets',
		'taxonomies'          => array( 'gallery_category' ),
		/* Add $this->plugin_name as translatable in the permalink structure,
		   to avoid conflicts with other plugins which may use customers as well. */
		'rewrite'             => array(
			'slug'       => esc_attr__( 'gallery', 'pure' ),
			'with_front' => false,
		),
	);
	register_post_type( 'gallery', $args );

}

add_action( 'init', 'pure_gallery_taxonomy', 5 );
// Register Custom Taxonomy
function pure_gallery_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Gallery Categories', 'Taxonomy General Name', 'pure' ),
		'singular_name'              => _x( 'Gallery Category', 'Taxonomy Singular Name', 'pure' ),
		'menu_name'                  => __( 'Gallery Category', 'pure' ),
	);
	$args   = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => false,
		'rewrite'           => array(
			'slug'         => __( 'gallery-category', 'pure' ),
			'with_front'   => true,
			'hierarchical' => false,
		),
	);
	register_taxonomy( 'gallery_category', array( 'gallery' ), $args );

}
