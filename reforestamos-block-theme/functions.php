<?php
/**
 * Reforestamos Block Theme Functions
 *
 * @package Reforestamos
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define theme constants
define( 'REFORESTAMOS_VERSION', '1.0.0' );
define( 'REFORESTAMOS_THEME_DIR', get_template_directory() );
define( 'REFORESTAMOS_THEME_URI', get_template_directory_uri() );

// Include error handler (loaded first for early error capture)
require_once REFORESTAMOS_THEME_DIR . '/inc/class-error-handler.php';
Reforestamos_Error_Handler::init();

// Register recommended plugin dependencies
$error_handler = Reforestamos_Error_Handler::get_instance();
$error_handler->register_dependency( 'reforestamos-core/reforestamos-core.php', 'Reforestamos Core', 'Reforestamos Block Theme' );
$error_handler->register_dependency( 'reforestamos-micrositios/reforestamos-micrositios.php', 'Reforestamos Micrositios', 'Reforestamos Block Theme' );
$error_handler->register_dependency( 'reforestamos-comunicacion/reforestamos-comunicacion.php', 'Reforestamos Comunicación', 'Reforestamos Block Theme' );
$error_handler->register_dependency( 'reforestamos-empresas/reforestamos-empresas.php', 'Reforestamos Empresas', 'Reforestamos Block Theme' );

// Include required files
require_once REFORESTAMOS_THEME_DIR . '/inc/security.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/theme-setup.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/block-registration.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/block-patterns.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/pattern-manager.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/enqueue-assets.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/performance.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/language-persistence.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/i18n-functions.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/translation-links.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/skip-to-content.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/seo.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/breadcrumbs.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/media-management.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/search.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/events-calendar.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/event-registration.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/events-archive.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/ical-export.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/analytics.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/cookie-consent.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/dashboard-widgets.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/monthly-reports.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/gdpr-compliance.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/health-check.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/integration-checker.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/db-optimization.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/conditional-assets.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/noscript-fallbacks.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/backward-compat.php';

// Include block render callbacks
require_once REFORESTAMOS_THEME_DIR . '/blocks/header-navbar/render.php';

// Initialize analytics and cookie consent
Reforestamos_Analytics::init();
Reforestamos_Cookie_Consent::init();
Reforestamos_Dashboard_Widgets::init();
Reforestamos_Monthly_Reports::init();
Reforestamos_GDPR_Compliance::init();

// Initialize integration checker and optimization modules
Reforestamos_Integration_Checker::init();
Reforestamos_DB_Optimization::init();
Reforestamos_Conditional_Assets::init();
Reforestamos_Noscript_Fallbacks::init();
Reforestamos_Backward_Compat::init();

/**
 * Theme setup
 */
function reforestamos_setup() {
	// Add theme support
	add_theme_support( 'editor-styles' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Register navigation menus
	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'reforestamos' ),
			'footer'  => __( 'Footer Menu', 'reforestamos' ),
		)
	);

	// Load text domain
	load_theme_textdomain( 'reforestamos', REFORESTAMOS_THEME_DIR . '/languages' );
}
add_action( 'after_setup_theme', 'reforestamos_setup' );

/**
 * Create custom block category
 */
function reforestamos_block_categories( $categories ) {
	return array_merge(
		array(
			array(
				'slug'  => 'reforestamos',
				'title' => __( 'Reforestamos', 'reforestamos' ),
			),
		),
		$categories
	);
}
add_filter( 'block_categories_all', 'reforestamos_block_categories', 10, 2 );
