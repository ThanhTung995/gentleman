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

add_action( 'wp_footer', 'pure_load_google_fonts', 11 );
/**
 * Load Google Font Asynchronous. Allow controlling from child theme.
 */
function pure_load_google_fonts() {

	$google_fonts = apply_filters( 'pure_google_fonts', array() );

	if ( !is_array( $google_fonts ) || empty( $google_fonts ) ) {
		return;
	}

	$font_string = '';

	foreach ( $google_fonts as $font ) :
		if ( isset( $font[ 'family' ] ) && isset( $font[ 'variations' ] ) && isset( $font[ 'subset' ] ) ) {
			$font_string .= '\'' . str_replace( ' ', '+', $font[ 'family' ] ) . ':' . $font[ 'variations' ] . ':' . $font[ 'subset' ] . '\',';
		}
	endforeach;

	if ( empty( $font_string ) ) {
		return;
	}

	?>
    <script>
        WebFontConfig = {
            google: {families: [ <?php echo $font_string; ?> ]}
        };

        (function (d) {
            var wf = d.createElement('script'), s = d.scripts[0];
            wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js';
            wf.async = true;
            s.parentNode.insertBefore(wf, s);
        })(document);
    </script>
	<?php
}

add_action( 'pure_before', 'pure_fb_sdk' );
/**
 * Add Facebook SDK script with filtered App ID.
 */
function pure_fb_sdk() {

	if ( apply_filters( 'pure_disable_fb_sdk', false ) ) {
		return;
	}

	$app_id = pure_get_option( 'fb_app_id', apply_filters( 'pure_fb_app_id', false ) );
	if ( $app_id ) {
		$app_id = 'appId : \'' . $app_id . '\',';
	}

	?>
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                <?php echo $app_id; ?>
                autoLogAppEvents : true,
                xfbml            : true,
                version          : 'v2.12'
            });
        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
	<?php
}