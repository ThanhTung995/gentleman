<?php
/**
 * Add your changes from here. Try do add action, filter to customize Pure.
 *
 * @package Pure Academy
 */

/**
 * Define theme constant.
 */
$pure_theme = wp_get_theme();
define( 'CHILD_THEME_NAME', $pure_theme->get( 'Name' ) );
define( 'CHILD_THEME_SLUG', $pure_theme->get( 'TextDomain' ) );
define( 'CHILD_THEME_VERSION', $pure_theme->get( 'Version' ) );

define( 'CHILD_DIR', get_stylesheet_directory() );
define( 'CHILD_URL', get_stylesheet_directory_uri() );

add_action( 'after_setup_theme', 'pure_sample_i18n' );
/**
 * Load text domain
 */
function pure_sample_i18n() {

	load_theme_textdomain( 'pure-sample', get_stylesheet_directory() . '/languages' );
}

add_action( 'wp_enqueue_scripts', 'pure_child_scripts', 11 );
/**
 * Enqueue scripts and styles.
 */
function pure_child_scripts() {

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';
	// Theme stylesheet.
	wp_enqueue_style( CHILD_THEME_SLUG, get_theme_file_uri( '/css/css' . $suffix . '.css' ), array( PARENT_THEME_NAME ), CHILD_THEME_VERSION );

	wp_enqueue_script( CHILD_THEME_SLUG, get_theme_file_uri( '/js/js' . $suffix . '.js' ), array( PARENT_THEME_NAME ), CHILD_THEME_VERSION, true );
}

add_filter( 'pure_get_default_option', 'pure_gentleman_default_option' );
/**
 * Adjust variables.
 */
function pure_gentleman_default_option() {

    return array(
        'footer_bg' => array(
            'background-color' => '#0e2633',
        ),

        'footer_heading_color'    => '#ffffff',
        'footer_text_color'       => '#69747a',
        'footer_link_color'       => '#69747a',
        'footer_link_hover_color' => '#ffffff',
        'footer_text_size'        => '15px',
        'footer_heading_size'     => '24px',

        'primary_color' => '#033043',
        'text_color'    => '#777777',
        'heading_color' => '#222222',
        'border_color'  => '#e4e4e4',
        'secondary_bg'  => '#f5f5f5',
        'primary_bg'    => '#ffffff',

        'wc_single_thumbnails_position' => 'left',
        'wc_tab_position' => 'bellow_summary'
        //'wc_tab_style' => 'h-tab', // h-tab, v-tab, accordion, none
    );
}

add_filter( 'pure_google_fonts', 'pure_gentleman_google_fonts' );
/**
 * Load google font of this childtheme.
 */
function pure_gentleman_google_fonts() {

    return array(
        array(
            'family'     => 'Roboto',
            'variations' => '400,400italic,600,700',
            'subset'     => 'latin,vietnamese',
        ),
        array(
            'family'     => 'Judson',
            'variations' => '400',
            'subset'     => 'latin,vietnamese',
        ),
    );
}

/**
 * Change image size for shop_thumbnail
 */
add_filter( 'woocommerce_get_image_size_gallery_thumbnail', function () {

    return array(
        'width'  => 126,
        'height' => 93,
        'crop'   => 1,
    );
} );

function pure_header() {

    pure_wrap( 'site-header', 'open', true, true );

    pure_logo();

    pure_tag( array(
        'open'    => '<nav %s>',
        'context' => 'site-navigation',
    ) );
    wp_nav_menu( array(
        'theme_location' => 'primary',
        'container'      => '',
        'menu_class'     => 'sf-menu',
    ) );

    pure_tag( array(
        'close'   => '</nav>',
        'context' => 'site-navigation',
    ) );

    echo get_search_form();

    if ( class_exists( 'WooCommerce' ) ) : ?>
        <div class="pure-woocommerce-mini-cart-icon bg-primary-color color-primary-bg">
            <i class="fa fa-shopping-cart"></i>
            <span class="cart-counter-wrapper bg-heading-color color-primary-bg">
			<span class="cart-counter">
				<?php echo WC()->cart->get_cart_contents_count(); ?>
			</span>
		</span>
        </div>
    <?php endif;

    pure_wrap( 'site-header', 'close', true, true );
}

