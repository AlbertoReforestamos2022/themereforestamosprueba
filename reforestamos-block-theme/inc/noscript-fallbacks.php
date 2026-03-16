<?php
/**
 * Noscript Fallbacks and Graceful Degradation
 *
 * Provides fallback content when JavaScript is disabled and
 * ensures blocks degrade gracefully in non-Gutenberg contexts
 * (email, RSS, REST API responses).
 *
 * @package Reforestamos
 * @since 1.0.0
 *
 * Requirements: 24.4, 24.5, 24.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Reforestamos_Noscript_Fallbacks
 *
 * Handles noscript fallbacks and graceful degradation for blocks.
 */
class Reforestamos_Noscript_Fallbacks {

	/**
	 * Initialize noscript fallback hooks.
	 */
	public static function init() {
		// Add noscript styles and messages.
		add_action( 'wp_head', array( __CLASS__, 'add_noscript_styles' ) );
		add_action( 'wp_body_open', array( __CLASS__, 'add_noscript_banner' ) );

		// Block render fallbacks for non-Gutenberg contexts.
		add_filter( 'render_block', array( __CLASS__, 'add_block_fallbacks' ), 10, 2 );

		// RSS feed fallbacks.
		add_filter( 'the_content_feed', array( __CLASS__, 'simplify_blocks_for_feed' ) );
		add_filter( 'the_excerpt_rss', array( __CLASS__, 'simplify_blocks_for_feed' ) );

		// Email-safe content filter.
		add_filter( 'reforestamos_email_content', array( __CLASS__, 'make_email_safe' ) );
	}

	/**
	 * Add CSS that provides fallback styling when JS is disabled.
	 */
	public static function add_noscript_styles() {
		?>
		<noscript>
		<style>
			/* Carousel fallback: show all slides stacked */
			.wp-block-reforestamos-carousel .carousel-item {
				display: block !important;
				opacity: 1 !important;
				position: relative !important;
				margin-bottom: 1rem;
			}
			.wp-block-reforestamos-carousel .carousel-control-prev,
			.wp-block-reforestamos-carousel .carousel-control-next,
			.wp-block-reforestamos-carousel .carousel-indicators {
				display: none !important;
			}

			/* FAQ accordion fallback: show all answers */
			.wp-block-reforestamos-faqs .accordion-collapse {
				display: block !important;
				height: auto !important;
			}
			.wp-block-reforestamos-faqs .accordion-button::after {
				display: none !important;
			}

			/* Gallery tabs fallback: show all galleries */
			.wp-block-reforestamos-galeria-tabs .tab-pane {
				display: block !important;
				opacity: 1 !important;
				margin-bottom: 2rem;
			}
			.wp-block-reforestamos-galeria-tabs .nav-tabs {
				display: none !important;
			}

			/* Chatbot fallback: hide widget, show contact link */
			.reforestamos-chatbot-widget {
				display: none !important;
			}
			.reforestamos-chatbot-noscript {
				display: block !important;
			}

			/* Contact form: show basic HTML form */
			.reforestamos-contact-form .js-only {
				display: none !important;
			}
			.reforestamos-contact-form .noscript-fallback {
				display: block !important;
			}

			/* Map fallback: show static message */
			.reforestamos-map-container .map-interactive {
				display: none !important;
			}
			.reforestamos-map-container .map-noscript {
				display: block !important;
				padding: 2rem;
				background: #f5f5f5;
				text-align: center;
				border: 1px solid #ddd;
				border-radius: 4px;
			}

			/* Eventos próximos: show static list */
			.wp-block-reforestamos-eventos-proximos .js-dynamic-content {
				display: none !important;
			}
			.wp-block-reforestamos-eventos-proximos .static-content {
				display: block !important;
			}

			/* Noscript banner */
			.reforestamos-noscript-banner {
				display: block !important;
			}

			/* Responsive navigation fallback */
			.reforestamos-nav-mobile-toggle {
				display: none !important;
			}
			.reforestamos-nav-menu {
				display: block !important;
				position: static !important;
			}
			.reforestamos-nav-menu li {
				display: inline-block;
			}

			/* Timeline: ensure all items visible */
			.wp-block-reforestamos-timeline .timeline-item {
				opacity: 1 !important;
				transform: none !important;
			}

			/* Logos aliados: disable any JS animations */
			.wp-block-reforestamos-logos-aliados .logo-item {
				opacity: 1 !important;
				transform: none !important;
			}
		</style>
		</noscript>
		<?php
	}

	/**
	 * Add a noscript banner informing users that JS is required for full functionality.
	 */
	public static function add_noscript_banner() {
		?>
		<noscript>
			<div class="reforestamos-noscript-banner" style="background:#fff3cd;color:#856404;padding:12px 20px;text-align:center;border-bottom:1px solid #ffc107;font-size:14px;" role="alert">
				<?php esc_html_e( 'Para una mejor experiencia, habilita JavaScript en tu navegador. Algunas funciones interactivas no están disponibles sin JavaScript.', 'reforestamos' ); ?>
			</div>
		</noscript>
		<?php
	}

	/**
	 * Add static HTML fallbacks to block output for non-JS contexts.
	 *
	 * @param string $block_content The block content.
	 * @param array  $block         The full block data.
	 * @return string Modified block content with fallbacks.
	 */
	public static function add_block_fallbacks( $block_content, $block ) {
		if ( empty( $block['blockName'] ) ) {
			return $block_content;
		}

		switch ( $block['blockName'] ) {
			case 'reforestamos/carousel':
				$block_content = self::add_carousel_fallback( $block_content, $block );
				break;

			case 'reforestamos/eventos-proximos':
				$block_content = self::add_eventos_fallback( $block_content, $block );
				break;

			case 'reforestamos/contacto':
				$block_content = self::add_contact_fallback( $block_content, $block );
				break;
		}

		return $block_content;
	}

