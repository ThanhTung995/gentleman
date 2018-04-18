<?php
/**
 * Count views of posts
 */

/**
 * Get post view from post meta.
 *
 * @param      $postID Post ID.
 * @param bool $suffix Whether display suffix or not.
 *
 * @return mixed|string
 */
function pure_get_post_views( $postID ) {

	$count_key = 'post_views_count';
	$count     = get_post_meta( $postID, $count_key, true );
	if ( $count == '' ) {
		delete_post_meta( $postID, $count_key );
		add_post_meta( $postID, $count_key, '0' );

		return 0;
	}

	return $count;
}

/**
 * Set post view counter.
 *
 * @param int $postID Post ID.
 */
function pure_set_post_views() {

	$postID    = get_the_ID();
	$count_key = 'post_views_count';
	$count     = get_post_meta( $postID, $count_key, true );
	if ( $count == '' ) {
		$count = 0;
		delete_post_meta( $postID, $count_key );
		add_post_meta( $postID, $count_key, '0' );
	} else {
		$count ++;
		update_post_meta( $postID, $count_key, $count );
	}
}

add_filter( 'manage_posts_columns', 'pure_admin_posts_column_views' );
/**
 * Add column to admin post list.
 *
 * @param $defaults
 *
 * @return mixed
 */
function pure_admin_posts_column_views( $defaults ) {

	$defaults['post_views'] = __( 'Views', 'pure' );

	return $defaults;
}

add_action( 'manage_posts_custom_column', 'pure_admin_posts_custom_column_views', 5, 2 );
/**
 * Print value to custom Post view column
 *
 * @param $column_name
 * @param $id
 */
function pure_admin_posts_custom_column_views( $column_name, $id ) {

	if ( $column_name === 'post_views' ) {
		echo pure_get_post_views( get_the_ID() );
	}
}

add_action( 'template_redirect', 'pure_post_views_counter_hooks' );
/**
 * Setup the counter.
 */
function pure_post_views_counter_hooks() {

	if ( is_single() || is_page() ) {
		add_action( 'wp_head', 'pure_set_post_views' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	}
}

/**
 * Return filtered HTML output of post views.
 *
 * @param      $post_id
 * @param null $before
 * @param null $after
 *
 * @return string
 */
function pure_get_post_views_output( $post_id, $before = null, $after = null ) {

	if( $before === NULL && $after === NULL ) {
		$before = apply_filters( 'pure_post_views_output_before', __('| Views: ', 'pure') );
		$after  = apply_filters( 'pure_post_views_output_after', '' );
	}

	return $before . pure_get_post_views( $post_id ) . $after;
}

/**
 * Append post view to post meta.
 */
add_filter( 'pure_entry_meta', function( $meta ) {

	$views = pure_tag( array(
		'context' => 'entry-post-views',
		'open'    => '<span %s>',
		'close'   => '</span>',
		'content' => pure_get_post_views_output( get_the_ID() ),
		'echo'    => false,
	) );

	return $meta . $views;
} );
