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
 * Get all published sliders.
 */
function pure_gallery_get_all_sliders() {

	$output  = array();
	$sliders = get_posts( array(
		'post_type'      => 'gallery',
		'order'          => 'ASC',
		'posts_per_page' => 100,
		'post_status'    => 'publish',
	) );
	foreach ( $sliders as $slider ) {
		$output[] = $slider->ID;
	}

	return $output;
}
