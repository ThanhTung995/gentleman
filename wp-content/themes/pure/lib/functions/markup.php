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

/**
 * Control tag output conditionally.
 *
 * @param array $args Attibutes for tag.
 *
 * @return null|string
 */
function pure_tag( $args = array() ) {

	$defaults = array(
		'context' => '',
		'open'    => '',
		'close'   => '',
		'content' => '',
		'echo'    => true,
		'params'  => array(),
	);

	$args = wp_parse_args( $args, $defaults );

	/**
	 * Filter to short circuit.
	 *
	 * @param       bool  false Default no pre.
	 * @param array $args Array with tag arguments.
	 *
	 * @see pure_tag $args Array.
	 */
	$pre = apply_filters( "pure_tag_{$args['context']}", false, $args );
	if ( false !== $pre ) {
		if ( !$args[ 'echo' ] ) {
			return $pre;
		}
		echo $pre;

		return null;
	}

	// Add attr to open tag.
	if ( $args[ 'context' ] ) {
		$open = $args[ 'open' ] ? sprintf( $args[ 'open' ], pure_attr( $args[ 'context' ], $args[ 'params' ], $args ) ) : '';

		/**
		 * Filter to modify 'open' markup.
		 *
		 * @param string $open Open HTML tag.
		 * @param array  $args Array with tag arguments.
		 *
		 * @see pure_tag $args Array.
		 */
		$open = apply_filters( "pure_tag_{$args['context']}_open", $open, $args );

		/**
		 * Filter to modify 'close' markup.
		 *
		 * @param string $close Close HTML tag.
		 * @param array  $args  Array with tag arguments.
		 *
		 * @see pure_tag $args Array.
		 */
		$close = apply_filters( "pure_tag_{$args['context']}_close", $args[ 'close' ], $args );
	} else {
		$open  = $args[ 'open' ];
		$close = $args[ 'close' ];
	}

	if ( $open || $args[ 'content' ] ) {
		/**
		 * Non-contextual filter to modify 'open' markup.
		 *
		 * @param string $open Open HTML tag.
		 * @param array  $args Array with tag arguments.
		 *
		 * @see pure_tag $args Array.
		 */
		$open = apply_filters( 'pure_tag_open', $open, $args );
	}
	if ( $close || $args[ 'content' ] ) {
		/**
		 * Non-contextual filter to modify 'close' markup.
		 *
		 * @param string $close Close HTML tag.
		 * @param array  $args  Array with tag arguments.
		 *
		 * @see pure_tag $args Array.
		 */
		$close = apply_filters( 'pure_tag_close', $close, $args );
	}

	if ( $args[ 'echo' ] ) {
		echo $open . $args[ 'content' ] . $close;

		return null;
	} else {
		return $open . $args[ 'content' ] . $close;
	}
}

/**
 * Build attributes.
 *
 * @param string $context
 * @param array  $attributes
 * @param array  $args
 *
 * @return string
 */
function pure_attr( $context, $attributes = array(), $args = array() ) {

	$attributes = pure_parse_attr( $context, $attributes, $args );
	$output     = '';

	foreach ( $attributes as $key => $value ) {
		if ( !$value ) {
			continue;
		}

		if ( true === $value ) {
			$output .= $key . ' ';
		} else {
			$output .= sprintf( '%s="%s" ', esc_html( $key ), esc_attr( $value ) );
		}
	}

	$output = apply_filters( "pure_attr_{$context}_output", $output, $attributes, $context, $args );

	return trim( $output );
}

/**
 * Parse attributes with defaults.
 *
 * @param string $context
 * @param array  $attributes
 * @param array  $args
 *
 * @return array
 */
function pure_parse_attr( $context, $attributes = array(), $args = array() ) {

	$defaults = array(
		'class' => sanitize_html_class( $context ),
	);

	$attributes = wp_parse_args( $attributes, $defaults );

	return apply_filters( "pure_attr_{$context}", $attributes, $context, $args );
}

add_filter( 'pure_attr_head', 'pure_attr_head' );
/**
 * Add attributes for head.
 *
 * @param array $attributes
 *
 * @return array
 */
function pure_attr_head( $attributes ) {

	$attributes[ 'class' ] = '';

	if ( !pure_is_root_page() ) {
		return $attributes;
	}

	$attributes[ 'itemscope' ] = true;
	$attributes[ 'itemtype' ]  = 'https://schema.org/WebSite';

	return $attributes;
}

add_filter( 'pure_attr_body', 'pure_attr_body' );
/**
 * Add attributes for body.
 *
 * @param array $attributes
 *
 * @return array
 */
