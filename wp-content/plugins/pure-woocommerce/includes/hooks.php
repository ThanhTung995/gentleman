<?php

/**
 * Disable Woocommerce style for greater good.
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Remove Woocommerce breadcrumbs.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/**
 * Add our woocommerce folder to template list.
 */
add_filter( 'woocommerce_locate_template', 'pure_woocommerce_locate_template', 10, 3 );

add_action( 'after_setup_theme', function () {

	global $woocommerce;

	/** Take control of shop template loading */
	remove_filter( 'template_include', array( &$woocommerce, 'template_loader' ) );
	add_filter( 'template_include', 'pure_woocommerce_template_loader', 20 );
} );

/**
 * Override template loader.
 */
function pure_woocommerce_template_loader( $template ) {

	if ( is_single() && 'product' == get_post_type() ) {

		$template = locate_template( array( 'woocommerce/single-product.php' ) );

		if ( !$template ) {
			$template = PURE_WOOCOMMERCE_PATH . 'templates/single-product.php';
		}

	} elseif ( is_post_type_archive( 'product' ) || is_page( get_option( 'woocommerce_shop_page_id' ) ) ) {

		$template = locate_template( array( 'woocommerce/archive-product.php' ) );

		if ( !$template ) {
			$template = PURE_WOOCOMMERCE_PATH . 'templates/archive-product.php';
		}

	} elseif ( is_tax() ) {

		$term = get_query_var( 'term' );

		$tax = get_query_var( 'taxonomy' );

		/** Get an array of all relevant taxonomies */
		$taxonomies = get_object_taxonomies( 'product', 'names' );

		if ( in_array( $tax, $taxonomies ) ) {

			$tax  = sanitize_title( $tax );
			$term = sanitize_title( $term );

			$templates = array(
				'woocommerce/taxonomy-' . $tax . '-' . $term . '.php',
				'woocommerce/taxonomy-' . $tax . '.php',
				'woocommerce/taxonomy.php',
			);

			$template = locate_template( $templates );

			/** Fallback to GCW template */
			if ( !$template ) {
				$template = PURE_WOOCOMMERCE_PATH . 'templates/archive-product.php';
			}
		}
	}

	return $template;
}

/**
 * @param $template
 * @param $template_name
 * @param $template_path
 *
 * @return string
 */
function pure_woocommerce_locate_template( $template, $template_name, $template_path ) {

	global $woocommerce;

	$_template = $template;

	if ( !$template_path ) {
		$template_path = $woocommerce->template_url;
	}

	$plugin_path = PURE_WOOCOMMERCE_PATH . 'templates/';

	// Look within passed path within the theme - this is priority
	$template = locate_template(
		array(
			$template_path . $template_name,
			$template_name,
		)
	);

	// Get the template from this plugin, if it exists
	if ( !$template && file_exists( $plugin_path . $template_name ) ) {
		$template = $plugin_path . $template_name;
	}

	// Use default template
	if ( !$template ) {
		$template = $_template;
	}

	return $template;
}

add_filter( 'wc_get_template_part', 'pure_woocommerce_wc_get_template_parts', 10, 3 );
/**
 * @param $template
 * @param $slug
 * @param $name
 *
 * @return string
 */
function pure_woocommerce_wc_get_template_parts( $template, $slug, $name ) {

	global $woocommerce;

	$_template     = $template;
	$template_path = $woocommerce->template_url;
	$plugin_path   = PURE_WOOCOMMERCE_PATH . 'templates/';
	$template_name = "{$slug}.php";

	if ( $name ) {
		$template_name = "{$slug}-{$name}.php";
	}

	// Look within passed path within the theme - this is priority
	$template = locate_template(
		array(
			$template_path . $template_name,
			$template_name,
		)
	);

	// Get the template from this plugin, if it exists
	if ( !$template && file_exists( $plugin_path . $template_name ) ) {
		$template = $plugin_path . $template_name;
	}

	// Use default template
	if ( !$template ) {
		$template = $_template;
	}

	return $template;
}

add_filter( 'body_class', 'pure_woocommerce_classes', 10, 1 );
/**
 * Shop classes
 */
