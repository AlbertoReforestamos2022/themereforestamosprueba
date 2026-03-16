<?php
/**
 * Database Optimization
 *
 * Optimizes database queries with caching, custom indexes, and query monitoring.
 *
 * @package Reforestamos
 * @since 1.0.0
 *
 * Requirements: 19.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Reforestamos_DB_Optimization
 *
 * Provides database query optimization, caching, and index management.
 */
class Reforestamos_DB_Optimization {

	/**
	 * Cache group name.
	 *
	 * @var string
	 */
	const CACHE_GROUP = 'reforestamos';

	/**
	 * Default cache expiration in seconds (1 hour).
	 *
	 * @var int
	 */
	const CACHE_EXPIRATION = HOUR_IN_SECONDS;

	/**
	 * Initialize database optimization hooks.
	 */
	public static function init() {
		// Create custom indexes on activation / upgrade.
		add_action( 'after_switch_theme', array( __CLASS__, 'create_indexes' ) );
		add_action( 'admin_init', array( __CLASS__, 'maybe_create_indexes' ) );

		// Optimize common queries.
		add_filter( 'posts_clauses', array( __CLASS__, 'optimize_event_queries' ), 10, 2 );

		// Cache invalidation hooks.
		add_action( 'save_post', array( __CLASS__, 'invalidate_post_cache' ), 10, 2 );
		add_action( 'delete_post', array( __CLASS__, 'invalidate_post_cache' ), 10, 2 );
		add_action( 'set_object_terms', array( __CLASS__, 'invalidate_taxonomy_cache' ), 10, 4 );

		// Transient cleanup.
		add_action( 'reforestamos_cleanup_transients', array( __CLASS__, 'cleanup_expired_transients' ) );
		if ( ! wp_next_scheduled( 'reforestamos_cleanup_transients' ) ) {
			wp_schedule_event( time(), 'daily', 'reforestamos_cleanup_transients' );
		}
	}

