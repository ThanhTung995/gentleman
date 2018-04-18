<?php
function product_filter_shortcode() {
    ?>
    <div class="short_code_product_filter">
        <div class="header_filter">
            <div class="content">
                <h2>Sản phẩm nổi bật</h2>
                <ul>
                    <?php
                        $terms = get_terms( array(
                            'taxonomy' => 'product_cat',
                            'hide_empty' => false,
                        ) );
                        foreach ($terms as $item) :
                    ?>
                    <li data-filter="<?php echo $item->slug; ?>"><?php echo $item->name; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="content_filter">
            <?php
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
                        <?php
                        global $product;
                        if( $product->is_on_sale() ) {
                            return $product->get_sale_price();
                        }
                        return $product->get_regular_price();
                        ?>
                    </div>
                </div>
            <?php
            endwhile;
            endif;
            wp_reset_query();
            ?>
        </div>
    </div>
    <?php
}
add_shortcode( 'product_filter_shortcode', 'product_filter_shortcode' );
?>