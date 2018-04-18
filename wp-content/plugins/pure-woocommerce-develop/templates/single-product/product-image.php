<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.2
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;
$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
$attachment_ids    = $product->get_gallery_image_ids();
$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
$image_title       = get_post_field( 'post_excerpt', $post_thumbnail_id );
$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
$thumb_position    = pure_get_option( 'wc_single_thumbnails_position', 'bottom' );
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'woocommerce-product-gallery',
	'woocommerce-product-gallery--' . $placeholder,
	'woocommerce-product-gallery--columns-' . absint( $columns ),
	'images',
	$thumb_position,
) );

?>

<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>"
     data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
    <div class="woocommerce-product-gallery__outer-wrapper">
        <figure class="woocommerce-product-gallery__wrapper">
			<?php
			$attributes = array(
				'title'                   => $image_title,
				'data-src'                => $full_size_image[ 0 ],
				'data-large_image'        => $full_size_image[ 0 ],
				'data-large_image_width'  => $full_size_image[ 1 ],
				'data-large_image_height' => $full_size_image[ 2 ],
				'itemprop'                => 'image',
			);

			if ( has_post_thumbnail() ) {
				$html = '<div data-thumb="' . get_the_post_thumbnail_url( $post->ID, 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[ 0 ] ) . '">';
				$html .= get_the_post_thumbnail( $post->ID, 'shop_single', $attributes );
				$html .= '</a></div>';
			} else {
				$size              = wc_get_image_size( 'shop_single' );
				$placeholder_width = $size[ 'width' ];
				$html              = '<div class="woocommerce-product-gallery__image--placeholder">';
				$html              .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" width="%d" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ), $placeholder_width );
				$html              .= '</div>';
			}

			echo apply_filters( 'pure_woocommerce_single_featured_image_html', $html, get_post_thumbnail_id( $post->ID ) );

			if ( $attachment_ids && has_post_thumbnail() ) {
				foreach ( $attachment_ids as $attachment_id ) {
					$full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
					$image_title     = get_post_field( 'post_excerpt', $attachment_id );

					$attributes = array(
						'title'                   => $image_title,
						'data-src'                => $full_size_image[ 0 ],
						'data-large_image'        => $full_size_image[ 0 ],
						'data-large_image_width'  => $full_size_image[ 1 ],
						'data-large_image_height' => $full_size_image[ 2 ],
					);

					$html = '<div data-thumb="' . esc_url( wp_get_attachment_image_url( $attachment_id, 'shop_thumbnail' ) ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[ 0 ] ) . '">';
					$html .= sprintf(
						'<img data-flickity-lazyload="%s" %s />',
						esc_url( wp_get_attachment_image_url( $attachment_id, 'shop_single' ) ),
						pure_attr( 'photoswipe', $attributes )
					);
//					$html .= wp_get_attachment_image( $attachment_id, 'shop_single', false, $attributes );
					$html .= '</a></div>';

					echo apply_filters( 'pure_woocommerce_single_product_image_gallery_html', $html, $attachment_id );
				}
			}
			?>

        </figure>
		<?php do_action( 'pure_woocommerce_product_video' ); ?>
    </div>
	<?php do_action( 'woocommerce_product_thumbnails' ); ?>
</div>