	/**
	 * Add carousel fallback: first image shown as static when JS is off.
	 *
	 * @param string $content Block content.
	 * @param array  $block   Block data.
	 * @return string Modified content.
	 */
	private static function add_carousel_fallback( $content, $block ) {
		$attrs  = $block['attrs'] ?? array();
		$images = $attrs['images'] ?? array();

		if ( empty( $images ) ) {
			return $content;
		}

		$fallback = '<noscript><div class="reforestamos-carousel-noscript">';
		foreach ( $images as $image ) {
			$url = esc_url( $image['url'] ?? '' );
			$alt = esc_attr( $image['alt'] ?? '' );
			if ( $url ) {
				$fallback .= '<img src="' . $url . '" alt="' . $alt . '" style="max-width:100%;height:auto;margin-bottom:1rem;" loading="lazy" />';
			}
		}
		$fallback .= '</div></noscript>';

		return $content . $fallback;
	}

	/**
	 * Add eventos fallback: static list of upcoming events via PHP.
	 *
	 * @param string $content Block content.
	 * @param array  $block   Block data.
	 * @return string Modified content.
	 */
	private static function add_eventos_fallback( $content, $block ) {
		if ( ! post_type_exists( 'eventos' ) ) {
			return $content;
		}

		$attrs = $block['attrs'] ?? array();
		$count = $attrs['count'] ?? 3;

		$events = get_posts(
			array(
				'post_type'      => 'eventos',
				'posts_per_page' => $count,
				'post_status'    => 'publish',
				'meta_key'       => '_evento_fecha',
				'orderby'        => 'meta_value',
				'order'          => 'ASC',
				'meta_query'     => array(
					array(
						'key'     => '_evento_fecha',
						'value'   => current_time( 'Y-m-d' ),
						'compare' => '>=',
						'type'    => 'DATE',
					),
				),
			)
		);

		if ( empty( $events ) ) {
			return $content;
		}

		$fallback = '<noscript><div class="static-content"><ul>';
		foreach ( $events as $event ) {
			$date      = get_post_meta( $event->ID, '_evento_fecha', true );
			$fallback .= '<li>';
			$fallback .= '<a href="' . esc_url( get_permalink( $event->ID ) ) . '">';
			$fallback .= esc_html( $event->post_title );
			$fallback .= '</a>';
			if ( $date ) {
				$fallback .= ' — <time>' . esc_html( $date ) . '</time>';
			}
			$fallback .= '</li>';
		}
		$fallback .= '</ul></div></noscript>';

		return $content . $fallback;
	}

	/**
	 * Add contact form fallback: mailto link when JS is disabled.
	 *
	 * @param string $content Block content.
	 * @param array  $block   Block data.
	 * @return string Modified content.
	 */
	private static function add_contact_fallback( $content, $block ) {
		$admin_email = get_option( 'admin_email' );

		$fallback  = '<noscript><div class="noscript-fallback" style="padding:1rem;background:#f8f9fa;border:1px solid #dee2e6;border-radius:4px;margin-top:1rem;">';
		$fallback .= '<p>' . esc_html__( 'El formulario de contacto requiere JavaScript. Puedes contactarnos directamente:', 'reforestamos' ) . '</p>';
		$fallback .= '<p><a href="mailto:' . esc_attr( $admin_email ) . '">' . esc_html( $admin_email ) . '</a></p>';
		$fallback .= '</div></noscript>';

		return $content . $fallback;
	}

	/**
	 * Simplify block content for RSS feeds.
	 * Strips interactive elements and provides static HTML.
	 *
	 * @param string $content The content.
	 * @return string Simplified content.
	 */
	public static function simplify_blocks_for_feed( $content ) {
		// Remove noscript tags (not needed in feeds).
		$content = preg_replace( '/<noscript>.*?<\/noscript>/s', '', $content );

		// Remove interactive elements.
		$content = preg_replace( '/<button[^>]*>.*?<\/button>/s', '', $content );
		$content = preg_replace( '/<form[^>]*>.*?<\/form>/s', '', $content );

		// Remove script tags.
		$content = preg_replace( '/<script[^>]*>.*?<\/script>/s', '', $content );

		// Remove inline styles that reference CSS variables.
		$content = preg_replace( '/style="[^"]*var\(--[^"]*"/', '', $content );

		return $content;
	}

	/**
	 * Make content safe for email rendering.
	 * Converts blocks to simple HTML tables and inline styles.
	 *
	 * @param string $content The content to make email-safe.
	 * @return string Email-safe content.
	 */
	public static function make_email_safe( $content ) {
		// Remove all script and style tags.
		$content = preg_replace( '/<script[^>]*>.*?<\/script>/s', '', $content );
		$content = preg_replace( '/<style[^>]*>.*?<\/style>/s', '', $content );
		$content = preg_replace( '/<noscript>.*?<\/noscript>/s', '', $content );

		// Convert relative URLs to absolute.
		$site_url = get_site_url();
		$content  = preg_replace(
			'/(?:src|href)="\/(?!\/)/',
			'$0' . $site_url . '/',
			$content
		);

		// Replace CSS classes with inline styles for common elements.
		$content = str_replace(
			'class="btn btn-primary"',
			'style="display:inline-block;padding:10px 20px;background-color:#2E7D32;color:#ffffff;text-decoration:none;border-radius:4px;"',
			$content
		);

		// Remove data attributes.
		$content = preg_replace( '/\s+data-[a-z-]+="[^"]*"/', '', $content );

		return $content;
	}
}