function pure_attr_body( $attributes ) {

	$attributes[ 'class' ]     = implode( ' ', get_body_class() );
	$attributes[ 'itemscope' ] = true;
	$attributes[ 'itemtype' ]  = 'https://schema.org/WebPage';

	if ( is_search() ) {
		$attributes[ 'itemtype' ] = 'https://schema.org/SearchResultPage';
	}

	return $attributes;
}

add_filter( 'pure_attr_wrap', 'pure_attr_wrap' );
/**
 * Add attributes for wrap.
 *
 * @param array $attributes
 *
 * @return array
 */
function pure_attr_wrap( $attributes ) {

	$attributes[ 'class' ] = 'wrap';

	return $attributes;
}

add_filter( 'pure_attr_site-header', 'pure_attr_site_header' );
/**
 * Add attributes for header.
 *
 * @param array $attributes
 *
 * @return array
 */
function pure_attr_site_header( $attributes ) {

	$attributes[ 'itemscope' ] = true;
	$attributes[ 'itemtype' ]  = 'https://schema.org/WPHeader';

	$attributes[ 'class' ] .= ' desktop';

	return $attributes;
}

add_filter( 'pure_attr_site-navigation', 'pure_attr_site_navigation' );
/**
 * Add attributes for site navigation.
 *f
 *
 * @param array $attributes
 *
 * @return mixed
 */
function pure_attr_site_navigation( $attributes ) {

	$attributes[ 'itemscope' ] = true;
	$attributes[ 'itemtype' ]  = 'https://schema.org/SiteNavigationElement';
	$attributes[ 'class' ]     .= ' desktop';

	return $attributes;
}

add_filter( 'pure_attr_site-footer', 'pure_attr_site_footer' );
/**
 * Add attributes for footer.
 *
 * @param array $attributes
 *
 * @return array
 */
function pure_attr_site_footer( $attributes ) {

	$attributes[ 'itemscope' ] = true;
	$attributes[ 'itemtype' ]  = 'https://schema.org/WPFooter';

	return $attributes;
}

add_filter( 'pure_attr_sidebar-primary', 'pure_attr_sidebar_primary' );
/**
 * Add attributes for site entry.
 *
 * @param array $attributes
 *
 * @return mixed
 */
function pure_attr_sidebar_primary( $attributes ) {

	$attributes[ 'class' ]      = 'sidebar sidebar-primary widget-area';
	$attributes[ 'role' ]       = 'complementary';
	$attributes[ 'aria-label' ] = __( 'Primary Sidebar', 'pure' );
	$attributes[ 'itemscope' ]  = true;
	$attributes[ 'itemtype' ]   = 'https://schema.org/WPSideBar';

	return $attributes;
}

add_filter( 'pure_attr_sidebar-secondary', 'pure_attr_sidebar_secondary' );
/**
 * Add attributes for site entry.
 *
 * @param array $attributes
 *
 * @return mixed
 */
function pure_attr_sidebar_secondary( $attributes ) {

	$attributes[ 'class' ]      = 'sidebar sidebar-secondary widget-area';
	$attributes[ 'role' ]       = 'complementary';
	$attributes[ 'aria-label' ] = __( 'Secondary Sidebar', 'pure' );
	$attributes[ 'itemscope' ]  = true;
	$attributes[ 'itemtype' ]   = 'https://schema.org/WPSideBar';

	return $attributes;
}

add_filter( 'pure_attr_shop-sidebar', 'pure_attr_sidebar_shop' );
/**
 * Add attributes for site entry.
 *
 * @param array $attributes
 *
 * @return mixed
 */
function pure_attr_sidebar_shop( $attributes ) {

	$attributes[ 'class' ]      = 'sidebar shop-sidebar widget-area';
	$attributes[ 'role' ]       = 'complementary';
	$attributes[ 'aria-label' ] = __( 'Shop Sidebar', 'pure' );
	$attributes[ 'id' ]         = 'shop-sidebar';
	$attributes[ 'itemscope' ]  = true;
	$attributes[ 'itemtype' ]   = 'https://schema.org/WPSideBar';

	return $attributes;
}

add_filter( 'pure_attr_entry', 'pure_attr_entry' );
/**
 * Add attributes for site entry.
 *
 * @param array $attributes
 *
 * @return mixed
 */
function pure_attr_entry( $attributes ) {

	$attributes[ 'class' ] .= ' ' . implode( ' ', get_post_class() );

	if ( !is_singular() ) {
		$attributes[ 'class' ] .= ' loop__item';
	} else {
		$attributes[ 'class' ] .= ' single-content';
	}

	$attributes[ 'itemscope' ] = true;
	$attributes[ 'itemtype' ]  = 'https://schema.org/CreativeWork';

	return $attributes;
}

add_filter( 'pure_attr_entry-title', 'pure_attr_entry_title' );
/**
 * Add attributes for entry title.
 *
 * @param array $attributes
 *
 * @return mixed
 */