function pure_woocommerce_classes( $classes ) {

	if ( is_product_category() || is_product_tag() || is_shop() ) {
		if ( pure_get_option( 'woocommerce-listing-fullwidth' ) ) {
			$classes[] = 'pure-shop-fullwidth';
		}
		if ( pure_get_option( 'woocommerce-listing-animation' ) ) {
			$classes[] = 'pure-shop-animation';
		}
	}

	if ( is_product() ) {
		if ( 'ajax' != pure_get_option( 'wc_add_to_cart_type' ) ) {
			$classes[] = 'single-product-no-ajax';
		}
		if ( pure_get_option( 'wc_add_to_cart_large' ) ) {
			$classes[] = 'single-large-add-to-cart';
		}
	}

	return $classes;
}

/**
 * Add photoswipe support.
 */
add_theme_support( 'wc-product-gallery-lightbox' );

/**
 * Remove description heading.
 */
add_filter( 'woocommerce_product_description_heading', '__return_false' );
add_filter( 'woocommerce_product_additional_information_heading', '__return_false' );

add_action( 'template_redirect', 'pure_woocommerce_remove_single_product_sidebar', 11 );
/**
 * Remove Product sidebar on single product
 */
function pure_woocommerce_remove_single_product_sidebar() {

	if ( is_product() ) {
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	}
}

add_filter( 'add_to_cart_fragments', function ( $fragments ) {

	ob_start();

	echo '<span class="pure-cart-counter">';
	echo WC()->cart->get_cart_contents_count();
	echo '</span>';

	$fragments[ '.pure-cart-counter' ] = ob_get_clean();

	return $fragments;

} );

add_filter( 'wp_ajax_nopriv_pure_woocommerce_update_mini_cart', 'pure_woocommerce_update_mini_cart' );
add_filter( 'wp_ajax_pure_woocommerce_update_mini_cart', 'pure_woocommerce_update_mini_cart' );
/**
 * Update mini cart content.
 */
function pure_woocommerce_update_mini_cart() {

	ob_start();
	wc_get_template( 'cart/mini-cart.php' );
	$output = ob_get_clean();

	wp_send_json_success( array(
		'message'    => WC()->session->get( 'wc_notices' ),
		'cart_total' => WC()->cart->get_cart_contents_count(),
		'cart_html'  => $output,
	) );
}

add_action( 'wp_footer', 'pure_woocommerce_mini_cart' );
/**
 * Custom mini cart.
 */
function pure_woocommerce_mini_cart() {

	?>
    <div
            class="pure-woocommerce-mini-cart <?php echo esc_attr( pure_get_option( 'wc_mini_cart_position' ) ); ?>">
        <div class="mini-cart-header">
            <div class="js-close-mini-cart mini-cart-close color-bg-primary">
                <i class="ion-ios-close-empty"></i>
            </div>
            <h3 class="mini-cart-title color-bg-primary bg-color-heading">
				<?php echo pure_get_option( 'wc_mini_cart_title' ); ?>
            </h3>
        </div>
        <div class="pure-woocommerce-cart-wrapper bg-primary">
			<?php wc_get_template( 'cart/mini-cart.php' ); ?>
        </div>
    </div>
	<?php
}

add_action( 'woocommerce_widget_shopping_cart_buttons', 'pure_woocommerce_continue_shopping_button', 30 );
add_action( 'woocommerce_cart_actions', 'pure_woocommerce_continue_shopping_button' );
/**
 * Continue shopping button.
 */
function pure_woocommerce_continue_shopping_button() {

	?>
    <a class="js-close-mini-cart continue-shopping"
       href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php esc_html_e( 'Continue Shopping', 'pure-woocommerce' ); ?></a>
	<?php
}

add_action( 'woocommerce_share', function () {

//	echo '<div class="clear"></div>';
}, 1 );
add_action( 'woocommerce_share', 'pure_social_sharing', 10 );

add_action( 'template_redirect', 'pure_woocommerce_change_tabs_position', 11 );
/**
 * Allow changing tabs position through theme options.
 */
function pure_woocommerce_change_tabs_position() {

	if ( 'bellow_summary' === pure_get_option( 'wc_tab_position' ) ) {
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
		add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 100 );
	}
}

add_action( 'woocommerce_after_add_to_cart_button', 'pure_hidden_product_id_input' );
/**
 * Add a hidden input to support single ajax add to cart.
 */
function pure_hidden_product_id_input() {

	global $product;
	?>
    <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>"/>
	<?php
}

add_action( 'template_redirect', 'pure_woocommmerce_remove_add_to_cart_message' );
/**
 * Remove woocommerce notices, info and message.
 */
