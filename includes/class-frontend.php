<?php
/**
 * Frontend output.
 *
 * @package WP_Dark_Mode
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class WPDM_Frontend
 *
 * Renders the toggle button and dynamic CSS on the front end.
 */
class WPDM_Frontend {

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
	 * Render the floating toggle in wp_footer.
	 */
	public function render_toggle() {
		if ( ! WPDM_Helpers::should_load() ) {
			return;
		}

		$settings = WPDM_Settings::get_all();

		if ( empty( $settings['floating_toggle'] ) ) {
			return;
		}

		$markup = $this->get_toggle_markup( true );

		echo WPDM_Helpers::kses_toggle( $markup );
	}

	/**
	 * Render an inline (non-floating) toggle. Used by template tag.
	 */
	public function render_inline_toggle() {
		if ( ! WPDM_Helpers::should_load() ) {
			return;
		}

		$markup = $this->get_toggle_markup( false );

		echo WPDM_Helpers::kses_toggle( $markup );
	}

	/**
	 * Build toggle button markup.
	 *
	 * @param bool $floating Whether this is the floating toggle.
	 * @return string HTML markup.
	 */
	public function get_toggle_markup( $floating = false ) {
		$settings = WPDM_Settings::get_all();
		$position = $settings['toggle_position'];
		$style    = $settings['toggle_style'];

		$classes = array( 'wpdm-toggle' );

		if ( $floating ) {
			$classes[] = 'wpdm-toggle--floating';
			$classes[] = 'wpdm-toggle--' . sanitize_html_class( $position );
		}

		$classes[] = 'wpdm-toggle--' . sanitize_html_class( $style );

		$class_attr = implode( ' ', $classes );

		$sun_svg  = '<svg class="wpdm-icon wpdm-icon--sun" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>';
		$moon_svg = '<svg class="wpdm-icon wpdm-icon--moon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>';

		$markup = sprintf(
			'<button type="button" class="%s" id="wpdm-toggle-btn" aria-label="%s" aria-pressed="false">
				<span class="wpdm-toggle__track">
					<span class="wpdm-toggle__icons">%s%s</span>
					<span class="wpdm-toggle__thumb"></span>
				</span>
				<span class="screen-reader-text">%s</span>
			</button>',
			esc_attr( $class_attr ),
			esc_attr__( 'Toggle dark mode', 'wp-dark-mode' ),
			$sun_svg,
			$moon_svg,
			esc_html__( 'Toggle dark mode', 'wp-dark-mode' )
		);

		/**
		 * Filter the toggle markup.
		 *
		 * @param string $markup   Toggle HTML.
		 * @param array  $settings Current settings.
		 * @param bool   $floating Whether this is the floating toggle.
		 */
		return apply_filters( 'wpdm_toggle_markup', $markup, $settings, $floating );
	}

	/**
	 * Output dynamic dark mode CSS variables in wp_head.
	 */
	public function output_dynamic_css() {
		if ( ! WPDM_Helpers::should_load() ) {
			return;
		}

		$settings = WPDM_Settings::get_all();

		$css_vars = array(
			'--wpdm-bg'       => $settings['color_background'],
			'--wpdm-surface'  => $settings['color_surface'],
			'--wpdm-text'     => $settings['color_text'],
			'--wpdm-link'     => $settings['color_link'],
			'--wpdm-btn-bg'   => $settings['color_btn_bg'],
			'--wpdm-btn-text' => $settings['color_btn_text'],
		);

		$vars = '';
		foreach ( $css_vars as $prop => $val ) {
			$sanitized = WPDM_Helpers::sanitize_hex_color( $val );
			if ( $sanitized ) {
				$vars .= $prop . ':' . $sanitized . ';';
			}
		}

		$css = '[data-wpdm-dark]{' . $vars . '}';

		// Apply dark mode styles using the CSS variables.
		$css .= '
[data-wpdm-dark] {
	background-color: var(--wpdm-bg) !important;
	color: var(--wpdm-text) !important;
}
[data-wpdm-dark] a {
	color: var(--wpdm-link) !important;
}
[data-wpdm-dark] button,
[data-wpdm-dark] .button,
[data-wpdm-dark] input[type="submit"] {
	background-color: var(--wpdm-btn-bg) !important;
	color: var(--wpdm-btn-text) !important;
}
[data-wpdm-dark] .wpdm-toggle {
	color: var(--wpdm-text);
}
[data-wpdm-dark] header,
[data-wpdm-dark] nav,
[data-wpdm-dark] aside,
[data-wpdm-dark] footer,
[data-wpdm-dark] .site-header,
[data-wpdm-dark] .site-footer,
[data-wpdm-dark] .widget,
[data-wpdm-dark] article,
[data-wpdm-dark] .entry-content,
[data-wpdm-dark] .comment-body,
[data-wpdm-dark] .sidebar {
	background-color: var(--wpdm-surface) !important;
	color: var(--wpdm-text) !important;
}
[data-wpdm-dark] img {
	opacity: 0.92;
}';

		// Custom CSS from settings — sanitize to prevent injection.
		$custom_css = WPDM_Helpers::sanitize_css( $settings['custom_css'] ?? '' );

		if ( ! empty( $custom_css ) ) {
			$css .= "\n" . $custom_css;
		}

		/**
		 * Filter the generated dark mode CSS.
		 *
		 * @param string $css      Generated CSS string.
		 * @param array  $settings Current settings.
		 */
		$css = apply_filters( 'wpdm_dynamic_css', $css, $settings );

		// Post-filter safety: prevent </style> breakout from filter returns.
		$css = str_replace( '</style', '/* blocked */', $css );
		$css = str_replace( '</', '/* blocked */', $css );

		printf( '<style id="wpdm-dynamic-css">%s</style>', $css ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS is sanitized above and post-filter.
	}
}
