<?php
/**
 * Shortcodes.
 *
 * @package WP_Dark_Mode
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class WPDM_Shortcodes
 *
 * Registers plugin shortcodes.
 */
class WPDM_Shortcodes {

	/**
	 * Register shortcodes.
	 */
	public static function init() {
		add_shortcode( 'wp_dark_mode_toggle', array( __CLASS__, 'toggle_shortcode' ) );
	}

	/**
	 * Render the toggle via shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string HTML markup.
	 */
	public static function toggle_shortcode( $atts ) {
		if ( ! WPDM_Helpers::should_load() ) {
			return '';
		}

		$markup = WPDM_Frontend::get_instance()->get_toggle_markup( false );

		return WPDM_Helpers::kses_toggle( $markup );
	}
}