function pure_woocommmerce_remove_add_to_cart_message() {

	if ( !pure_get_option( 'wc_add_to_cart_type' ) ) {
		add_filter( 'wc_add_to_cart_message_html', '__return_false' );
	}
}

add_action( 'widgets_init', 'pure_woocommerce_widgets_init', 11 );
/**
 * Add a widget area for shop.
 */
function pure_woocommerce_widgets_init() {

	$widget_title_tag = apply_filters( 'pure_sidebar_widget_title_tag', 'h3' );
	pure_register_sidebar( array(
		'id'   => 'shop-sidebar',
		'name' => __( 'Shop Sidebar', 'pure-woocommerce' ),
	), $widget_title_tag );
}

add_action( 'woocommerce_before_shop_loop', 'pure_woocommerce_archive_actions_wrap_start', 11 );
/**
 * Wrap actions in cateogory page.
 */
function pure_woocommerce_archive_actions_wrap_start() {

	echo '<div class="pure-shop-actions">';
}

add_action( 'woocommerce_before_shop_loop', 'pure_woocommerce_archive_actions_wrap_end', 100 );
function pure_woocommerce_archive_actions_wrap_end() {

	echo '</div>';
}

add_action( 'woocommerce_before_shop_loop', 'pure_woocommerce_archive_filter_button', 15 );
/**
 * Filter Toggler Button.
 */
function pure_woocommerce_archive_filter_button() {

	echo '<div class="js-open-shop-sidebar shop-sidebar-icon color-heading-color color-primary-color--hover"><i class="fa fa-sliders-h"></i></div>';
}

add_action( 'woocommerce_before_shop_loop', 'pure_woocommerce_archive_layout_button', 25 );
/**
 * Layout Toggler Button.
 */
function pure_woocommerce_archive_layout_button() {

	echo '<div class="js-shop-list shop-list-icon"><span class="icon bg-heading-color bg-heading-color--after bg-heading-color--before bg-primary-color--hover bg-primary-color--before--hover bg-primary-color--after--hover"></span></div>';
	echo '<div class="js-shop-grid shop-grid-icon color-heading-color color-primary-color--hover"><i class="fa fa-th"></i></div>';
}

/**
 * Change default wrap for product listing.
 */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

function pure_woocommerce_loop_image_link_open() {

	echo '<a href="' . get_the_permalink() . '" title="' . get_the_title() . '" class="product-loop__image">';
}

function pure_woocommerce_loop_link_close() {

	echo '</a>';
}

function pure_woocommerce_loop_image_hover() {

	$style = pure_get_option( 'woocommerce-listing-item-hover-style' );

	if ( false !== strpos( $style, 'flip' ) ) {
		global $product;
		$image_size     = apply_filters( 'single_product_archive_thumbnail_size', 'shop_catalog' );
		$attachment_ids = $product->get_gallery_image_ids();
		$attachment_id  = $attachment_ids[ 0 ];
		echo wp_get_attachment_image( $attachment_id, $image_size, false, array( 'class' => 'secondary-image' ) );
	}

	if ( false !== strpos( $style, 'mask' ) ) {
		echo '<div class="mask-overlay" style="background-color: ' . pure_get_option( 'woocommerce-listing-item-hover-bg' ) . '"></div>';
	}

}

add_action( 'woocommerce_before_shop_loop_item_title', 'pure_woocommerce_loop_image_link_open', 1 );
add_action( 'woocommerce_before_shop_loop_item_title', 'pure_woocommerce_loop_image_hover', 90 );
add_action( 'woocommerce_before_shop_loop_item_title', 'pure_woocommerce_loop_link_close', 99 );

function pure_woocommerce_loop_info_open() {

	echo '<div class="product-loop__info">';
}

function pure_woocommerce_loop_info_close() {

	echo '</div>';
}

add_action( 'woocommerce_before_shop_loop_item_title', 'pure_woocommerce_loop_info_open', 100 );
add_action( 'woocommerce_after_shop_loop_item', 'pure_woocommerce_loop_info_close', 99 );

function pure_woocommerce_loop_title_link_open() {

	echo '<a href="' . get_the_permalink() . '" title="' . get_the_title() . '" class="product-loop__title-link">';
}

add_action( 'woocommerce_shop_loop_item_title', 'pure_woocommerce_loop_title_link_open', 1 );
add_action( 'woocommerce_shop_loop_item_title', 'pure_woocommerce_loop_link_close', 99 );

