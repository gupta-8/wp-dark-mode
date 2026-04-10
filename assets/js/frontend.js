/**
 * WP Dark Mode — Frontend JavaScript
 *
 * Handles toggle interaction, preference storage, OS detection, and scheduling.
 */
( function() {
	'use strict';

	var STORAGE_KEY = 'wpdm_dark_mode';
	var ATTR        = 'data-wpdm-dark';
	var config      = window.wpdmConfig || {};
	var isDark      = false;

	/**
	 * Get stored preference from localStorage.
	 *
	 * @returns {string|null} "dark", "light", or null.
	 */
	function getStoredPref() {
		try {
			return localStorage.getItem( STORAGE_KEY );
		} catch ( e ) {
			return null;
		}
	}

	/**
	 * Save preference to localStorage.
	 *
	 * @param {string} mode "dark" or "light".
	 */
	function storePref( mode ) {
		if ( ! config.rememberPref ) {
			return;
		}
		try {
			localStorage.setItem( STORAGE_KEY, mode );
		} catch ( e ) {
			// Storage unavailable — silently fail.
		}
	}

	/**
	 * Detect OS-level dark mode preference.
	 *
	 * @returns {boolean} True if OS prefers dark.
	 */
	function osPrefDark() {
		return window.matchMedia && window.matchMedia( '(prefers-color-scheme: dark)' ).matches;
	}

	/**
	 * Check if we're within the configured schedule window.
	 *
	 * @returns {boolean} True if dark mode should be active per schedule.
	 */
	function isWithinSchedule() {
		if ( ! config.scheduleEnabled ) {
			return false;
		}

		var now   = new Date();
		var hours = now.getHours();
		var mins  = now.getMinutes();
		var current = hours * 60 + mins;

		var startParts = ( config.scheduleStart || '20:00' ).split( ':' );
		var endParts   = ( config.scheduleEnd || '06:00' ).split( ':' );
		var start      = parseInt( startParts[0], 10 ) * 60 + parseInt( startParts[1], 10 );
		var end        = parseInt( endParts[0], 10 ) * 60 + parseInt( endParts[1], 10 );

		// Schedule crosses midnight.
		if ( start > end ) {
			return current >= start || current < end;
		}

		return current >= start && current < end;
	}

	/**
	 * Determine the initial mode.
	 *
	 * @returns {boolean} True if dark mode should be active.
	 */
	function shouldStartDark() {
		// 1. Stored user preference takes priority.
		var stored = getStoredPref();
		if ( stored === 'dark' ) return true;
		if ( stored === 'light' ) return false;

		// 2. Schedule override.
		if ( config.scheduleEnabled && isWithinSchedule() ) {
			return true;
		}

		// 3. OS preference.
		if ( config.followOS && osPrefDark() ) {
			return true;
		}

		// 4. Default mode.
		if ( config.defaultMode === 'dark' ) return true;
		if ( config.defaultMode === 'auto' && config.followOS ) return osPrefDark();

		return false;
	}

	/**
	 * Apply or remove dark mode on the document.
	 *
	 * @param {boolean} dark Whether dark mode is active.
	 */
	function applyMode( dark ) {
		if ( dark ) {
			document.documentElement.setAttribute( ATTR, '' );
		} else {
			document.documentElement.removeAttribute( ATTR );
		}

		// Update all toggle buttons.
		var toggles = document.querySelectorAll( '.wpdm-toggle' );
		for ( var i = 0; i < toggles.length; i++ ) {
			toggles[ i ].setAttribute( 'aria-pressed', dark ? 'true' : 'false' );
		}
	}

	/**
	 * Initialize dark mode.
	 */
	function init() {
		isDark = shouldStartDark();
		applyMode( isDark );

		// Bind toggle buttons.
		document.addEventListener( 'click', function( e ) {
			var btn = e.target.closest( '.wpdm-toggle' );
			if ( ! btn ) return;

			isDark = ! isDark;
			applyMode( isDark );
			storePref( isDark ? 'dark' : 'light' );
		} );

		// Listen for OS preference changes.
		if ( config.followOS && window.matchMedia ) {
			var mql = window.matchMedia( '(prefers-color-scheme: dark)' );

			var handler = function( e ) {
				// Only follow OS if user hasn't set a manual preference.
				if ( getStoredPref() ) return;
				isDark = e.matches;
				applyMode( isDark );
			};

			if ( mql.addEventListener ) {
				mql.addEventListener( 'change', handler );
			} else if ( mql.addListener ) {
				mql.addListener( handler );
			}
		}
	}

	// Apply immediately to avoid flash.
	if ( document.readyState === 'loading' ) {
		// DOM not ready, but we can still set the attribute early.
		applyMode( shouldStartDark() );
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
