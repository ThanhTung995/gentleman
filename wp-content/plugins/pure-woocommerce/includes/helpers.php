<?php

/**
 * Print help link.
 * To use under add to cart button.
 */
function pure_buying_guide_link() {

	$link = pure_get_option( 'buying_guide_link', '' );

	if ( !$link ) {
		return;
	}

	printf(
		'<div class="mgt20 buying-guide"><i class="far fa-share"></i> <a href="%s" target="_blank">%s</a></div>',
		$link,
		__( 'How to buy from our store', 'pure-woocommerce' )
	);
}

/**
 * Display the review content.
 */
function woocommerce_review_display_comment_text() {

	echo '<div class="description" itemprop="description">';
	comment_text();
	echo '</div>';
}

/**
 * Display promotion content from customizer.
 */
function pure_woocommerce_promotion() {

	$promo = pure_get_option( 'wc_single_promotion', '' );
	if ( !$promo ) {
		return;
	}
	echo '<div class="promo">';
//	echo '<div class="title bold mgb10"><span class="color-primary-color"><i class="fa fa-gift"></i></span> ' . __( 'Promotion', 'pure-woocommerce' ) . '</div>';
	echo '<div class="promo-content bg-secondary-bg pdtb10 pdlr20">';
	echo $promo;
	echo '</div>';
	echo '</div>';
}

function pure_woocommerce_hotline() {

	echo '<div class="pure-woocommerce-hotline">';
	echo '<span class="txt">Hotline:</span>';
	pure_snippet_hotline( '', true, false );
	echo '</div>';
}

function pure_woocommere_single_under_open() {

	?>
    <div class="pure-woocommerce-single__under">
	<?php
}

function pure_woocommere_single_under_close() {

	?>
    </div><!-- /.pure-woocommerce-single__under -->
	<?php
}

function pure_woocommere_single_sidebar() {

	echo '<div class="pure-woocommer-single-sidebar">';
	/**
	 * Hook: pure_woocommerce_single_sidebar.
	 *
	 * @hooked pure_woocommerce_related_news - 20
	 */
	do_action( 'pure_woocommerce_single_sidebar' );
	echo '</div>';
}