/**
 * Add product description to shop loop for list layout.
 */
function pure_woocommerce_loop_description() {

	global $post;

	if ( !$post->post_excerpt ) {
		return;
	}

	?>
    <div class="product-loop__description">
		<?php echo $post->post_excerpt; ?>
    </div>
	<?php
}

add_action( 'woocommerce_after_shop_loop_item_title', 'pure_woocommerce_loop_description', 15 );

/**
 * Add post class to product listing.
 *
 * @param $classes
 * @param $class
 * @param $postid
 *
 * @return array
 */
function pure_woocommerce_post_class( $classes, $class, $postid ) {

	if ( 'product' === get_post_type( $postid ) ) {
//		$classes[] = 'size-' . get_post_meta( $postid, 'product-metro-image-size' );
//		$classes[] = 'grid-item';
		$classes[] = 'loop__item';
		if ( 'mask-flip' === pure_get_option( 'woocommerce-listing-item-hover-style' ) ) {
			$classes[] = 'hover-mask';
			$classes[] = 'hover-flip';
		} else {
			$classes[] = 'hover-' . pure_get_option( 'woocommerce-listing-item-hover-style' );
		}
	}

	return $classes;
}

add_filter( 'post_class', 'pure_woocommerce_post_class', 10, 3 );

/**
 * Wrap product loop action in a div
 */
add_action( 'woocommerce_after_shop_loop_item', 'pure_loop_actions_open', 5 );
function pure_loop_actions_open() {

	echo '<div class="actions">';
}

add_action( 'woocommerce_after_shop_loop_item', 'pure_loop_actions_close', 999 );
function pure_loop_actions_close() {

	echo '</div>';
}

add_filter( 'pure_woocommerce_loop_item_attributes', 'pure_woocommerce_loop_item_attributes' );

function pure_woocommerce_loop_item_attributes( $atts ) {

	if ( is_product_category() || is_product_tag() || is_shop() ) {
		$pagination_style = pure_get_option( 'pagination_style', 'number' );
		if ( pure_get_option( 'woocommerce-listing-animation' ) ) {
			if ( 'number' === $pagination_style ) {
				$atts .= ' data-animation-enable="1" data-animation="PureWoocommercefadeInUp" data-animation-delay="300" ';
			} else {
				if ( get_query_var( 'paged' ) <= 1 ) {
					$atts .= ' data-animation-enable="1" data-animation="PureWoocommercefadeInUp" data-animation-delay="300" ';
				}
			}
		}
	}

	return $atts;
}

add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );
/**
 * Change number of posts per page for woocommece loop.
 *
 * @param $cols
 *
 * @return int
 */
function new_loop_shop_per_page( $cols ) {

	$cols = intval( pure_get_option( 'wc_listing_perpage' ) );

	return $cols;
}

add_filter( 'woocommerce_output_related_products_args', 'pure_woocommerce_related_products_args' );
/**
 * Change number of related products.
 *
 * @param $args
 *
 * @return mixed
 */
function pure_woocommerce_related_products_args( $args ) {

	$args[ 'posts_per_page' ] = intval( pure_get_option( 'wc_related_count' ) );

	return $args;
}

add_filter( 'woocommerce_checkout_fields', 'pure_woocommerce_override_checkout_fields' );
/**
 * @param $fields
 *
 * @return mixed
 */
function pure_woocommerce_override_checkout_fields( $fields ) {

	$fields[ 'billing' ][ 'billing_first_name' ][ 'placeholder' ] = __( 'First name', 'pure-woocommerce' );
	unset( $fields[ 'billing' ][ 'billing_last_name' ] );
	unset( $fields[ 'billing' ][ 'billing_company' ] );
	unset( $fields[ 'billing' ][ 'billing_postcode' ] );
	unset( $fields[ 'billing' ][ 'billing_city' ] );
	unset( $fields[ 'billing' ][ 'billing_country' ] );
	unset( $fields[ 'billing' ][ 'billing_address_2' ] );
	$fields[ 'billing' ][ 'billing_phone' ][ 'placeholder' ] = __( 'Phone', 'pure-woocommerce' );
	$fields[ 'billing' ][ 'billing_email' ][ 'placeholder' ] = __( 'Email address', 'pure-woocommerce' );

	$fields[ 'shipping' ][ 'shipping_first_name' ][ 'placeholder' ] = __( 'First name', 'pure-woocommerce' );
	$fields[ 'shipping' ][ 'shipping_last_name' ][ 'placeholder' ]  = __( 'Last name', 'pure-woocommerce' );
	unset( $fields[ 'shipping' ][ 'shipping_country' ] );
	unset( $fields[ 'shipping' ][ 'shipping_address_2' ] );
	unset( $fields[ 'shipping' ][ 'shipping_company' ] );
	unset( $fields[ 'shipping' ][ 'shipping_postcode' ] );
	unset( $fields[ 'shipping' ][ 'shipping_city' ] );

	return $fields;
}