add_action('pure_copyright_additional_content',function (){
    echo '<div class="fo-social">';
    echo "Theo dõi chúng tôi :";
    echo '<a href="#"><i class="fab fa-facebook-square"></i></a> <a href="#"><i class="fab fa-instagram"></i></a>';
    echo '</div>';
});

add_filter( 'pure_sidebar_layout', function () {
    return 'c';
});

add_filter('pure_thumbnail_size',function (){
    return array(
        'width'  => '870',
        'height' => '322',
    );
});

add_action('pure_before_entry',function (){
    echo '<div class="loop-item-hover">';
},4);

add_action('pure_before_entry',function (){
    echo '<i class="far fa-share"></i>';
    ?>
    <p class='ct'><a href="<?php the_permalink(); ?>">Xem chi tiết</a></p>
    <?php
},6);

add_action('pure_before_entry',function (){
    echo '</div>';
},7);

add_action('pure_before_entry',function (){
    if(is_single()){
        the_post_thumbnail();
    }
},7);

remove_action( 'woocommerce_before_shop_loop', 'pure_woocommerce_archive_layout_button', 25 );

add_action('woocommerce_before_shop_loop',function (){
    echo "<span class='sx-filer'>Sắp xếp theo: </span>";
},24);

remove_action( 'woocommerce_before_shop_loop', 'pure_woocommerce_archive_filter_button', 15 );

add_action('woocommerce_before_shop_loop',function (){
    global $post;
    $terms = get_terms( array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
    ));
    echo '<ul class="filter-cat-product">';

    foreach ($terms as $term) {
        ?>
        <li><a class="<?php
            $terms = get_the_terms( $post->ID, 'product_cat' );
            foreach ($terms as $item){
                $data_term = $item->name;
                if($data_term == $term->name){
                    echo 'active';
                }
            }
            ?>" href="<?php echo home_url( '/danh-muc/' );echo $term->slug; ?>"><?php echo $term->name; ?></a></li>
        <?php
    }
    echo '</ul>';
},22);



add_action('woocommerce_single_product_summary',function (){
    global $product;
    $attributes = $product->get_attributes();
    foreach ($attributes as $data) {
        echo "Hãng: ".$data['options'][0];
        break;
    }
    ?>
    <span class="stock-wrapper">
    Tình trạng: <span class="status <?php echo ( $product->is_in_stock() ) ? 'instock' : 'outstock'; ?>"><?php echo ( $product->is_in_stock() ) ? esc_html__( 'In stock', 'woocommerce' ) : esc_html__( 'Out of stock', 'woocommerce' ); ?></span></span>
    <?php
},6);

remove_action('woocommerce_single_product_summary','woocommerce_template_single_excerpt',20);
add_action('woocommerce_single_product_summary',function (){
    echo "<h3 class='title_des_meta'>Mô tả chi tiết</h3>";
},31);
add_action('woocommerce_single_product_summary','woocommerce_template_single_excerpt',32);

remove_action( 'woocommerce_share', 'pure_social_sharing', 10 );

add_action('woocommerce_single_product_summary',function (){
    echo "<h3 class='title_mes_meta'>Bình luận</h3>";
},60);

add_action( 'woocommerce_single_product_summary', 'pure_woocommerce_hotline', 30 );

add_action('woocommerce_before_single_product_summary','pure_social_sharing',21);

//short code
require_once "inc/short_product_filter.php";

//ajax script
function theme_script(){
    wp_register_script('ajax-product-filter', get_theme_file_uri( '/js/ajax.js' ), array('jquery'));
    wp_enqueue_script('ajax-product-filter');

    wp_localize_script( 'ajax-product-filter', 'FilterAjax',
        array( 'ajaxurl' 	 => admin_url( 'admin-ajax.php' ))
    );
}

add_action('wp_enqueue_scripts', 'theme_script');

//ajax data
require_once "inc/ajax/ajax_product_filter.php";