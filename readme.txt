=== WP Dark Mode ===
Contributors: gupta-8
Tags: dark mode, night mode, dark theme, toggle, accessibility
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Add dark mode support to any WordPress site with a visitor toggle, OS preference detection, customizable colors, and scheduling.

== Description ==

WP Dark Mode is a lightweight WordPress plugin that adds a beautiful dark mode toggle to your website. It automatically detects your visitor's OS preference, remembers their choice, and lets you fully customize the dark color palette — all with zero configuration required.

= Why WP Dark Mode? =

* **Zero configuration** — activate the plugin and dark mode just works
* **Lightweight** — vanilla JS (~3 KB), no jQuery, no external dependencies
* **Non-invasive** — uses CSS variables and data attributes, won't break your theme
* **Accessible** — ARIA labels, keyboard navigation, focus styles, reduced-motion support
* **Developer-friendly** — 4 filter hooks, template tag, shortcode, CSS custom properties

= Features =

**For Site Owners**

* Floating toggle button with 3 styles: pill, simple, minimal
* Toggle position: bottom-left or bottom-right
* 6 customizable dark mode colors: background, surface, text, links, button background, button text
* Schedule mode: auto-activate dark mode during specific hours (e.g., 8 PM to 6 AM)
* Exclude specific pages or posts by ID
* Restrict dark mode to logged-in users only
* Custom CSS field for additional dark mode styles
* Clean admin settings page under Settings > WP Dark Mode

**For Visitors**

* Automatic OS dark mode detection via prefers-color-scheme
* Persistent preference saved in localStorage across page loads
* Smooth color transitions with reduced-motion support
* Full keyboard and screen reader accessibility

**For Developers**

* Shortcode: `[wp_dark_mode_toggle]`
* Template tag: `wpdm_render_toggle()`
* CSS custom properties: `--wpdm-bg`, `--wpdm-surface`, `--wpdm-text`, `--wpdm-link`, `--wpdm-btn-bg`, `--wpdm-btn-text`
* Filter: `wpdm_default_settings` — modify default settings
* Filter: `wpdm_dynamic_css` — customize dark mode CSS output
* Filter: `wpdm_toggle_markup` — replace toggle button HTML
* Filter: `wpdm_should_load` — control loading on specific pages or conditions

= How It Works =

1. When dark mode activates, the plugin sets a `data-wpdm-dark` attribute on the HTML element
2. All dark styles target `[data-wpdm-dark]` using CSS variables
3. JavaScript handles toggle state, localStorage, OS detection, and scheduling
4. No inline styles on individual elements — everything flows through CSS custom properties

== Installation ==

= Upload via WordPress Admin =