//add_action( 'pure_woocommerce_product_video', 'pure_woocommerce_product_video' );
/**
 * Parse Product video
 */
function pure_woocommerce_product_video() {

	$url = get_post_meta( get_the_ID(), 'product-video-link' );
	if ( !$url ) {
		return;
	}
	?>
    <a class="product-video" href="<?php echo esc_url( $url ); ?>">
		<span class="insider color-heading color-primary--hover">
			<i class="ion-ios-play"></i> View Video
		</span>
    </a>
	<?php
}

/**
 * Add wish list icon to product loop.
 */
//function pure_woocommerce_loop_add_to_wish_list() {
//
//	if ( is_plugin_active( 'yith-woocommerce-wishlist/init.php' ) ) {
//		echo do_shortcode( "[yith_wcwl_add_to_wishlist]" );
//	}
//}
//
//add_action( 'woocommerce_after_shop_loop_item', 'pure_woocommerce_loop_add_to_wish_list', 20 );

add_action( 'woocommerce_after_shop_loop_item', 'pure_view_product_detail_button', 20 );
/**
 * Add view detail button to product loop actions.
 */
function pure_view_product_detail_button() {

	?>
    <a class="button view-detail" href="<?php the_permalink() ?>"><i class="fa fa-arrow-alt-circle-right"></i> <span
                class="txt"><?php echo esc_html__( 'View Detail', 'pure-woocommerce' ) ?></span></a>
	<?php
}


add_action( 'woocommerce_after_shop_loop_item', 'pure_quick_view_button', 30 );
/**
 * Add quick view button to product loop.
 */
function pure_quick_view_button() {

	if ( pure_get_option( 'wc_quick_view' ) ) {
		global $product;
		printf(
			'<span class="button js-quick-view quick-view-btn alt" data-title="%s" data-id="%d" ><i class="fa fa-eye"></i><span class="txt">%s</span></span>',
			apply_filters( 'pure_woocommerce_quick_view_text', __( 'Quick view', 'pure-woocommerce' ) ),
			$product->get_id(),
			apply_filters( 'pure_woocommerce_quick_view_text', __( 'Quick view', 'pure-woocommerce' ) )
		);
	}
}


add_action( 'wp_footer', function () {

	if ( pure_get_option( 'wc_quick_view' ) ) {
		echo '<div class="pure-quick-view"></div>';
		echo '<div class="quick-view-overlay"><div class="overlay-loader"><div class="loader"></div></div></div>';
	}
} );

function pure_woocommerce_quick_view() {

	if ( isset( $_POST[ 'product_id' ] ) && (int)$_POST[ 'product_id' ] ) {
		global $product, $post;
		$product_id = $_POST[ 'product_id' ];
		$product    = get_product( $product_id );
		$post       = get_post( $product_id );
		if ( $product ) {
			wc_get_template( 'content-quick-view.php' );
		}
	}
	die();
}

add_filter( 'wp_ajax_nopriv_pure_woocommerce_quick_view', 'pure_woocommerce_quick_view' );
add_filter( 'wp_ajax_pure_woocommerce_quick_view', 'pure_woocommerce_quick_view' );

add_action( 'pure_quick_view_product_summary', 'woocommerce_template_single_rating', 10 );
add_action( 'pure_quick_view_product_summary', 'woocommerce_template_single_price', 20 );
add_action( 'pure_quick_view_product_summary', 'woocommerce_template_single_excerpt', 30 );

add_filter( 'woocommerce_product_add_to_cart_text', 'pure_woocommerce_product_add_to_cart_text', 10, 2 );
add_filter( 'woocommerce_product_single_add_to_cart_text', 'pure_woocommerce_product_add_to_cart_text', 10, 2 );
/**
 * Filter add to cart text
 *
 * @param $text
 *
 * @return mixed|null|string
 */
