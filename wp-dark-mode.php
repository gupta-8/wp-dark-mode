<?php
/**
 * Plugin Name:       WP Dark Mode
 * Plugin URI:        https://github.com/gupta-8/wp-dark-mode
 * Description:       Add dark mode support to your WordPress site with a visitor toggle, OS preference detection, customizable colors, and scheduling.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            gupta-8
 * Author URI:        https://github.com/gupta-8
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-dark-mode
 * Domain Path:       /languages
 */

defined( 'ABSPATH' ) || exit;

// Plugin constants.
define( 'WPDM_VERSION', '1.0.0' );
define( 'WPDM_PLUGIN_FILE', __FILE__ );
define( 'WPDM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPDM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WPDM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'WPDM_OPTION_KEY', 'wpdm_settings' );

// Autoload classes.
require_once WPDM_PLUGIN_DIR . 'includes/class-helpers.php';
require_once WPDM_PLUGIN_DIR . 'includes/class-settings.php';
require_once WPDM_PLUGIN_DIR . 'includes/class-loader.php';
require_once WPDM_PLUGIN_DIR . 'includes/class-assets.php';
require_once WPDM_PLUGIN_DIR . 'includes/class-admin.php';
require_once WPDM_PLUGIN_DIR . 'includes/class-frontend.php';
require_once WPDM_PLUGIN_DIR . 'includes/class-shortcodes.php';
require_once WPDM_PLUGIN_DIR . 'includes/class-plugin.php';

/**
 * Returns the main plugin instance.
 *
 * @return WPDM_Plugin
 */
function wpdm() {
	return WPDM_Plugin::get_instance();
}

// Boot the plugin.
wpdm();

// Activation hook — must be at file scope per WordPress convention.
register_activation_hook( __FILE__, array( 'WPDM_Plugin', 'activate' ) );

/**
 * Template tag: render the dark mode toggle.
 */
function wpdm_render_toggle() {
	WPDM_Frontend::get_instance()->render_inline_toggle();
}
