<?php
/**
 * Uninstall WP Dark Mode.
 *
 * Fires when the plugin is deleted through the WordPress admin.
 *
 * @package WP_Dark_Mode
 */

// Abort if not called by WordPress.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Remove plugin data for a single site.
 */
function wpdm_uninstall_site() {
	delete_option( 'wpdm_settings' );
	delete_transient( 'wpdm_cache' );
}

if ( is_multisite() ) {
	$sites = get_sites( array( 'fields' => 'ids', 'number' => 0 ) );
	foreach ( $sites as $site_id ) {
		switch_to_blog( $site_id );
		wpdm_uninstall_site();
		restore_current_blog();
	}
} else {
	wpdm_uninstall_site();
}
