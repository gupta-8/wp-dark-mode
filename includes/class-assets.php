<?php
/**
 * Asset enqueueing.
 *
 * @package WP_Dark_Mode
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class WPDM_Assets
 *
 * Handles script and style registration.
 */
class WPDM_Assets {

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
	 * Enqueue front-end assets.
	 */
	public function enqueue_frontend() {
		if ( ! WPDM_Helpers::should_load() ) {
			return;
		}

		wp_enqueue_style(
			'wpdm-frontend',
			WPDM_PLUGIN_URL . 'assets/css/frontend.css',
			array(),
			WPDM_VERSION
		);

		wp_enqueue_script(
			'wpdm-frontend',
			WPDM_PLUGIN_URL . 'assets/js/frontend.js',
			array(),
			WPDM_VERSION,
			true
		);

		$settings = WPDM_Settings::get_all();

		$config = wp_json_encode( array(
			'defaultMode'      => $settings['default_mode'],
			'rememberPref'     => (bool) $settings['remember_preference'],
			'followOS'         => (bool) $settings['follow_os'],
			'scheduleEnabled'  => (bool) $settings['schedule_enabled'],
			'scheduleStart'    => $settings['schedule_start'],
			'scheduleEnd'      => $settings['schedule_end'],
		) );

		wp_add_inline_script( 'wpdm-frontend', 'var wpdmConfig = ' . $config . ';', 'before' );
	}

	/**
	 * Enqueue admin assets on our settings page only.
	 *
	 * @param string $hook_suffix Current admin page hook suffix.
	 */
	public function enqueue_admin( $hook_suffix ) {
		if ( 'settings_page_wp-dark-mode' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_style(
			'wpdm-admin',
			WPDM_PLUGIN_URL . 'assets/css/admin.css',
			array(),
			WPDM_VERSION
		);

		wp_enqueue_script(
			'wpdm-admin',
			WPDM_PLUGIN_URL . 'assets/js/admin.js',
			array( 'wp-color-picker' ),
			WPDM_VERSION,
			true
		);
	}
}
