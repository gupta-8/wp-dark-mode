<p align="center">
  <img src="assets/images/icon.svg" alt="WP Dark Mode" width="100" height="100">
</p>

<h1 align="center">WP Dark Mode</h1>

<p align="center">
  A lightweight WordPress plugin that adds dark mode support to any WordPress site.<br>
  Visitors can toggle between light and dark themes with OS preference detection, color customization, scheduling, and more.
</p>

<p align="center">
  <a href="#installation">Installation</a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;
  <a href="#features">Features</a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;
  <a href="#configuration">Configuration</a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;
  <a href="#usage">Usage</a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;
  <a href="#developer-reference">Developer Reference</a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;
  <a href="#faq">FAQ</a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/version-1.0.0-blue" alt="Version 1.0.0">
  <img src="https://img.shields.io/badge/wordpress-5.8%2B-21759b" alt="WordPress 5.8+">
  <img src="https://img.shields.io/badge/php-7.4%2B-777bb4" alt="PHP 7.4+">
  <img src="https://img.shields.io/badge/license-GPL--2.0--or--later-green" alt="License GPL-2.0-or-later">
  <img src="https://img.shields.io/badge/jQuery-not%20required-brightgreen" alt="No jQuery">
</p>

---

## Features

### For Site Owners
- **One-click activation** — enable dark mode site-wide with a single toggle
- **Floating toggle button** — customizable position (left/right) and style (pill, simple, minimal)
- **Customizable dark palette** — control background, surface, text, link, and button colors from the admin
- **Schedule mode** — automatically activate dark mode during specific hours (e.g., 8 PM to 6 AM)
- **Page exclusion** — disable dark mode on specific pages or posts by ID
- **Logged-in users only** — optionally restrict dark mode to authenticated users
- **Custom CSS** — add your own dark mode styles through the admin panel

### For Visitors
- **OS preference detection** — automatically follows the visitor's system dark/light preference
- **Persistent preference** — remembers the visitor's choice across page loads via localStorage
- **Smooth transitions** — animated color transitions with reduced-motion support
- **Keyboard accessible** — full keyboard navigation and screen reader support

### For Developers
- **4 filter hooks** — customize settings, CSS output, toggle markup, and loading behavior
- **Template tag** — `wpdm_render_toggle()` for direct theme integration
- **Shortcode** — `[wp_dark_mode_toggle]` for content placement
- **CSS variables** — all dark mode colors exposed as CSS custom properties
- **Non-invasive** — uses `[data-wpdm-dark]` attribute selector, no aggressive CSS resets

### Performance
- **Lightweight** — vanilla JS (~3 KB), no jQuery dependency on the frontend
- **Conditional loading** — assets only load when dark mode is enabled
- **No external requests** — everything runs locally, zero third-party dependencies

---

## Requirements

| Requirement | Minimum Version |
|---|---|
| WordPress | 5.8+ |
| PHP | 7.4+ |

Compatible with all modern browsers (Chrome, Firefox, Safari, Edge).

---

## Installation

### Method 1: Upload via WordPress Admin