function pure_woocommerce_product_add_to_cart_text( $text, $instance ) {

	global $product;

	$product_type = $product->get_type();

	switch ( $product_type ) {
		case 'simple':
			return pure_get_option( 'wc_add_to_cart_text' );
			break;
	}

	return $text;
}

function pure_woocommerce_direct_checkout() {

	if ( 'direct' === pure_get_option( 'wc_add_to_cart_type' ) ) {
		return WC()->cart->get_checkout_url();
	}
}

add_filter( 'woocommerce_add_to_cart_redirect', 'pure_woocommerce_direct_checkout' );

add_filter( 'pure_woocommerce_single_featured_image_html', 'pure_woocommerce_remove_thumbnail' );

function pure_woocommerce_remove_thumbnail( $image ) {

	global $post;

	$option = get_post_meta( $post->ID, 'product-remove-featured-image' );

	if ( 'remove' === $option ) {
		return '';
	}

	if ( !$option ) {
		if ( pure_get_option( 'wc_single_remove_featured_image' ) ) {
			return '';
		}
	}

	return $image;
}

function pure_wc_price( $price, $class = '' ) {

	extract( apply_filters( 'wc_price_args', array(
		'ex_tax_label'       => false,
		'currency'           => '',
		'decimal_separator'  => wc_get_price_decimal_separator(),
		'thousand_separator' => wc_get_price_thousand_separator(),
		'decimals'           => wc_get_price_decimals(),
		'price_format'       => get_woocommerce_price_format(),
	) ) );


	$unformatted_price = $price;
	$negative          = $price < 0;
	$price             = apply_filters( 'raw_woocommerce_price', floatval( $negative ? $price * -1 : $price ) );
	$price             = apply_filters( 'formatted_woocommerce_price', number_format( $price, $decimals, $decimal_separator, $thousand_separator ), $price, $decimals, $decimal_separator, $thousand_separator );

	if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && $decimals > 0 ) {
		$price = wc_trim_zeros( $price );
	}

	$price = '<span itemprop="price" content="' . $unformatted_price . '">' . $price . '</span>';

	$formatted_price = ( $negative ? '-' : '' ) . sprintf( $price_format, '<span class="woocommerce-Price-currencySymbol" itemprop="priceCurrency" content="VND">' . get_woocommerce_currency_symbol( $currency ) . '</span>', $price );
	$return          = '<span class="woocommerce-Price-amount amount ' . $class . '">' . $formatted_price . '</span>';

	if ( $ex_tax_label && wc_tax_enabled() ) {
		$return .= ' <small class="woocommerce-Price-taxLabel tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
	}

	return $return;
}

add_filter( 'woocommerce_get_price_html', 'pure_price_html' );

function pure_price_html( $price ) {

	global $product;

	if ( $product->is_type( 'simple' ) ) {
		ob_start();
		if ( $product->is_on_sale() ) {
			echo '<del>' . wc_price( $product->get_regular_price() ) . '</del>';
			echo '<ins>' . pure_wc_price( $product->get_sale_price(), 'color-secondary-color' ) . '</ins>';
		} else {
			echo pure_wc_price( $product->get_regular_price(), 'color-secondary-color' );
		}

		return ob_get_clean();
	}

	return $price;
}

//add_filter( 'woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2 );
/**
 * Change currency symbol to VND
 *
 * @param $currency_symbol
 * @param $currency
 *
 * @return string
 */
function change_existing_currency_symbol( $currency_symbol, $currency ) {

	switch ( $currency ) {
		case 'VND':
			$currency_symbol = 'VND';
			break;
	}

	return $currency_symbol;
}

/**
 * Add placeholder for review form.
 */
