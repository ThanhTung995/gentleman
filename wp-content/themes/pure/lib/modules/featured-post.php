<?php

if ( !function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'                   => 'group_5a7d61a80ae59',
	'fields'                => array(
		array(
			'key'           => 'field_5a7d61bc70502',
			'label'         => 'Featured post',
			'name'          => 'featured_post',
			'type'          => 'post_object',
			'required'      => 0,
			'post_type'     => array(
				0 => 'post',
			),
			'allow_null'    => 1,
			'multiple'      => 0,
			'return_format' => 'object',
			'ui'            => 1,
		),
	),
	'location'              => array(
		array(
			array(
				'param'    => 'taxonomy',
				'operator' => '==',
				'value'    => 'category',
			),
		),
	),
	'menu_order'            => 0,
	'position'              => 'normal',
	'style'                 => 'default',
	'label_placement'       => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen'        => '',
	'active'                => 1,
	'description'           => '',
) );

if ( !function_exists( 'pure_featured_post_template' ) ) {
	function pure_featured_post_template( $featured_post ) {

		?>
        <a class="featured-post"
           href="<?php echo get_permalink( $featured_post->ID ); ?>"
           title="<?php echo $featured_post->post_title; ?>"
           style="background-image: url(<?php echo get_the_post_thumbnail_url( $featured_post->ID ); ?>)"
        >
            <div class="inside">
                <div class="info color-primary-bg color-primary-color--hover">
            <span class="date">
	        <?php echo date( 'n/j/Y', strtotime( $featured_post->post_date ) ); ?>
            </span>
                    <span class="views">
                <?php echo pure_get_post_views_output( $featured_post->ID, '<i class="far fa-eye"></i> ', __( ' views', 'pure' ) ); ?>
            </span>
                </div>
                <h3 class="color-primary-bg color-primary-color--hover">
					<?php echo $featured_post->post_title; ?>
                </h3>
            </div>
        </a>
		<?php
	}
}

add_action( 'pure_before_loop', function () {

	if ( !is_category() ) {
		return;
	}

	$term = get_queried_object();

	$featured_post = get_field( 'featured_post', $term );

	if ( !$featured_post ) {
		return;
	}

	pure_featured_post_template( $featured_post );

}, 9 );