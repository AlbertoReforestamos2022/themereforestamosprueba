=== Reforestamos Core ===
Contributors: reforestamosmexico
Tags: custom-post-types, rest-api, reforestamos
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Core functionality for Reforestamos México - Custom Post Types, Taxonomies, Custom Fields, and REST API.

== Description ==

Reforestamos Core is the foundational plugin for the Reforestamos México WordPress site. It provides essential functionality including:

* Custom Post Types (Empresas, Eventos, Integrantes, Boletín)
* Custom Taxonomies for organizing content
* Custom Fields using CMB2 for structured data entry
* REST API endpoints for all custom post types
* Admin interface enhancements

This plugin is designed to work with the Reforestamos Block Theme and can be used independently or alongside other Reforestamos plugins.

== Installation ==

1. Upload the `reforestamos-core` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. The custom post types will be immediately available in the WordPress admin

== Frequently Asked Questions ==

= What custom post types does this plugin register? =

The plugin registers four custom post types:
* Empresas (Companies) - For managing partner companies
* Eventos (Events) - For managing reforestation events
* Integrantes (Team Members) - For managing team member profiles
* Boletín (Newsletter) - For managing newsletter content

= Can I use this plugin without the Reforestamos Block Theme? =

Yes, the plugin is designed to be theme-independent and can work with any WordPress theme.

= Does this plugin require other Reforestamos plugins? =

No, this is a standalone plugin. However, the Reforestamos Empresas plugin requires this Core plugin to be active.

= How do I access the REST API endpoints? =

Once activated, the plugin exposes REST API endpoints at:
* /wp-json/wp/v2/empresas
* /wp-json/wp/v2/eventos
* /wp-json/wp/v2/integrantes
* /wp-json/wp/v2/boletin

== Changelog ==

= 1.0.0 =
* Initial release
* Custom Post Types registration
* Custom Taxonomies
* Custom Fields with CMB2
* REST API endpoints
* Admin UI enhancements

== Upgrade Notice ==

= 1.0.0 =
Initial release of Reforestamos Core plugin.

== Developer Notes ==

This plugin follows WordPress coding standards and uses:
* Singleton pattern for the main class
* Namespaced functions and classes to avoid conflicts
* WordPress security best practices (nonce verification, data sanitization)
* Proper text domain for internationalization

For more information, visit: https://reforestamos.org
