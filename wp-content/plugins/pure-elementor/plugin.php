<?php

namespace PureElementor;

use PureElementor\Widgets\Info_Box;

if ( !defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Main Plugin Class
 *
 * Register new elementor widget.
 *
 * @since 1.0.0
 */
class Plugin
{

	/**
	 * Constructor
	 *
	 * @since  1.0.0
	 *
	 * @access public
	 */
	public function __construct() {

		$this->add_actions();
	}

	/**
	 * Add Actions
	 *
	 * @since  1.0.0
	 *
	 * @access private
	 */
	private function add_actions() {

		add_action( 'elementor/widgets/widgets_registered', [ $this, 'on_widgets_registered' ] );

		add_action( 'elementor/frontend/after_register_scripts', function () {

			wp_enqueue_script( 'pure-elementor', plugins_url( '/assets/js/pure-elementor.js', ELEMENTOR_PURE_ELEMENTOR__FILE__ ), [ 'jquery' ], false, true );
			wp_enqueue_style( 'pure-elementor', plugins_url( '/assets/css/pure-elementor.css', ELEMENTOR_PURE_ELEMENTOR__FILE__ ), [ 'elementor-frontend' ] );
		} );
	}

	/**
	 * On Widgets Registered
	 *
	 * @since  1.0.0
	 *
	 * @access public
	 */
	public function on_widgets_registered() {

		$this->includes();
		$this->register_widget();
	}

	/**
	 * Includes
	 *
	 * @since  1.0.0
	 *
	 * @access private
	 */
	private function includes() {

		require __DIR__ . '/widgets/info-box.php';
	}

	/**
	 * Register Widget
	 *
	 * @since  1.0.0
	 *
	 * @access private
	 */
	private function register_widget() {

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Info_Box() );
	}
}

new Plugin();
