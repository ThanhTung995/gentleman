<?php
/**
 * Nivo Slider Hook
 *
 * @package    PureCore
 * @subpackage NivoSlider
 * @author     Boong
 * @link       https://boongstudio.com/plugins/nivo-slider
 */

/**
 * Get all published sliders.
 */
function pure_nivo_slider_get_all_sliders() {

	$output  = array();
	$sliders = get_posts( array(
		'post_type'      => 'nivo_slider',
		'order'          => 'ASC',
		'posts_per_page' => 100,
		'post_status'    => 'publish',
	) );
	foreach ( $sliders as $slider ) {
		$output[] = $slider->ID;
	}

	return $output;
}
