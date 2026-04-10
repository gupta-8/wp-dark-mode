<?php
/**
 * Settings management.
 *
 * @package WP_Dark_Mode
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class WPDM_Settings
 *
 * Manages plugin settings: defaults, get, save, sanitize.
 */
class WPDM_Settings {

	/**
	 * Return default settings.
	 *
	 * @return array
	 */
	public static function get_defaults() {
		$defaults = array(
			'enabled'            => true,
			'default_mode'       => 'auto',
			'floating_toggle'    => true,
			'toggle_position'    => 'right',
			'toggle_style'       => 'pill',
			'remember_preference' => true,
			'follow_os'          => true,
			'logged_in_only'     => false,
			'excluded_ids'       => '',
			'custom_css'         => '',
			'admin_dark_mode'    => false,
			'schedule_enabled'   => false,
			'schedule_start'     => '20:00',
			'schedule_end'       => '06:00',
			'color_background'   => '#1a1a2e',
			'color_surface'      => '#16213e',
			'color_text'         => '#e0e0e0',
			'color_link'         => '#7ec8e3',
			'color_btn_bg'       => '#0f3460',
			'color_btn_text'     => '#ffffff',
		);

		/**
		 * Filter default settings.
		 *
		 * @param array $defaults Default settings array.
		 */
		return apply_filters( 'wpdm_default_settings', $defaults );
	}

	/**
	 * Get all settings merged with defaults.
	 *
	 * @return array
	 */
	public static function get_all() {
		$saved    = get_option( WPDM_OPTION_KEY, array() );
		$defaults = self::get_defaults();

		return wp_parse_args( $saved, $defaults );
	}

	/**
	 * Get a single setting value.
	 *
	 * @param string $key     Setting key.
	 * @param mixed  $default Fallback value.
	 * @return mixed
	 */
	public static function get( $key, $default = null ) {
		$settings = self::get_all();

		if ( isset( $settings[ $key ] ) ) {
			return $settings[ $key ];
		}

		if ( null !== $default ) {
			return $default;
		}

		$defaults = self::get_defaults();
		return $defaults[ $key ] ?? null;
	}

	/**
	 * Sanitize the full settings array.
	 *
	 * @param array $input Raw input from form.
	 * @return array Sanitized settings.
	 */
	public static function sanitize( $input ) {
		$clean    = array();
		$defaults = self::get_defaults();

		// Booleans — unchecked checkboxes are absent from $_POST.
		$booleans = array(
			'enabled',
			'floating_toggle',
			'remember_preference',
			'follow_os',
			'logged_in_only',
			'admin_dark_mode',
			'schedule_enabled',
		);

		foreach ( $booleans as $key ) {
			$clean[ $key ] = ! empty( $input[ $key ] );
		}

		// Select fields.
		$clean['default_mode'] = in_array( $input['default_mode'] ?? '', array( 'light', 'dark', 'auto' ), true )
			? $input['default_mode']
			: $defaults['default_mode'];

		$clean['toggle_position'] = in_array( $input['toggle_position'] ?? '', array( 'left', 'right' ), true )
			? $input['toggle_position']
			: $defaults['toggle_position'];

		$clean['toggle_style'] = in_array( $input['toggle_style'] ?? '', array( 'simple', 'minimal', 'pill' ), true )
			? $input['toggle_style']
			: $defaults['toggle_style'];

		// Colors.
		$color_keys = array( 'color_background', 'color_surface', 'color_text', 'color_link', 'color_btn_bg', 'color_btn_text' );
		foreach ( $color_keys as $key ) {
			$sanitized = WPDM_Helpers::sanitize_hex_color( $input[ $key ] ?? '' );
			$clean[ $key ] = $sanitized ? $sanitized : $defaults[ $key ];
		}

		// Text fields.
		$clean['excluded_ids'] = WPDM_Helpers::sanitize_id_list( $input['excluded_ids'] ?? '' );
		$clean['custom_css']   = WPDM_Helpers::sanitize_css( $input['custom_css'] ?? '' );

		// Schedule times.
		$clean['schedule_start'] = WPDM_Helpers::sanitize_time( $input['schedule_start'] ?? '' );
		$clean['schedule_end']   = WPDM_Helpers::sanitize_time( $input['schedule_end'] ?? '' );

		if ( empty( $clean['schedule_start'] ) ) {
			$clean['schedule_start'] = $defaults['schedule_start'];
		}
		if ( empty( $clean['schedule_end'] ) ) {
			$clean['schedule_end'] = $defaults['schedule_end'];
		}

		return $clean;
	}

	/**
	 * Reset settings to defaults.
	 */
	public static function reset() {
		update_option( WPDM_OPTION_KEY, self::get_defaults() );
	}
}