1. Download the latest release `.zip` from [Releases](https://github.com/gupta-8/wp-dark-mode/releases)
2. Go to **Plugins > Add New > Upload Plugin** in your WordPress admin
3. Upload the `.zip` file and click **Install Now**
4. Click **Activate**

### Method 2: Manual Upload

1. Download or clone this repository:
   ```bash
   git clone https://github.com/gupta-8/wp-dark-mode.git
   ```
2. Copy the `wp-dark-mode` folder into `/wp-content/plugins/`
3. Go to **Plugins > Installed Plugins** and activate **WP Dark Mode**

### Method 3: Composer (for developers)

```bash
cd wp-content/plugins
git clone https://github.com/gupta-8/wp-dark-mode.git
```

After activation, go to **Settings > WP Dark Mode** to configure.

---

## Configuration

All settings live under **Settings > WP Dark Mode** in the WordPress admin.

### General Settings

| Setting | Description | Default |
|---|---|---|
| Enable Plugin | Master switch for the plugin | On |
| Default Mode | Starting mode for new visitors: Light, Dark, or Auto (System) | Auto |
| Follow OS Preference | Detect visitor's OS dark/light mode | On |
| Remember User Preference | Save visitor's choice in localStorage | On |

### Appearance Settings

| Setting | Description | Default |
|---|---|---|
| Show Floating Toggle | Display the floating toggle button on the frontend | On |
| Toggle Position | Bottom-left or bottom-right | Bottom Right |
| Toggle Style | Pill, Simple, or Minimal | Pill |
| Background Color | Main page background in dark mode | `#1a1a2e` |
| Surface Color | Cards, sidebar, header, footer backgrounds | `#16213e` |
| Text Color | Primary text color | `#e0e0e0` |
| Link Color | Hyperlink color | `#7ec8e3` |
| Button Background | Button background color | `#0f3460` |
| Button Text Color | Button text/label color | `#ffffff` |

### Behavior Settings

| Setting | Description | Default |
|---|---|---|
| Logged-in Users Only | Only show dark mode to authenticated users | Off |
| Enable Schedule | Auto-activate dark mode during a time window | Off |
| Schedule Start Time | When dark mode turns on (24h format) | 20:00 |
| Schedule End Time | When dark mode turns off (24h format) | 06:00 |

### Advanced Settings

| Setting | Description | Default |
|---|---|---|
| Exclude Page/Post IDs | Comma-separated IDs where dark mode is disabled | Empty |
| Enable in Admin Area | Apply dark mode in the WordPress dashboard | Off |
| Custom CSS | Additional CSS applied during dark mode | Empty |

---

## Usage

### Floating Toggle (Default)

Once activated, a floating toggle button automatically appears on the frontend. Visitors click it to switch between light and dark modes. No code needed.

### Shortcode

Place the toggle anywhere in posts, pages, or widgets:

```
[wp_dark_mode_toggle]
```

### Template Tag

For theme developers — add the toggle directly in your theme templates:

```php
<?php
if ( function_exists( 'wpdm_render_toggle' ) ) {
    wpdm_render_toggle();
}
?>
```

### Custom CSS Targeting

Use the `[data-wpdm-dark]` selector to write custom dark mode styles:

```css
/* In the Custom CSS field or your theme's stylesheet */
[data-wpdm-dark] .my-header {
    background-color: #0d1117;
    border-bottom: 1px solid #30363d;
}

[data-wpdm-dark] .card {
    background-color: #161b22;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
}
```

### CSS Variables Available

When dark mode is active, these CSS custom properties are set on the `<html>` element:

| Variable | Description |
|---|---|
| `--wpdm-bg` | Background color |
| `--wpdm-surface` | Surface/card color |
| `--wpdm-text` | Text color |
| `--wpdm-link` | Link color |
| `--wpdm-btn-bg` | Button background |
| `--wpdm-btn-text` | Button text color |

Use them in your own CSS:

```css
[data-wpdm-dark] .custom-element {
    background-color: var(--wpdm-surface);
    color: var(--wpdm-text);
}
```

---

## Developer Reference

### Filter Hooks

#### `wpdm_default_settings`

Modify the default settings array.

```php
add_filter( 'wpdm_default_settings', function( $defaults ) {
    $defaults['default_mode']     = 'dark';
    $defaults['color_background'] = '#0d1117';
    $defaults['color_surface']    = '#161b22';
    return $defaults;
} );
```

#### `wpdm_dynamic_css`

Filter the CSS output before it's rendered in `<style>` tags.

```php
add_filter( 'wpdm_dynamic_css', function( $css, $settings ) {
    $css .= '[data-wpdm-dark] .site-title { color: #58a6ff !important; }';
    return $css;
}, 10, 2 );
```

#### `wpdm_toggle_markup`

Customize the toggle button HTML.

```php
add_filter( 'wpdm_toggle_markup', function( $markup, $settings, $floating ) {
    // Replace with your own toggle button
    return '<button class="wpdm-toggle my-custom-toggle" aria-label="Toggle dark mode" aria-pressed="false">Dark Mode</button>';
}, 10, 3 );
```

#### `wpdm_should_load`

Control whether dark mode loads on a specific request.

```php
// Disable on WooCommerce checkout
add_filter( 'wpdm_should_load', function( $should_load, $settings ) {
    if ( function_exists( 'is_checkout' ) && is_checkout() ) {
        return false;
    }
    return $should_load;
}, 10, 2 );
```

```php
// Disable for specific user roles
add_filter( 'wpdm_should_load', function( $should_load ) {
    if ( current_user_can( 'administrator' ) ) {
        return false;
    }
    return $should_load;
} );
```

### How Dark Mode Works (Technical)

1. When dark mode activates, the plugin sets `data-wpdm-dark` attribute on `<html>`
2. All dark styles target `[data-wpdm-dark]` — clean, non-invasive, and specific
3. Colors are applied via CSS custom properties for easy theming
4. JavaScript handles toggle state, localStorage persistence, OS detection, and scheduling
5. No inline styles on individual elements — everything flows through CSS variables

---

## File Structure

```
wp-dark-mode/
├── wp-dark-mode.php              # Plugin bootstrap, constants, template tag
├── uninstall.php                 # Clean removal (single site + multisite)
├── readme.txt                    # WordPress.org plugin directory readme
├── README.md                     # This file
│
├── assets/
│   ├── css/
│   │   ├── frontend.css          # Toggle styles, transitions, dark mode base
│   │   └── admin.css             # Settings page layout
│   ├── js/
│   │   ├── frontend.js           # Toggle logic, preference, OS detection, schedule
│   │   └── admin.js              # WordPress color picker initialization
│   └── images/
│       └── icon.svg              # Plugin icon
│
├── includes/
│   ├── class-plugin.php          # Singleton orchestrator, activation hook
│   ├── class-loader.php          # WordPress hook/filter registration
│   ├── class-admin.php           # Settings page, fields, reset handler
│   ├── class-frontend.php        # Toggle rendering, dynamic CSS output
│   ├── class-settings.php        # Defaults, get/save, sanitization
│   ├── class-assets.php          # Conditional script/style enqueueing
│   ├── class-shortcodes.php      # [wp_dark_mode_toggle] shortcode
│   └── class-helpers.php         # Sanitizers, kses, exclusion logic
│
└── languages/
    └── wp-dark-mode.pot          # Translation template
```

---

## Security

This plugin follows WordPress security best practices:

- All user inputs sanitized before saving (`sanitize_text_field`, custom validators)
- All outputs escaped before rendering (`esc_html`, `esc_attr`, `wp_kses`)
- Admin forms protected with nonces and capability checks
- Custom CSS sanitized against injection vectors (blocks `expression()`, `@import`, `javascript:`, `data:`, `vbscript:`, unicode escapes)
- Filter outputs re-sanitized before rendering to prevent third-party injection
- Clean uninstall removes all plugin data (supports multisite)

---

## FAQ

**Does this work with any theme?**

Yes. WP Dark Mode uses CSS variables and the `[data-wpdm-dark]` attribute selector, which works with virtually any theme. Fine-tune colors from the settings page if needed.

**Will it slow down my site?**

No. The plugin loads a single small CSS file and a lightweight vanilla JS file (~3 KB). No jQuery, no external requests, no bloat.

**Can I exclude certain pages?**

Yes. Go to **Settings > WP Dark Mode > Advanced** and enter comma-separated page/post IDs.

**Can I schedule dark mode?**

Yes. Enable the schedule under **Settings > WP Dark Mode > Behavior** and set start/end times. The schedule supports crossing midnight (e.g., 20:00 to 06:00).

**Does it remember the visitor's preference?**

Yes. When "Remember User Preference" is enabled, the visitor's choice is stored in localStorage and persists across page loads and sessions.

**Can I use it with WooCommerce?**

Yes. For pages where dark mode doesn't look right (like checkout), exclude them by ID or use the `wpdm_should_load` filter.

**Is it translation-ready?**

Yes. All user-facing strings use WordPress translation functions. A `.pot` file is included in the `languages/` directory.

**Does it support multisite?**

Yes. Each site in a multisite network gets its own settings. Uninstall cleans up all sites.

---

## Changelog

### 1.0.0

- Initial release
- Floating toggle with pill, simple, and minimal styles
- OS preference detection via `prefers-color-scheme`
- localStorage preference persistence
- Customizable dark mode color palette (6 color controls)
- Schedule mode with start/end times (supports midnight crossing)
- Page/post exclusion by ID
- Logged-in users only option
- Custom CSS field with injection protection
- Shortcode: `[wp_dark_mode_toggle]`
- Template tag: `wpdm_render_toggle()`
- 4 developer filter hooks
- Full accessibility support (ARIA, keyboard, focus, reduced-motion)
- Multisite-aware uninstall
- Translation-ready with `.pot` file

---

## Contributing

Contributions are welcome. Please open an issue first to discuss what you'd like to change.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/my-feature`)
3. Commit your changes (`git commit -m 'Add my feature'`)
4. Push to the branch (`git push origin feature/my-feature`)
5. Open a Pull Request

---

## License

This project is licensed under the **GPL-2.0-or-later** license. See the [GNU General Public License](https://www.gnu.org/licenses/gpl-2.0.html) for details.
