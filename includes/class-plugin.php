<?php
/**
 * Main plugin class.
 *
 * @package WP_Dark_Mode
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class WPDM_Plugin
 *
 * Orchestrates the plugin. Singleton entry point.
 */
class WPDM_Plugin {

	/**
	 * Singleton instance.
	 *
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return self
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor — register hooks.
	 */
	private function __construct() {
		WPDM_Loader::init();
	}

	/**
	 * Plugin activation: set default options if not already present.
	 */
	public static function activate() {
		if ( false === get_option( WPDM_OPTION_KEY ) ) {
			add_option( WPDM_OPTION_KEY, WPDM_Settings::get_defaults() );
		}
	}
}