add_filter( 'woocommerce_product_review_comment_form_args', function ( $comment_form ) {

	$commenter = wp_get_current_commenter();

	$comment_form[ 'fields' ][ 'author' ] = '<p class="comment-form-author">' . '<label for="author">' . esc_html__( 'Name', 'woocommerce' ) . ' <span class="required">*</span></label> ' .
		'<input id="author" name="author" type="text" value="' . esc_attr( $commenter[ 'comment_author' ] ) . '" size="30" aria-required="true" required placeholder="' . esc_attr( 'Name: John Doe', 'pure-woocommerce' ) . '" /></p>';

	$comment_form[ 'fields' ][ 'email' ] = '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'woocommerce' ) . ' <span class="required">*</span></label> ' .
		'<input id="email" name="email" type="email" value="' . esc_attr( $commenter[ 'comment_author_email' ] ) . '" size="30" aria-required="true" required placeholder="' . esc_attr__( 'Email: name@email.com', 'pure-woocommerce' ) . '" /></p>';

	if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
		$comment_form[ 'comment_field' ] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'woocommerce' ) . '</label><select name="rating" id="rating" aria-required="true" required>
							<option value="">' . esc_html__( 'Rate&hellip;', 'woocommerce' ) . '</option>
							<option value="5">' . esc_html__( 'Perfect', 'woocommerce' ) . '</option>
							<option value="4">' . esc_html__( 'Good', 'woocommerce' ) . '</option>
							<option value="3">' . esc_html__( 'Average', 'woocommerce' ) . '</option>
							<option value="2">' . esc_html__( 'Not that bad', 'woocommerce' ) . '</option>
							<option value="1">' . esc_html__( 'Very poor', 'woocommerce' ) . '</option>
						</select></div>';
	}
	$comment_form[ 'comment_field' ] .= '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your review', 'woocommerce' ) . ' <span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" required placeholder="' . esc_attr__( 'Type your comments..', 'pure-woocommerce' ) . '"></textarea></p>';

	return $comment_form;
} );

add_filter( 'woocommerce_product_get_rating_html', 'pure_wc_get_rating_html', 10, 3 );
/**
 * Filter star rating.
 *
 * @param  float $rating Rating being shown.
 * @param  int   $count  Total number of ratings.
 *
 * @return string
 */
function pure_wc_get_rating_html( $html, $rating, $count ) {

	if ( 0 < $rating ) {
		$html = '<div class="star-rating">';
		$html .= '<span class="stars"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></span>';
		$html .= wc_get_star_rating_html( $rating, $count );
		$html .= '</div>';
	} else {
		$html = '';
	}

	return $html;
}

add_filter( 'woocommerce_get_star_rating_html', 'pure_wc_get_star_rating_html', 10, 3 );
/**
 * Filter star rating.
 *
 * @since  3.1.0
 *
 * @param  float $rating Rating being shown.
 * @param  int   $count  Total number of ratings.
 *
 * @return string
 */
function pure_wc_get_star_rating_html( $html, $rating, $count ) {

	$html = '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%">';
	$html .= '<span class="stars"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></span>';

	if ( 0 < $count ) {
		$html .= '<span class="txt" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">';
		/* translators: 1: rating 2: rating count */
		$html .= sprintf( _n( 'Rated %1$s out of 5 based on %2$s customer rating', 'Rated %1$s out of 5 based on %2$s customer ratings', $count, 'woocommerce' ), '<strong class="rating" itemprop="ratingValue">' . esc_html( $rating ) . '</strong>', '<span class="rating" itemprop="reviewCount">' . esc_html( $count ) . '</span>' );
	} else {
		$html .= '<span class="txt" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">';
		$html .= '<meta itemprop="worstRating" content = "1">';
		$html .= sprintf( esc_html__( 'Rated %s out of %s', 'woocommerce' ), '<strong class="rating" itemprop="ratingValue">' . esc_html( $rating ) . '</strong>', '<span itemprop="bestRating">5</span>' );
	}
	$html .= '</span>';

	$html .= '</span>';

	return $html;
}

add_filter( 'pure_sidebar_layout', function ( $layout ) {

	$page_id = get_the_ID();

	if ( get_option( 'woocommerce_cart_page_id' ) == $page_id || get_option( 'woocommerce_checkout_page_id' ) == $page_id || get_option( 'woocommerce_myaccount_page_id' ) == $page_id || is_singular( 'product' ) ) {
		return 'c';
	}

	return $layout;
} );

add_action( 'template_redirect', function () {

	$page_id = get_the_ID();

	if ( get_option( 'woocommerce_cart_page_id' ) == $page_id ) {
		remove_action( 'pure_entry_header', 'pure_entry_title', 10 );
	}
} );


add_filter( 'woocommerce_product_tabs', 'pure_remove_product_tabs', 98 );

function pure_remove_product_tabs( $tabs ) {

	unset( $tabs[ 'additional_information' ] );    // Remove the additional information tab

	return $tabs;
}

