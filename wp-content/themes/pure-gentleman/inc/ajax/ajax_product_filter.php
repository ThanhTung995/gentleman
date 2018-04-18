<?php
add_action('wp_ajax_ajax_product_filter', 'product_filter_features');
add_action('wp_ajax_nopriv_ajax_product_filter', 'product_filter_features');
function product_filter_features(){
    $data = $_POST['data'];
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 8,
        'order' => 'DESC',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
            ),
        ),
        'product_cat' => $data,
    );
    $query_filter = new WP_Query($args);
    if($query_filter->have_posts()) : while($query_filter->have_posts()) : $query_filter->the_post();
        ?>
        <div class="item">
            <div class="img"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('small'); ?></a></div>
            <div class="text">
                <a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
                <span><p><?php echo get_post_meta( get_the_ID(), '_sale_price', true); ?></p></span>
                <span><?php echo get_post_meta( get_the_ID(), '_regular_price', true); ?></span>
            </div>
        </div>
    <?php
    endwhile;
    endif;
    wp_reset_query();
    die();
}
?>