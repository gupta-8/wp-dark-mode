<?php
/**
 * Hook and filter loader.
 *
 * @package WP_Dark_Mode
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class WPDM_Loader
 *
 * Registers all WordPress hooks for the plugin.
 */
class WPDM_Loader {

	/**
	 * Register all hooks.
	 */
	public static function init() {
		// Load text domain.
		add_action( 'init', array( __CLASS__, 'load_textdomain' ) );

		// Admin.
		if ( is_admin() ) {
			add_action( 'admin_menu', array( WPDM_Admin::get_instance(), 'add_menu_page' ) );
			add_action( 'admin_init', array( WPDM_Admin::get_instance(), 'register_settings' ) );
			add_action( 'admin_init', array( WPDM_Admin::get_instance(), 'handle_reset' ) );
			add_filter( 'plugin_action_links_' . WPDM_PLUGIN_BASENAME, array( WPDM_Admin::get_instance(), 'action_links' ) );
		}

		// Assets.
		add_action( 'wp_enqueue_scripts', array( WPDM_Assets::get_instance(), 'enqueue_frontend' ) );
		add_action( 'admin_enqueue_scripts', array( WPDM_Assets::get_instance(), 'enqueue_admin' ) );

		// Frontend.
		add_action( 'wp_footer', array( WPDM_Frontend::get_instance(), 'render_toggle' ) );
		add_action( 'wp_head', array( WPDM_Frontend::get_instance(), 'output_dynamic_css' ), 99 );

		// Shortcodes.
		WPDM_Shortcodes::init();
	}

	/**
	 * Load plugin text domain.
	 */
	public static function load_textdomain() {
		load_plugin_textdomain( 'wp-dark-mode', false, dirname( WPDM_PLUGIN_BASENAME ) . '/languages' );
	}
}
