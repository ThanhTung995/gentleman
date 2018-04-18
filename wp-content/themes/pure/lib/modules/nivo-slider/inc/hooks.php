<?php
/**
 * Nivo Slider Hook
 *
 * @package    PureCore
 * @subpackage NivoSlider
 * @author     Boong
 * @link       https://boongstudio.com/plugins/nivo-slider
 */

add_action('add_meta_boxes', 'pure_nivo_slider_remove_seo_meta_box', 100);
/**
 * Remove Yoast SEO metabox.
 */
function pure_nivo_slider_remove_seo_meta_box() {
	remove_meta_box('wpseo_meta', 'nivo_slider', 'normal');
}
