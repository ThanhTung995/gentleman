<?php
/**
 * Gallery Single.
 * Use action hooks to adjust single template for gallery.
 *
 * @package    PureCore
 * @subpackage Gallery
 * @author     Boong
 * @link       https://boongstudio.com/plugins/gallery
 */

add_action( 'template_redirect', function () {

	if ( !is_singular( 'gallery' ) ) {
		return;
	}

	remove_action( 'pure_entry_header', 'pure_social_sharing', 15 );
//	remove_action( 'pure_entry_footer', 'pure_social_sharing', 15 );

	remove_action( 'pure_after_entry', 'pure_related_posts' );
	remove_action( 'pure_after_entry', 'pure_comment_template', 20 );

	add_action( 'pure_entry_header', function () {

		echo '<div class="meta text-center">';
		echo '<span class="post-time mgr20">';
		echo do_shortcode( '[post_time][post_modified_time]' );
		echo '</span>';

		echo '<span class="post-views"><i class="far fa-eye"></i> ' . pure_get_post_views( get_the_ID() ) . '</span>';
		echo '</div>';
	}, 12 );

	add_action( 'pure_after_entry', function () {

		echo '<div class="full-width comment-wrapper bg-primary-bg">';
		pure_comment_template();
		echo '</div>';
	} );

	add_action( 'pure_after_entry_content', function () {

		$images = get_field( 'gallery' );

		if ( empty( $images ) ) {
			return;
		}

		echo '<div class="pure-gallery grid masonry column-3" itemscope itemtype="http://schema.org/ImageGallery">';
		echo '<div class="grid-sizer"></div>';

		foreach ( $images as $key => $image ) {
			?>
            <figure class="gallery__item grid-item" itemprop="associatedMedia" itemscope
                    itemtype="http://schema.org/ImageObject">
                <a href="<?php echo esc_url( $image[ 'url' ] ) ?>" itemprop="contentUrl"
                   data-size="<?php printf( '%dx%d', $image[ 'width' ], $image[ 'height' ] ) ?>">
                    <img src="<?php echo esc_url( $image[ 'sizes' ][ 'medium_large' ] ) ?>" itemprop="thumbnail"
                         alt="<?php echo esc_attr( $image[ 'alt' ] ) ?>"/>
                </a>
            </figure>
			<?php
		}

		echo '</div>';
	} );
} );