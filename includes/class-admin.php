<?php
/**
 * Admin settings page.
 *
 * @package WP_Dark_Mode
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class WPDM_Admin
 *
 * Registers the admin menu page and settings fields using the WordPress Settings API.
 */
class WPDM_Admin {

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
	 * Add the settings page under Settings menu.
	 */
	public function add_menu_page() {
		add_options_page(
			esc_html__( 'WP Dark Mode', 'wp-dark-mode' ),
			esc_html__( 'WP Dark Mode', 'wp-dark-mode' ),
			'manage_options',
			'wp-dark-mode',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Add a Settings link on the Plugins list page.
	 *
	 * @param array $links Existing action links.
	 * @return array
	 */
	public function action_links( $links ) {
		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			esc_url( admin_url( 'options-general.php?page=wp-dark-mode' ) ),
			esc_html__( 'Settings', 'wp-dark-mode' )
		);
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Register settings, sections, and fields.
	 */
	public function register_settings() {
		register_setting( 'wpdm_settings_group', WPDM_OPTION_KEY, array(
			'type'              => 'array',
			'sanitize_callback' => array( 'WPDM_Settings', 'sanitize' ),
			'default'           => WPDM_Settings::get_defaults(),
		) );

		// --- General Section ---
		add_settings_section( 'wpdm_general', esc_html__( 'General', 'wp-dark-mode' ), '__return_null', 'wp-dark-mode' );

		$this->add_field( 'enabled', esc_html__( 'Enable Plugin', 'wp-dark-mode' ), 'checkbox', 'wpdm_general' );
		$this->add_field( 'default_mode', esc_html__( 'Default Mode', 'wp-dark-mode' ), 'select', 'wpdm_general', array(
			'options' => array(
				'auto'  => esc_html__( 'Auto (System)', 'wp-dark-mode' ),
				'light' => esc_html__( 'Light', 'wp-dark-mode' ),
				'dark'  => esc_html__( 'Dark', 'wp-dark-mode' ),
			),
		) );
		$this->add_field( 'follow_os', esc_html__( 'Follow OS Preference', 'wp-dark-mode' ), 'checkbox', 'wpdm_general' );
		$this->add_field( 'remember_preference', esc_html__( 'Remember User Preference', 'wp-dark-mode' ), 'checkbox', 'wpdm_general' );

		// --- Appearance Section ---
		add_settings_section( 'wpdm_appearance', esc_html__( 'Appearance', 'wp-dark-mode' ), '__return_null', 'wp-dark-mode' );

		$this->add_field( 'floating_toggle', esc_html__( 'Show Floating Toggle', 'wp-dark-mode' ), 'checkbox', 'wpdm_appearance' );
		$this->add_field( 'toggle_position', esc_html__( 'Toggle Position', 'wp-dark-mode' ), 'select', 'wpdm_appearance', array(
			'options' => array(
				'right' => esc_html__( 'Bottom Right', 'wp-dark-mode' ),
				'left'  => esc_html__( 'Bottom Left', 'wp-dark-mode' ),
			),
		) );
		$this->add_field( 'toggle_style', esc_html__( 'Toggle Style', 'wp-dark-mode' ), 'select', 'wpdm_appearance', array(
			'options' => array(
				'pill'    => esc_html__( 'Pill', 'wp-dark-mode' ),
				'simple'  => esc_html__( 'Simple', 'wp-dark-mode' ),
				'minimal' => esc_html__( 'Minimal', 'wp-dark-mode' ),
			),
		) );

		$this->add_field( 'color_background', esc_html__( 'Background Color', 'wp-dark-mode' ), 'color', 'wpdm_appearance' );
		$this->add_field( 'color_surface', esc_html__( 'Surface Color', 'wp-dark-mode' ), 'color', 'wpdm_appearance' );
		$this->add_field( 'color_text', esc_html__( 'Text Color', 'wp-dark-mode' ), 'color', 'wpdm_appearance' );
		$this->add_field( 'color_link', esc_html__( 'Link Color', 'wp-dark-mode' ), 'color', 'wpdm_appearance' );
		$this->add_field( 'color_btn_bg', esc_html__( 'Button Background', 'wp-dark-mode' ), 'color', 'wpdm_appearance' );
		$this->add_field( 'color_btn_text', esc_html__( 'Button Text Color', 'wp-dark-mode' ), 'color', 'wpdm_appearance' );

		// --- Behavior Section ---
		add_settings_section( 'wpdm_behavior', esc_html__( 'Behavior', 'wp-dark-mode' ), '__return_null', 'wp-dark-mode' );

		$this->add_field( 'logged_in_only', esc_html__( 'Logged-in Users Only', 'wp-dark-mode' ), 'checkbox', 'wpdm_behavior' );
		$this->add_field( 'schedule_enabled', esc_html__( 'Enable Schedule', 'wp-dark-mode' ), 'checkbox', 'wpdm_behavior' );
		$this->add_field( 'schedule_start', esc_html__( 'Schedule Start Time', 'wp-dark-mode' ), 'time', 'wpdm_behavior' );
		$this->add_field( 'schedule_end', esc_html__( 'Schedule End Time', 'wp-dark-mode' ), 'time', 'wpdm_behavior' );

		// --- Advanced Section ---
		add_settings_section( 'wpdm_advanced', esc_html__( 'Advanced', 'wp-dark-mode' ), '__return_null', 'wp-dark-mode' );

		$this->add_field( 'excluded_ids', esc_html__( 'Exclude Page/Post IDs', 'wp-dark-mode' ), 'text', 'wpdm_advanced', array(
			'description' => esc_html__( 'Comma-separated list of post or page IDs to exclude.', 'wp-dark-mode' ),
		) );
		$this->add_field( 'admin_dark_mode', esc_html__( 'Enable in Admin Area', 'wp-dark-mode' ), 'checkbox', 'wpdm_advanced' );
		$this->add_field( 'custom_css', esc_html__( 'Custom CSS', 'wp-dark-mode' ), 'textarea', 'wpdm_advanced', array(
			'description' => esc_html__( 'Add custom CSS applied during dark mode. Use [data-wpdm-dark] as the parent selector.', 'wp-dark-mode' ),
		) );
	}

	/**
	 * Helper: register a single settings field.
	 *
	 * @param string $key     Setting key.
	 * @param string $label   Field label.
	 * @param string $type    Field type.
	 * @param string $section Section ID.
	 * @param array  $extra   Extra arguments.
	 */
	private function add_field( $key, $label, $type, $section, $extra = array() ) {
		add_settings_field(
			'wpdm_' . $key,
			$label,
			array( $this, 'render_field' ),
			'wp-dark-mode',
			$section,
			array_merge( array(
				'key'  => $key,
				'type' => $type,
			), $extra )
		);
	}

	/**
	 * Render a settings field.
	 *
	 * @param array $args Field arguments.
	 */
	public function render_field( $args ) {
		$key      = $args['key'];
		$type     = $args['type'];
		$settings = WPDM_Settings::get_all();
		$value    = $settings[ $key ] ?? '';
		$name     = WPDM_OPTION_KEY . '[' . esc_attr( $key ) . ']';

		switch ( $type ) {

			case 'checkbox':
				printf(
					'<label><input type="checkbox" name="%s" value="1" %s /> %s</label>',
					esc_attr( $name ),
					checked( $value, true, false ),
					esc_html__( 'Enable', 'wp-dark-mode' )
				);
				break;

			case 'select':
				printf( '<select name="%s">', esc_attr( $name ) );
				foreach ( $args['options'] as $opt_value => $opt_label ) {
					printf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $opt_value ),
						selected( $value, $opt_value, false ),
						esc_html( $opt_label )
					);
				}
				echo '</select>';
				break;

			case 'color':
				printf(
					'<input type="text" class="wpdm-color-picker" name="%s" value="%s" data-default-color="%s" />',
					esc_attr( $name ),
					esc_attr( $value ),
					esc_attr( WPDM_Settings::get_defaults()[ $key ] ?? '' )
				);
				break;

			case 'time':
				printf(
					'<input type="time" name="%s" value="%s" />',
					esc_attr( $name ),
					esc_attr( $value )
				);
				break;

			case 'textarea':
				printf(
					'<textarea name="%s" rows="6" cols="60" class="large-text code">%s</textarea>',
					esc_attr( $name ),
					esc_textarea( $value )
				);
				break;

			default:
				printf(
					'<input type="text" name="%s" value="%s" class="regular-text" />',
					esc_attr( $name ),
					esc_attr( $value )
				);
				break;
		}

		if ( ! empty( $args['description'] ) ) {
			printf( '<p class="description">%s</p>', esc_html( $args['description'] ) );
		}
	}