/**
 * Add stock status to beginning of product meta.
 */
add_action( 'woocommerce_product_meta_start', function () {

	global $product;

	?>
    <span class="stock-wrapper">
<span class="status <?php echo ( $product->is_in_stock() ) ? 'instock' : 'outstock'; ?>"><?php echo ( $product->is_in_stock() ) ? esc_html__( 'In stock', 'woocommerce' ) : esc_html__( 'Out of stock', 'woocommerce' ); ?></span></span>
	<?php
} );

/**
 * Force shipping to billing address.
 */
add_filter( 'woocommerce_cart_needs_shipping_address', '__return_false' );

add_action( 'pure_woocommerce_single_sidebar', 'pure_woocommerce_related_news', 20 );
/**
 * Hook related news to product single sidebar
 */
function pure_woocommerce_related_news() {

	$related = array(
		'count' => apply_filters( 'pure_related_posts_count', 6 ),
		'title' => apply_filters( 'pure_related_posts_title', __( 'Related Posts', 'pure' ) ),
	);

	$current_post_id = get_the_ID();

	// Get custom related posts
	$custom_related_posts = array();
	if ( function_exists( 'get_field' ) ) {
		$custom_related_posts = get_field( 'product_related_news_id', $current_post_id );
	}

	if ( !empty( $custom_related_posts ) ) {
		$args = array(
			'post_type' => 'post',
			'order'     => 'DESC',
			'post__in'  => $custom_related_posts,
		);
	} else {
		// Get cat of single post.
		$cat_id = 0;
		if ( function_exists( 'get_field' ) ) {
			$cat_id = get_field( 'product_related_news_cat', $current_post_id );
		}
		if ( $cat_id ) {
			$args = array(
				'post_type'      => 'post',
				'order'          => 'DESC',
				'posts_per_page' => $related[ 'count' ],
				'cat'            => $cat_id,
			);
		} else {
			$args = array(
				'post_type'      => 'post',
				'order'          => 'DESC',
				'posts_per_page' => $related[ 'count' ],
			);
		}
	}

	$the_query = new WP_Query( $args );

	// Start output.
	if ( $the_query->have_posts() && $the_query->found_posts > 1 ) : ?>

        <div class="pure-woocommerce-related-posts">

            <h2 class="pure-title color-primary-color"><?php echo esc_html( $related[ 'title' ] ); ?></h2>

            <div class="post-list">
				<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                    <div class="item">
                        <div class="insider">
                            <a class="thumbnail">
								<?php the_post_thumbnail( 'thumbnail' ) ?>
                            </a>
                            <div class="info">
                                <a class="title">
									<?php the_title() ?>
                                </a>
								<?php echo do_shortcode( '[post_time]' ) ?>
                            </div>
                        </div>
                    </div>
				<?php endwhile; ?>
            </div>
        </div> <!-- /.pure-related-posts -->

	<?php
	endif;
	wp_reset_postdata(); // Restore the main query.
}

/**
 * Handle when user did not enter product price
 */
add_filter( 'woocommerce_empty_price_html', 'pure_woocommerce_call_for_price' );

function pure_woocommerce_call_for_price() {

	return sprintf(
		'<span class="color-secondary-color">%s</span>',
		__( 'Contact', 'pure-woocommerce' )
	);
}

add_filter( 'woocommerce_get_price_html', 'pure_woocommerce_price_free_zero_empty', 100, 2 );

function pure_woocommerce_price_free_zero_empty( $price, $product ) {

	if ( '' === $product->get_price() || 0 == $product->get_price() ) {
		return sprintf(
			'<span class="color-secondary-color">%s</span>',
			__( 'Contact', 'pure-woocommerce' )
		);
	}

	return $price;
}

/**
 * Product templates adjustment
 */
add_action( 'template_redirect', function () {

	if ( is_product() ) {
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 6 );
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 11 );
		add_action( 'woocommerce_single_product_summary', 'pure_woocommerce_promotion', 25 );
		add_action( 'woocommerce_single_product_summary', 'pure_woocommerce_hotline', 30 );

		add_action( 'woocommerce_after_single_product_summary', 'pure_woocommere_single_under_open', 5 );
		add_action( 'woocommerce_after_single_product_summary', 'pure_woocommere_single_sidebar', 12 );
		add_action( 'woocommerce_after_single_product_summary', 'pure_woocommere_single_under_close', 13 );
	}

} );
