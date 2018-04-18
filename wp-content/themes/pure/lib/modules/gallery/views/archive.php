<?php
/**
 * Gallery Shortcodes.
 *
 * @package    PureCore
 * @subpackage Gallery
 * @author     Boong
 * @link       https://boongstudio.com/plugins/gallery
 */

add_action( 'template_redirect', function () {

	if ( !is_post_type_archive( 'gallery' ) && !is_tax( 'gallery_category' ) ) {
		return;
	}

	add_action( 'pure_before_loop', function () {

		$current_term = 0;
		if ( is_tax( 'gallery_category' ) ) {
			$current_term = get_queried_object()->term_id;
		}
		?>
        <div class="full-width bg-primary-bg mgb40 gallery-archive-nav">
            <div class="wrap">
                <div class="cat-list">
					<?php
					$terms = get_terms( 'gallery_category', array(
						'hide_empty' => false,
					) );
					foreach ( $terms as $term ) {
						if ( $current_term === $term->term_id ) {
							echo '<div class="item bg-primary-color--before">';
						} else {
							echo '<div class="item">';
						}
						echo '<a href="' . get_term_link( $term ) . '" title="' . $term->name . '">';
						echo $term->name;
						echo '</a>';
						echo '</div>';
					}
					?>
                </div>
                <div class="change-loop-layout" data-target=".loop">
                    <span data-layout="list"><i class="far fa-th-list"></i></span>
                    <span data-layout="grid"><i class="far fa-th-large"></i></span>
                </div>
            </div>
        </div>
		<?php
	}, 8 );

	add_filter( 'pure_loop_class', function ( $class ) {


		if ( is_post_type_archive( 'gallery' ) || is_tax( 'gallery_category' ) ) {
			return 'loop--grid loop--columns-3';
		}

		return $class;

	}, 11 );

	add_action( 'pure_after_entry_content', function () {

		echo '<hr />';
	} );
	remove_action( 'pure_entry_footer', 'pure_headphone_loop_footer' );

	add_action( 'pure_entry_footer', function () {

		if ( is_singular() ) {
			return;
		}
		echo '<span class="color-meta-color">';
		echo do_shortcode( '[post_time]' );
		echo '<span class="post-views mgl20"><i class="far fa-eye"></i> ' . pure_get_post_views( get_the_ID() ) . '</span>';
		echo '</span>';

		printf( '<a href="%s" class="read-more color-meta-color"><i class="far fa-share"></i> %s</a>',
			get_permalink(),
			esc_html__( 'Read more', 'pure' )
		);
	} );

} );