1. Download the plugin zip file from [GitHub Releases](https://github.com/gupta-8/wp-dark-mode/releases).
2. Go to Plugins > Add New > Upload Plugin in your WordPress admin.
3. Upload the zip file and click Install Now.
4. Click Activate.

= Manual Upload =

1. Download or clone the repository.
2. Copy the `wp-dark-mode` folder into `/wp-content/plugins/`.
3. Go to Plugins > Installed Plugins and activate WP Dark Mode.

= After Activation =

Go to Settings > WP Dark Mode to configure. The floating toggle appears automatically on the front end with sensible defaults.

== Usage ==

= Floating Toggle =

Enabled by default. A floating button appears in the bottom-right corner of your site. Visitors click it to switch between light and dark modes. No code required.

= Shortcode =

Place the toggle anywhere in posts, pages, or widgets:

`[wp_dark_mode_toggle]`

= Template Tag =

Add the toggle directly in your theme template files:

`<?php if ( function_exists( 'wpdm_render_toggle' ) ) { wpdm_render_toggle(); } ?>`

= Custom CSS Targeting =

Write custom dark mode styles using the data attribute selector:

`[data-wpdm-dark] .my-header { background-color: #0d1117; }`

Or use the CSS variables directly:

`[data-wpdm-dark] .custom-element { background-color: var(--wpdm-surface); color: var(--wpdm-text); }`

= Developer Filter Examples =

Disable dark mode on WooCommerce checkout:

`add_filter( 'wpdm_should_load', function( $load ) { return function_exists( 'is_checkout' ) && is_checkout() ? false : $load; } );`

Change default colors programmatically:

`add_filter( 'wpdm_default_settings', function( $defaults ) { $defaults['color_background'] = '#0d1117'; return $defaults; } );`

== Configuration ==

All settings are under Settings > WP Dark Mode.

= General =

* Enable Plugin — master on/off switch
* Default Mode — Light, Dark, or Auto (follows OS)
* Follow OS Preference — detect visitor's system dark/light mode
* Remember User Preference — save choice in localStorage

= Appearance =

* Show Floating Toggle — display the floating button on frontend
* Toggle Position — bottom-left or bottom-right
* Toggle Style — pill, simple, or minimal
* Background Color — main page background in dark mode
* Surface Color — cards, sidebar, header, footer backgrounds
* Text Color — primary text color
* Link Color — hyperlink color
* Button Background — button background color
* Button Text Color — button label color

= Behavior =

* Logged-in Users Only — restrict dark mode to authenticated users
* Enable Schedule — auto-activate during a time window
* Schedule Start Time — when dark mode turns on (24h format)
* Schedule End Time — when dark mode turns off (24h format)

= Advanced =

* Exclude Page/Post IDs — comma-separated IDs where dark mode is disabled
* Enable in Admin Area — apply dark mode in the WordPress dashboard
* Custom CSS — additional styles applied during dark mode

== Frequently Asked Questions ==

= Does this work with any theme? =

Yes. WP Dark Mode uses CSS variables and the `[data-wpdm-dark]` attribute selector, which works with virtually any WordPress theme. You can fine-tune the dark palette from the settings page to match your theme's design.

= Will it slow down my site? =

No. The plugin loads one small CSS file and a lightweight vanilla JS file (~3 KB). There are no jQuery dependencies on the frontend, no external HTTP requests, and no third-party libraries.

= Can I exclude certain pages from dark mode? =

Yes. Go to Settings > WP Dark Mode > Advanced and enter a comma-separated list of page or post IDs. Dark mode will be completely disabled on those pages.

= Can I schedule dark mode to activate automatically? =

Yes. Enable the schedule under Settings > WP Dark Mode > Behavior, then set start and end times. The schedule supports crossing midnight (e.g., 8:00 PM to 6:00 AM).

= Does it remember the visitor's preference? =

Yes. When "Remember User Preference" is enabled (on by default), the visitor's choice is stored in localStorage and persists across page loads and browser sessions.

= Can I use it with WooCommerce? =

Yes. For pages where dark mode doesn't look right (like checkout), exclude them by ID in the settings or use the `wpdm_should_load` filter to disable programmatically.

= Is it translation-ready? =

Yes. All user-facing strings use WordPress translation functions (`esc_html__`, `__`, etc.). A `.pot` template file is included in the `languages/` directory for translators.

= Does it support WordPress Multisite? =

Yes. Each site in a multisite network gets its own independent settings. When the plugin is deleted, it cleans up data across all sites in the network.

= Can I use my own toggle button design? =

Yes. Use the `wpdm_toggle_markup` filter to replace the default toggle HTML with your own custom button. The JavaScript will still handle the toggle logic as long as your button has the `wpdm-toggle` class.

= What CSS variables are available? =

When dark mode is active, these CSS custom properties are set on the HTML element:

* `--wpdm-bg` — background color
* `--wpdm-surface` — surface/card color
* `--wpdm-text` — text color
* `--wpdm-link` — link color
* `--wpdm-btn-bg` — button background
* `--wpdm-btn-text` — button text color

= How do I add custom dark mode styles? =

Use the Custom CSS field in Settings > WP Dark Mode > Advanced, or add styles to your theme stylesheet using the `[data-wpdm-dark]` selector prefix.

== Screenshots ==

1. Admin settings page — General settings with enable, default mode, and preference options.
2. Admin settings page — Appearance settings with color pickers and toggle style selection.
3. Admin settings page — Behavior settings with schedule configuration.
4. Admin settings page — Advanced settings with exclusion, custom CSS, and reset.
5. Floating toggle button on the front end — pill style, bottom-right position.
6. Dark mode active on a standard WordPress theme.
7. Minimal toggle style — compact circle button.
8. Simple toggle style — rounded rectangle.

== Changelog ==

= 1.0.0 =
* Initial release.
* Floating dark mode toggle with 3 styles: pill, simple, minimal.
* Toggle position: bottom-left or bottom-right.
* OS dark mode detection via prefers-color-scheme.
* Visitor preference persistence via localStorage.
* 6 customizable dark mode colors with admin color pickers.
* Default mode selection: light, dark, or auto (system).
* Schedule mode with configurable start/end times (supports midnight crossing).
* Page and post exclusion by comma-separated IDs.
* Logged-in users only restriction option.
* Custom CSS field with injection protection.
* Shortcode: [wp_dark_mode_toggle].
* Template tag: wpdm_render_toggle().
* 4 developer filter hooks: wpdm_default_settings, wpdm_dynamic_css, wpdm_toggle_markup, wpdm_should_load.
* Full accessibility: ARIA attributes, keyboard navigation, visible focus styles, reduced-motion support.
* Multisite-aware uninstall with per-site cleanup.
* Translation-ready with .pot template file.
* Conditional asset loading — scripts and styles only load when dark mode is enabled.
* CSS variable-based theming for easy customization.
* wp_kses sanitization on all rendered markup.
* CSS injection protection: blocks expression(), @import, javascript:, data:, vbscript:, unicode escapes.
* Nonce and capability verification on all admin actions.
* Clean uninstall removes all plugin data from the database.

== Upgrade Notice ==

= 1.0.0 =
Initial release. Install, activate, and configure under Settings > WP Dark Mode.
