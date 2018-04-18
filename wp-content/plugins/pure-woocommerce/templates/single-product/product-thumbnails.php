<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.0.2
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}
global $post, $product;
$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
$attachment_ids    = $product->get_gallery_image_ids();
$image_title       = get_post_field( 'post_excerpt', $post_thumbnail_id );
$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
$thumb_position    = pure_get_option( 'wc_single_thumbnails_position', 'bottom' );

?>

<figure class="woocommerce-product-thumbnails__wrapper">
	<?php
	$attributes = array(
		'title' => $image_title,
	);

	if ( has_post_thumbnail() ) {
		$html = '<div class="woocommerce-product-thumbnails__image">';
		$html .= get_the_post_thumbnail( $post->ID, 'shop_thumbnail', $attributes );
		$html .= '</div>';
	} else {
		$size              = wc_get_image_size( 'shop_thumbnail' );
		$placeholder_width = $size[ 'width' ];
		$html              = '<div class="woocommerce-product-thumbnails__image--placeholder">';
		$html              .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" width="%d" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ), $placeholder_width );
		$html              .= '</div>';
	}

	echo apply_filters( 'pure_woocommerce_single_featured_image_html', $html, get_post_thumbnail_id( $post->ID ) );

	if ( $attachment_ids && has_post_thumbnail() ) {
		foreach ( $attachment_ids as $attachment_id ) {
			$image_title = get_post_field( 'post_excerpt', $attachment_id );

			$attributes = array(
				'title' => $image_title,
			);

			$html = '<div class="woocommerce-product-thumbnails__image">';
			$html .= wp_get_attachment_image( $attachment_id, 'shop_thumbnail', false, $attributes );
			$html .= '</div>';

			echo apply_filters( 'pure_woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
		}
	}
	?>

</figure>

