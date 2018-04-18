<?php
/**
 * Nivo Slider Shortcode.
 *
 * @package    PureCore
 * @subpackage NivoSlider
 * @author     Boong
 * @link       https://boongstudio.com/plugins/nivo-slider
 */

add_shortcode( 'pure_nivo_slider', 'pure_nivo_slider_shortcode' );
/**
 * Nivo slider shortcode.
 */
function pure_nivo_slider_shortcode( $atts ) {

	$a = shortcode_atts( array(
		'id' => '',
	), $atts );

	if ( !isset( $a[ 'id' ] ) || !$a[ 'id' ] ) {
		return;
	}

	$slider = get_field( 'slides', $a[ 'id' ] );

	if ( !isset( $slider ) || !is_array( $slider ) || empty( $slider ) ) {
		return;
	}

	$duration = get_post_meta( $a[ 'id' ], 'duration', true );
	$duration = $duration ? $duration : 3000;

	wp_enqueue_script( 'nivo-slider' );
	wp_add_inline_script(
		'nivo-slider',
		'jQuery(window).on("load elementor/frontend/init", function() {
					jQuery("#slider-' . $a[ 'id' ] . '").nivoSlider({
						pauseOnHover: true,
						pauseTime: ' . $duration . ',
					}); 
			});'
	);

	ob_start();
	?>
    <div class="slider-wrapper theme-default">
        <div id="slider-<?php echo esc_attr( $a[ 'id' ] ); ?>" class="nivoSlider">
			<?php
			foreach ( $slider as $slide ) :
				if ( !is_array( $slide ) || empty( $slide ) || !isset( $slide[ 'image' ] ) || !$slide[ 'image' ] ) {
					continue;
				}

				if ( !isset( $slide[ 'image' ][ 'url' ] ) || !$slide[ 'image' ][ 'url' ] ) {
					continue;
				}

				$image = sprintf( '<img src="%s">', $slide[ 'image' ][ 'url' ] );

				if ( isset( $slide[ 'url' ] ) && $slide[ 'url' ] ) {
					$output = '<a href="' . $slide[ 'url' ] . '"';
					if ( isset( $slide[ 'link_blank' ] ) && $slide[ 'link_blank' ] ) {
						$output .= ' target="_blank"';
					}
					$output .= '>';
					$output .= $image;
					$output .= '</a>';
					echo $output;
				} else {
					echo $image;
				}

			endforeach;
			?>
        </div>
    </div>
	<?php return ob_get_clean();
}