function pure_attr_entry_title( $attributes ) {

	if ( !is_singular() ) {
		$attributes[ 'class' ] .= ' loop__title';
	}

	$attributes[ 'itemprop' ] = 'headline';

	return $attributes;
}

add_filter( 'pure_attr_entry-time', 'pure_attr_entry_time' );
/**
 * Add attributes for entry time.
 *
 * @param array $attributes
 *
 * @return mixed
 */
function pure_attr_entry_time( $attributes ) {

	$attributes[ 'class' ]    .= ' published';
	$attributes[ 'itemprop' ] = 'datePublished';
	$attributes[ 'datetime' ] = get_the_time( 'c' );

	return $attributes;
}

add_filter( 'pure_attr_entry-modified-time', 'pure_attr_entry_modified_time' );
/**
 * Add attributes for entry modified time.
 *
 * @param array $attributes
 *
 * @return mixed
 */
function pure_attr_entry_modified_time( $attributes ) {

	$attributes[ 'class' ]    .= ' updated';
	$attributes[ 'itemprop' ] = 'dateModified';
	$attributes[ 'datetime' ] = get_the_modified_time( 'c' );

	return $attributes;
}

add_filter( 'pure_attr_entry-author', 'pure_attr_entry_author' );
/**
 * Add attributes for entry author.
 *
 * @param array $attributes
 *
 * @return mixed
 */
function pure_attr_entry_author( $attributes ) {

	$attributes[ 'itemscope' ] = true;
	$attributes[ 'itemprop' ]  = 'author';
	$attributes[ 'itemtype' ]  = 'https://schema.org/Person';

	return $attributes;
}

add_filter( 'pure_attr_entry-author-link', 'pure_attr_entry_author_link' );
/**
 * Add attributes for entry author link.
 *
 * @param array $attributes
 *
 * @return mixed
 */
function pure_attr_entry_author_link( $attributes ) {

	$attributes[ 'itemprop' ] = 'url';
	$attributes[ 'rel' ]      = 'author';

	return $attributes;
}

add_filter( 'pure_attr_entry-author-name', 'pure_attr_entry_author_name' );
/**
 * Add attributes for entry author name.
 *
 * @param array $attributes
 *
 * @return mixed
 */
function pure_attr_entry_author_name( $attributes ) {

	$attributes[ 'itemprop' ] = 'name';

	return $attributes;
}

add_filter( 'pure_attr_entry-content', 'pure_attr_entry_content' );
/**
 * Add attributes for entry content.
 *
 * @param array $attributes
 *
 * @return mixed
 */
function pure_attr_entry_content( $attributes ) {

	if ( !is_singular() ) {
		$attributes[ 'class' ] .= ' loop__content';
	}

	$attributes[ 'itemprop' ] = 'text';

	return $attributes;
}

add_filter( 'pure_attr_loop', 'pure_attr_loop_wrapper' );
/**
 * Add attributes for loop wrapper.
 *
 * @param array $attributes
 *
 * @return mixed
 */
function pure_attr_loop_wrapper( $attributes ) {

	$additional_loop_classes = apply_filters( 'pure_loop_class', 'loop--list' );

	$attributes[ 'class' ] .= ' ' . $additional_loop_classes;

	return $attributes;
}

add_filter( 'pure_attr_entry-thumbnail', 'pure_attr_entry_thumbnail' );
/**
 * Add attributes for loop thumbnail.
 *
 * @param array $attributes
 *
 * @return mixed
 */
function pure_attr_entry_thumbnail( $attributes ) {

	if ( !is_singular() ) {
		$attributes[ 'class' ] .= ' loop__thumbnail';
	}

	return $attributes;
}

add_filter( 'pure_attr_entry-info', 'pure_attr_entry_info' );
/**
 * Add attributes for loop info.
 *
 * @param array $attributes
 *
 * @return mixed
 */
function pure_attr_entry_info( $attributes ) {

	if ( !is_singular() ) {
		$attributes[ 'class' ] .= ' loop__info';
	}

	return $attributes;
}

add_filter( 'pure_attr_title', 'pure_attr_title' );
/**
 * Add attributes for title.
 *
 * @param array $attributes
 *
 * @return mixed
 */
function pure_attr_title( $attributes ) {

	$attributes[ 'itemprop' ] = 'headline';

	return $attributes;
}

add_filter( 'rpwe_markup', 'pure_custom_rpwe_markup' );
/**
 * Change title markup output of Recent Post Extended Widget
 */
function pure_custom_rpwe_markup( $html ) {

	$html = str_replace( '<h3', '<div', $html );
	$html = str_replace( '</h3>', '</div>', $html );

	return $html;
}