	/**
	 * Create custom database indexes for frequently queried tables.
	 */
	public static function create_indexes() {
		global $wpdb;

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.DirectDatabaseQuery.SchemaChange

		// Index on postmeta for event date lookups.
		$index_exists = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(1) FROM information_schema.STATISTICS WHERE table_schema = %s AND table_name = %s AND index_name = %s",
				DB_NAME,
				$wpdb->postmeta,
				'idx_reforestamos_event_date'
			)
		);

		if ( ! $index_exists ) {
			$wpdb->query(
				"ALTER TABLE {$wpdb->postmeta} ADD INDEX idx_reforestamos_event_date (meta_key(40), meta_value(40))"
			);
		}

		// Index on postmeta for company category lookups.
		$index_exists = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(1) FROM information_schema.STATISTICS WHERE table_schema = %s AND table_name = %s AND index_name = %s",
				DB_NAME,
				$wpdb->postmeta,
				'idx_reforestamos_company_meta'
			)
		);

		if ( ! $index_exists ) {
			$wpdb->query(
				"ALTER TABLE {$wpdb->postmeta} ADD INDEX idx_reforestamos_company_meta (post_id, meta_key(40))"
			);
		}

		// Index on posts for CPT archive queries.
		$index_exists = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(1) FROM information_schema.STATISTICS WHERE table_schema = %s AND table_name = %s AND index_name = %s",
				DB_NAME,
				$wpdb->posts,
				'idx_reforestamos_cpt_archive'
			)
		);

		if ( ! $index_exists ) {
			$wpdb->query(
				"ALTER TABLE {$wpdb->posts} ADD INDEX idx_reforestamos_cpt_archive (post_type, post_status, post_date)"
			);
		}

		// Indexes for plugin custom tables (if they exist).
		self::create_plugin_table_indexes();

		// phpcs:enable

		update_option( 'reforestamos_db_indexes_version', '1.0.0' );
	}

	/**
	 * Create indexes on plugin custom tables.
	 */
	private static function create_plugin_table_indexes() {
		global $wpdb;

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.DirectDatabaseQuery.SchemaChange

		// Company clicks table index.
		$clicks_table = $wpdb->prefix . 'reforestamos_company_clicks';
		$table_exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $clicks_table ) );

		if ( $table_exists ) {
			$index_exists = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(1) FROM information_schema.STATISTICS WHERE table_schema = %s AND table_name = %s AND index_name = %s",
					DB_NAME,
					$clicks_table,
					'idx_clicks_company_date'
				)
			);

			if ( ! $index_exists ) {
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$wpdb->query( "ALTER TABLE {$clicks_table} ADD INDEX idx_clicks_company_date (company_id, clicked_at)" );
			}
		}

		// Newsletter subscribers table index.
		$subscribers_table = $wpdb->prefix . 'reforestamos_newsletter_subscribers';
		$table_exists      = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $subscribers_table ) );

		if ( $table_exists ) {
			$index_exists = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(1) FROM information_schema.STATISTICS WHERE table_schema = %s AND table_name = %s AND index_name = %s",
					DB_NAME,
					$subscribers_table,
					'idx_subscriber_status'
				)
			);

			if ( ! $index_exists ) {
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$wpdb->query( "ALTER TABLE {$subscribers_table} ADD INDEX idx_subscriber_status (status, created_at)" );
			}
		}

		// phpcs:enable
	}

	/**
	 * Only create indexes if they haven't been created for this version.
	 */
	public static function maybe_create_indexes() {
		$current_version = get_option( 'reforestamos_db_indexes_version', '0' );
		if ( version_compare( $current_version, '1.0.0', '<' ) ) {
			self::create_indexes();
		}
	}

	/**
	 * Optimize event queries by adding meta_key ordering directly in SQL.
	 *
	 * @param array    $clauses SQL clauses.
	 * @param WP_Query $query   The WP_Query instance.
	 * @return array Modified clauses.
	 */
	public static function optimize_event_queries( $clauses, $query ) {
		if ( ! $query->is_main_query() || is_admin() ) {
			return $clauses;
		}

		// Optimize upcoming events archive query.
		if ( $query->get( 'post_type' ) === 'eventos' && $query->is_archive() ) {
			global $wpdb;

			// Use index hint for event date ordering.
			if ( $query->get( 'meta_key' ) === '_evento_fecha' ) {
				$clauses['orderby'] = "{$wpdb->postmeta}.meta_value ASC";
			}
		}

		return $clauses;
	}

	/**
	 * Get cached query results or execute and cache.
	 *
	 * @param string   $cache_key Unique cache key.
	 * @param callable $callback  Function that returns the data to cache.
	 * @param int      $expiration Cache expiration in seconds.
	 * @return mixed Cached or fresh data.
	 */
	public static function get_cached( $cache_key, $callback, $expiration = 0 ) {
		if ( 0 === $expiration ) {
			$expiration = self::CACHE_EXPIRATION;
		}

		$cached = wp_cache_get( $cache_key, self::CACHE_GROUP );

		if ( false !== $cached ) {
			return $cached;
		}

		$data = call_user_func( $callback );

		wp_cache_set( $cache_key, $data, self::CACHE_GROUP, $expiration );

		return $data;
	}

	/**
	 * Get cached transient or execute and store.
	 *
	 * @param string   $key        Transient key.
	 * @param callable $callback   Function that returns the data.
	 * @param int      $expiration Expiration in seconds.
	 * @return mixed Cached or fresh data.
	 */
	public static function get_transient( $key, $callback, $expiration = 0 ) {
		if ( 0 === $expiration ) {
			$expiration = self::CACHE_EXPIRATION;
		}

		$transient_key = 'rfm_' . md5( $key );
		$cached        = get_transient( $transient_key );

		if ( false !== $cached ) {
			return $cached;
		}

		$data = call_user_func( $callback );

		set_transient( $transient_key, $data, $expiration );

		return $data;
	}

	/**
	 * Get upcoming events with caching.
	 *
	 * @param int $count Number of events to retrieve.
	 * @return array Array of event posts.
	 */
	public static function get_upcoming_events( $count = 5 ) {
		return self::get_transient(
			'upcoming_events_' . $count,
			function () use ( $count ) {
				return get_posts(
					array(
						'post_type'      => 'eventos',
						'posts_per_page' => $count,
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
			},
			15 * MINUTE_IN_SECONDS
		);
	}

	/**
	 * Get companies grid data with caching.
	 *
	 * @param string $category Optional category filter.
	 * @return array Array of company posts.
	 */
	public static function get_companies( $category = '' ) {
		$cache_key = 'companies_grid_' . sanitize_key( $category );

		return self::get_transient(
			$cache_key,
			function () use ( $category ) {
				$args = array(
					'post_type'      => 'empresas',
					'posts_per_page' => -1,
					'post_status'    => 'publish',
					'orderby'        => 'title',
					'order'          => 'ASC',
				);

				if ( ! empty( $category ) ) {
					$args['tax_query'] = array(
						array(
							'taxonomy' => 'categoria_empresa',
							'field'    => 'slug',
							'terms'    => $category,
						),
					);
				}

				return get_posts( $args );
			},
			self::CACHE_EXPIRATION
		);
	}

	/**
	 * Invalidate caches when a post is saved or deleted.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 */
	public static function invalidate_post_cache( $post_id, $post = null ) {
		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return;
		}

		$post_type = $post ? $post->post_type : get_post_type( $post_id );

		// Clear type-specific caches.
		switch ( $post_type ) {
			case 'eventos':
				self::delete_transients_by_prefix( 'rfm_' . md5( 'upcoming_events' ) );
				break;
			case 'empresas':
				self::delete_transients_by_prefix( 'rfm_' . md5( 'companies_grid' ) );
				break;
		}

		// Clear object cache group.
		wp_cache_flush_group( self::CACHE_GROUP );
	}

	/**
	 * Invalidate taxonomy-related caches.
	 *
	 * @param int    $object_id  Object ID.
	 * @param array  $terms      Array of term IDs.
	 * @param array  $tt_ids     Array of term taxonomy IDs.
	 * @param string $taxonomy   Taxonomy slug.
	 */
	public static function invalidate_taxonomy_cache( $object_id, $terms, $tt_ids, $taxonomy ) {
		if ( in_array( $taxonomy, array( 'categoria_empresa', 'tipo_evento' ), true ) ) {
			wp_cache_flush_group( self::CACHE_GROUP );
		}
	}

	/**
	 * Delete transients matching a prefix.
	 *
	 * @param string $prefix Transient key prefix.
	 */
	private static function delete_transients_by_prefix( $prefix ) {
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
				'_transient_' . $wpdb->esc_like( $prefix ) . '%',
				'_transient_timeout_' . $wpdb->esc_like( $prefix ) . '%'
			)
		);
	}

	/**
	 * Cleanup expired transients from the database.
	 */
	public static function cleanup_expired_transients() {
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->query(
			$wpdb->prepare(
				"DELETE a, b FROM {$wpdb->options} a
				INNER JOIN {$wpdb->options} b ON b.option_name = REPLACE(a.option_name, '_transient_timeout_', '_transient_')
				WHERE a.option_name LIKE %s AND a.option_value < %d",
				'_transient_timeout_rfm_%',
				time()
			)
		);
	}

	/**
	 * Remove custom indexes on theme deactivation.
	 */
	public static function remove_indexes() {
		global $wpdb;

		$indexes = array(
			$wpdb->postmeta => array( 'idx_reforestamos_event_date', 'idx_reforestamos_company_meta' ),
			$wpdb->posts    => array( 'idx_reforestamos_cpt_archive' ),
		);

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.DirectDatabaseQuery.SchemaChange
		foreach ( $indexes as $table => $index_names ) {
			foreach ( $index_names as $index_name ) {
				$exists = $wpdb->get_var(
					$wpdb->prepare(
						"SELECT COUNT(1) FROM information_schema.STATISTICS WHERE table_schema = %s AND table_name = %s AND index_name = %s",
						DB_NAME,
						$table,
						$index_name
					)
				);

				if ( $exists ) {
					// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
					$wpdb->query( "ALTER TABLE {$table} DROP INDEX {$index_name}" );
				}
			}
		}
		// phpcs:enable

		delete_option( 'reforestamos_db_indexes_version' );
	}
}
