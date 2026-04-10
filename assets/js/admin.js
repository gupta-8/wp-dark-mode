/**
 * WP Dark Mode — Admin JavaScript
 *
 * Initializes WordPress color pickers on settings page.
 */
( function( $ ) {
	'use strict';

	$( document ).ready( function() {
		$( '.wpdm-color-picker' ).wpColorPicker();
	} );
} )( jQuery );
