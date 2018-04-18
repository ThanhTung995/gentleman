<?php
/**
 * Pure Framework.
 *
 * WARNING: This is part of the Pure Framework. DO NOT EDIT this file under any circumstances.
 * Please do all your modifications in a child theme.
 *
 * @package Pure
 * @author  Boong
 * @link    https://boongstudio.com/themes/pure
 */

do_action( 'pure_doctype' );
do_action( 'pure_title'   );
do_action( 'pure_meta'    );

wp_head();

pure_tag( array(
	'close'   => '</head>',
	'context' => 'head',
) );

pure_tag( array(
	'open'    => '<body %s>',
	'context' => 'body',
) );

do_action( 'pure_before' );

pure_tag( array(
	'open'    => '<div %s>',
	'context' => 'site-container',
	'params'  => array(
		'id' => 'page',
	),
) );

do_action( 'pure_before_header' );
do_action( 'pure_header'        );
do_action( 'pure_after_header'  );

pure_tag( array(
	'open'    => '<div %s>',
	'context' => 'site-inner',
) );

pure_wrap( 'site-inner', 'open', true, false );