	/**
	 * Handle the reset settings action.
	 */
	public function handle_reset() {
		if ( ! isset( $_POST['wpdm_reset_settings'] ) ) {
			return;
		}

		if ( ! check_admin_referer( 'wpdm_reset_nonce', 'wpdm_reset_nonce_field' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		WPDM_Settings::reset();

		add_settings_error( WPDM_OPTION_KEY, 'wpdm_reset', esc_html__( 'Settings have been reset to defaults.', 'wp-dark-mode' ), 'updated' );
	}

	/**
	 * Render the settings page.
	 */
	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap wpdm-settings-wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<?php settings_errors( WPDM_OPTION_KEY ); ?>

			<form method="post" action="options.php">
				<?php
				settings_fields( 'wpdm_settings_group' );
				do_settings_sections( 'wp-dark-mode' );
				submit_button( esc_html__( 'Save Settings', 'wp-dark-mode' ) );
				?>
			</form>

			<form method="post" action="" class="wpdm-reset-form">
				<?php wp_nonce_field( 'wpdm_reset_nonce', 'wpdm_reset_nonce_field' ); ?>
				<p>
					<input
						type="submit"
						name="wpdm_reset_settings"
						class="button button-secondary"
						value="<?php esc_attr_e( 'Reset to Defaults', 'wp-dark-mode' ); ?>"
						onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to reset all settings to their defaults?', 'wp-dark-mode' ); ?>');"
					/>
				</p>
			</form>
		</div>
		<?php
	}
}
