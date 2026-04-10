<?php
/**
 * Helper utilities.
 *
 * @package WP_Dark_Mode
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class WPDM_Helpers
 *
 * Static utility methods used across the plugin.
 */
class WPDM_Helpers {

	/**
	 * Validate a hex color value.
	 *
	 * @param string $color Hex color with or without #.
	 * @return string|false Sanitized hex color or false.
	 */
	public static function sanitize_hex_color( $color ) {
		$color = ltrim( $color, '#' );

		if ( preg_match( '/^[a-fA-F0-9]{3}$|^[a-fA-F0-9]{6}$/', $color ) ) {
			return '#' . $color;
		}

		return false;
	}

	/**
	 * Sanitize a comma-separated list of numeric IDs.
	 *
	 * @param string $input Raw input.
	 * @return string Cleaned comma-separated IDs.
	 */
	public static function sanitize_id_list( $input ) {
		$ids = array_map( 'absint', array_filter( array_map( 'trim', explode( ',', $input ) ) ) );
		$ids = array_filter( $ids );
		return implode( ', ', $ids );
	}

	/**
	 * Sanitize a time string in HH:MM format.
	 *
	 * @param string $time Raw time string.
	 * @return string Sanitized time or empty string.
	 */
	public static function sanitize_time( $time ) {
		$time = sanitize_text_field( $time );

		if ( preg_match( '/^([01]\d|2[0-3]):([0-5]\d)$/', $time ) ) {
			return $time;
		}

		return '';
	}

	/**
	 * Sanitize a CSS string to prevent injection.
	 *
	 * Strips HTML tags, blocks style-tag breakout, and removes
	 * dangerous CSS expressions/imports.
	 *
	 * @param string $css Raw CSS input.
	 * @return string Sanitized CSS.
	 */
	public static function sanitize_css( $css ) {
		// Strip any HTML tags.
		$css = wp_strip_all_tags( $css );

		// Prevent </style> breakout.
		$css = str_replace( '<', '', $css );
		$css = str_replace( '>', '', $css );

		// Normalize CSS unicode escapes that could bypass keyword filters.
		$css = preg_replace( '/\\\\[0-9a-fA-F]{1,6}\s?/', '', $css );

		// Remove dangerous CSS patterns.
		$css = preg_replace( '/expression\s*\(/i', '/* blocked */(', $css );
		$css = preg_replace( '/behavior\s*:/i', '/* blocked */', $css );
		$css = preg_replace( '/@import/i', '/* blocked */', $css );
		$css = preg_replace( '/url\s*\(\s*["\']?\s*javascript\s*:/i', 'url(/* blocked */', $css );
		$css = preg_replace( '/url\s*\(\s*["\']?\s*vbscript\s*:/i', 'url(/* blocked */', $css );
		$css = preg_replace( '/url\s*\(\s*["\']?\s*data\s*:/i', 'url(/* blocked */', $css );
		$css = preg_replace( '/-moz-binding/i', '/* blocked */', $css );
		$css = preg_replace( '/filter\s*:/i', '/* blocked */', $css );

		return $css;
	}

	/**
	 * Check if the current singular page is excluded.
	 *
	 * @param array $settings Plugin settings.
	 * @return bool
	 */
	public static function is_excluded_page( $settings ) {
		if ( ! is_singular() ) {
			return false;
		}

		$excluded = $settings['excluded_ids'] ?? '';

		if ( empty( $excluded ) ) {
			return false;
		}

		$ids = array_map( 'absint', array_filter( array_map( 'trim', explode( ',', $excluded ) ) ) );

		return in_array( get_the_ID(), $ids, true );
	}

	/**
	 * Check if dark mode should load on the current request.
	 *
	 * @return bool
	 */
	public static function should_load() {
		$settings = WPDM_Settings::get_all();

		if ( empty( $settings['enabled'] ) ) {
			return false;
		}

		if ( is_admin() && empty( $settings['admin_dark_mode'] ) ) {
			return false;
		}

		if ( ! empty( $settings['logged_in_only'] ) && ! is_user_logged_in() ) {
			return false;
		}

		if ( self::is_excluded_page( $settings ) ) {
			return false;
		}

		/**
		 * Filter whether dark mode should load.
		 *
		 * @param bool  $should_load Whether to load.
		 * @param array $settings    Current settings.
		 */
		return (bool) apply_filters( 'wpdm_should_load', true, $settings );
	}

	/**
	 * Allowed HTML for the toggle button markup.
	 *
	 * @return array
	 */
	public static function get_toggle_allowed_html() {
		return array(
			'button' => array(
				'type'         => true,
				'class'        => true,
				'id'           => true,
				'aria-label'   => true,
				'aria-pressed' => true,
			),
			'span'   => array(
				'class' => true,
			),
			'svg'    => array(
				'class'            => true,
				'xmlns'            => true,
				'width'            => true,
				'height'           => true,
				'viewbox'          => true,
				'fill'             => true,
				'stroke'           => true,
				'stroke-width'     => true,
				'stroke-linecap'   => true,
				'stroke-linejoin'  => true,
				'aria-hidden'      => true,
			),
			'circle' => array(
				'cx' => true,
				'cy' => true,
				'r'  => true,
			),
			'line'   => array(
				'x1' => true,
				'y1' => true,
				'x2' => true,
				'y2' => true,
			),
			'path'   => array(
				'd' => true,
			),
		);
	}

	/**
	 * Sanitize toggle markup through wp_kses.
	 *
	 * @param string $markup Raw HTML.
	 * @return string Sanitized HTML.
	 */
	public static function kses_toggle( $markup ) {
		return wp_kses( $markup, self::get_toggle_allowed_html() );
	}
}
