<?php
/**
 * Admin UI Class
 *
 * Enhances the WordPress admin interface for Custom Post Types.
 * Organizes menu structure, adds custom styles, implements filters,
 * search functionality, and provides better validation and error messages.
 *
 * @package Reforestamos_Core
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin UI Class
 *
 * Handles admin interface enhancements including:
 * - Custom menu organization
 * - Admin styles and scripts
 * - Custom columns in list tables
 * - Taxonomy filters
 * - Enhanced search
 * - Bulk actions
 * - Validation and error messages
 */
class Reforestamos_Core_Admin_UI {

	/**
	 * Single instance of the class
	 *
	 * @var Reforestamos_Core_Admin_UI
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Reforestamos_Core_Admin_UI
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		$this->init_hooks();
	}

	/**
	 * Initialize WordPress hooks
	 */
	private function init_hooks() {
		// Admin menu
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
		
		// Admin assets
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		
		// Custom columns
		add_action( 'manage_empresas_posts_columns', array( $this, 'add_empresas_columns' ) );
		add_action( 'manage_empresas_posts_custom_column', array( $this, 'render_empresas_columns' ), 10, 2 );
		add_action( 'manage_eventos_posts_columns', array( $this, 'add_eventos_columns' ) );
		add_action( 'manage_eventos_posts_custom_column', array( $this, 'render_eventos_columns' ), 10, 2 );
		add_action( 'manage_integrantes_posts_columns', array( $this, 'add_integrantes_columns' ) );
		add_action( 'manage_integrantes_posts_custom_column', array( $this, 'render_integrantes_columns' ), 10, 2 );
		
		// Taxonomy filters
		add_action( 'restrict_manage_posts', array( $this, 'add_taxonomy_filters' ) );
		
		// Enhanced search
		add_filter( 'posts_search', array( $this, 'enhance_search' ), 10, 2 );
		
		// Bulk actions
		add_filter( 'bulk_actions-edit-empresas', array( $this, 'add_bulk_actions' ) );
		add_filter( 'bulk_actions-edit-eventos', array( $this, 'add_bulk_actions' ) );
		add_filter( 'handle_bulk_actions-edit-empresas', array( $this, 'handle_bulk_actions' ), 10, 3 );
		add_filter( 'handle_bulk_actions-edit-eventos', array( $this, 'handle_bulk_actions' ), 10, 3 );
		
		// Validation
		add_action( 'save_post', array( $this, 'validate_post_data' ), 10, 2 );
		
		// Admin notices
		add_action( 'admin_notices', array( $this, 'display_admin_notices' ) );
	}

	/**
	 * Register custom admin menu structure
	 *
	 * Creates a top-level menu "Reforestamos" that groups all CPTs
	 * with submenu items for Dashboard, Empresas, Eventos, Integrantes, Boletines
	 */
	public function register_admin_menu() {
		// Add top-level menu
		add_menu_page(
			__( 'Reforestamos', 'reforestamos-core' ),
			__( 'Reforestamos', 'reforestamos-core' ),
			'edit_posts',
			'reforestamos',
			array( $this, 'render_dashboard' ),
			'dashicons-admin-site',
			30
		);

		// Add Dashboard submenu
		add_submenu_page(
			'reforestamos',
			__( 'Dashboard', 'reforestamos-core' ),
			__( 'Dashboard', 'reforestamos-core' ),
			'edit_posts',
			'reforestamos',
			array( $this, 'render_dashboard' )
		);
		
		// Add Empresas submenu
		add_submenu_page(
			'reforestamos',
			__( 'Empresas', 'reforestamos-core' ),
			__( 'Empresas', 'reforestamos-core' ),
			'edit_posts',
			'edit.php?post_type=empresas'
		);
		
		// Add Eventos submenu
		add_submenu_page(
			'reforestamos',
			__( 'Eventos', 'reforestamos-core' ),
			__( 'Eventos', 'reforestamos-core' ),
			'edit_posts',
			'edit.php?post_type=eventos'
		);
		
		// Add Integrantes submenu
		add_submenu_page(
			'reforestamos',
			__( 'Integrantes', 'reforestamos-core' ),
			__( 'Integrantes', 'reforestamos-core' ),
			'edit_posts',
			'edit.php?post_type=integrantes'
		);
		
		// Add Boletines submenu
		add_submenu_page(
			'reforestamos',
			__( 'Boletines', 'reforestamos-core' ),
			__( 'Boletines', 'reforestamos-core' ),
			'edit_posts',
			'edit.php?post_type=boletin'
		);
	}

	/**
	 * Render dashboard page
	 */
	public function render_dashboard() {
		// Get statistics
		$empresas_count = wp_count_posts( 'empresas' )->publish;
		$eventos_count = wp_count_posts( 'eventos' )->publish;
		$integrantes_count = wp_count_posts( 'integrantes' )->publish;
		$boletin_count = wp_count_posts( 'boletin' )->publish;
		
		// Get upcoming events
		$upcoming_events = $this->get_upcoming_events( 5 );
		?>
		<div class="wrap reforestamos-dashboard">
			<h1><?php esc_html_e( 'Reforestamos Dashboard', 'reforestamos-core' ); ?></h1>

			<div class="reforestamos-stats">
				<div class="stat-card">
					<div class="stat-icon"><span class="dashicons dashicons-building"></span></div>
					<div class="stat-content">
						<h3><?php echo esc_html( $empresas_count ); ?></h3>
						<p><?php esc_html_e( 'Empresas', 'reforestamos-core' ); ?></p>
					</div>
					<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=empresas' ) ); ?>" class="stat-link">
						<?php esc_html_e( 'Ver todas', 'reforestamos-core' ); ?>
					</a>
				</div>
				
				<div class="stat-card">
					<div class="stat-icon"><span class="dashicons dashicons-calendar-alt"></span></div>
					<div class="stat-content">
						<h3><?php echo esc_html( $eventos_count ); ?></h3>
						<p><?php esc_html_e( 'Eventos', 'reforestamos-core' ); ?></p>
					</div>
					<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=eventos' ) ); ?>" class="stat-link">
						<?php esc_html_e( 'Ver todos', 'reforestamos-core' ); ?>
					</a>
				</div>
				
				<div class="stat-card">
					<div class="stat-icon"><span class="dashicons dashicons-groups"></span></div>
					<div class="stat-content">
						<h3><?php echo esc_html( $integrantes_count ); ?></h3>
						<p><?php esc_html_e( 'Integrantes', 'reforestamos-core' ); ?></p>
					</div>
					<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=integrantes' ) ); ?>" class="stat-link">
						<?php esc_html_e( 'Ver todos', 'reforestamos-core' ); ?>
					</a>
				</div>
				
				<div class="stat-card">
					<div class="stat-icon"><span class="dashicons dashicons-email-alt"></span></div>
					<div class="stat-content">
						<h3><?php echo esc_html( $boletin_count ); ?></h3>
						<p><?php esc_html_e( 'Boletines', 'reforestamos-core' ); ?></p>
					</div>
					<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=boletin' ) ); ?>" class="stat-link">
						<?php esc_html_e( 'Ver todos', 'reforestamos-core' ); ?>
					</a>
				</div>
			</div>

			<?php if ( ! empty( $upcoming_events ) ) : ?>
			<div class="reforestamos-upcoming-events">
				<h2><?php esc_html_e( 'Próximos Eventos', 'reforestamos-core' ); ?></h2>
				<table class="wp-list-table widefat fixed striped">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Evento', 'reforestamos-core' ); ?></th>
							<th><?php esc_html_e( 'Fecha', 'reforestamos-core' ); ?></th>
							<th><?php esc_html_e( 'Ubicación', 'reforestamos-core' ); ?></th>
							<th><?php esc_html_e( 'Acciones', 'reforestamos-core' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $upcoming_events as $event ) : ?>
						<tr>
							<td><strong><?php echo esc_html( $event->post_title ); ?></strong></td>
							<td><?php echo esc_html( get_post_meta( $event->ID, '_evento_fecha', true ) ); ?></td>
							<td><?php echo esc_html( get_post_meta( $event->ID, '_evento_ubicacion', true ) ); ?></td>
							<td>
								<a href="<?php echo esc_url( get_edit_post_link( $event->ID ) ); ?>">
									<?php esc_html_e( 'Editar', 'reforestamos-core' ); ?>
								</a>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php endif; ?>
			
			<div class="reforestamos-quick-links">
				<h2><?php esc_html_e( 'Acciones Rápidas', 'reforestamos-core' ); ?></h2>
				<div class="quick-links-grid">
					<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=empresas' ) ); ?>" class="quick-link">
						<span class="dashicons dashicons-plus-alt"></span>
						<?php esc_html_e( 'Nueva Empresa', 'reforestamos-core' ); ?>
					</a>
					<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=eventos' ) ); ?>" class="quick-link">
						<span class="dashicons dashicons-plus-alt"></span>
						<?php esc_html_e( 'Nuevo Evento', 'reforestamos-core' ); ?>
					</a>
					<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=integrantes' ) ); ?>" class="quick-link">
						<span class="dashicons dashicons-plus-alt"></span>
						<?php esc_html_e( 'Nuevo Integrante', 'reforestamos-core' ); ?>
					</a>
					<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=boletin' ) ); ?>" class="quick-link">
						<span class="dashicons dashicons-plus-alt"></span>
						<?php esc_html_e( 'Nuevo Boletín', 'reforestamos-core' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get upcoming events
	 *
	 * @param int $limit Number of events to retrieve.
	 * @return array Array of event posts.
	 */
	private function get_upcoming_events( $limit = 5 ) {
		$args = array(
			'post_type'      => 'eventos',
			'posts_per_page' => $limit,
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
		);
		
		return get_posts( $args );
	}

	/**
	 * Enqueue admin assets
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_admin_assets( $hook ) {
		// Enqueue on all admin pages for Reforestamos CPTs
		$screen = get_current_screen();
		$post_types = array( 'empresas', 'eventos', 'integrantes', 'boletin' );
		
		if ( ! $screen || ( ! in_array( $screen->post_type, $post_types, true ) && 'toplevel_page_reforestamos' !== $hook ) ) {
			return;
		}
		
		// Enqueue admin styles
		wp_enqueue_style(
			'reforestamos-admin-styles',
			REFORESTAMOS_CORE_URL . 'admin/css/admin-styles.css',
			array(),
			REFORESTAMOS_CORE_VERSION
		);
		
		// Enqueue admin validation script
		wp_enqueue_script(
			'reforestamos-admin-validation',
			REFORESTAMOS_CORE_URL . 'admin/js/admin-validation.js',
			array( 'jquery' ),
			REFORESTAMOS_CORE_VERSION,
			true
		);
		
		// Localize script
		wp_localize_script(
			'reforestamos-admin-validation',
			'reforestamosAdmin',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'reforestamos_admin_nonce' ),
				'strings' => array(
					'required_field' => __( 'Este campo es obligatorio', 'reforestamos-core' ),
					'invalid_email'  => __( 'Por favor ingrese un email válido', 'reforestamos-core' ),
					'invalid_url'    => __( 'Por favor ingrese una URL válida', 'reforestamos-core' ),
					'invalid_date'   => __( 'Por favor ingrese una fecha válida', 'reforestamos-core' ),
				),
			)
		);
	}

	/**
	 * Add custom columns for Empresas
	 *
	 * @param array $columns Existing columns.
	 * @return array Modified columns.
	 */
	public function add_empresas_columns( $columns ) {
		$new_columns = array();
		
		foreach ( $columns as $key => $value ) {
			$new_columns[ $key ] = $value;
			
			if ( 'title' === $key ) {
				$new_columns['logo']      = __( 'Logo', 'reforestamos-core' );
				$new_columns['categoria'] = __( 'Categoría', 'reforestamos-core' );
				$new_columns['url']       = __( 'Sitio Web', 'reforestamos-core' );
			}
		}
		
		return $new_columns;
	}

	/**
	 * Render custom columns for Empresas
	 *
	 * @param string $column  Column name.
	 * @param int    $post_id Post ID.
	 */
	public function render_empresas_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'logo':
				$logo = get_post_meta( $post_id, '_empresa_logo', true );
				if ( $logo ) {
					echo '<img src="' . esc_url( $logo ) . '" alt="" style="max-width: 50px; height: auto;">';
				} else {
					echo '—';
				}
				break;
				
			case 'categoria':
				$terms = get_the_terms( $post_id, 'categoria_empresa' );
				if ( $terms && ! is_wp_error( $terms ) ) {
					$term_names = wp_list_pluck( $terms, 'name' );
					echo esc_html( implode( ', ', $term_names ) );
				} else {
					echo '—';
				}
				break;
				
			case 'url':
				$url = get_post_meta( $post_id, '_empresa_url', true );
				if ( $url ) {
					echo '<a href="' . esc_url( $url ) . '" target="_blank">' . esc_html( $url ) . '</a>';
				} else {
					echo '—';
				}
				break;
		}
	}

	/**
	 * Add custom columns for Eventos
	 *
	 * @param array $columns Existing columns.
	 * @return array Modified columns.
	 */
	public function add_eventos_columns( $columns ) {
		$new_columns = array();
		
		foreach ( $columns as $key => $value ) {
			$new_columns[ $key ] = $value;
			
			if ( 'title' === $key ) {
				$new_columns['fecha']     = __( 'Fecha', 'reforestamos-core' );
				$new_columns['ubicacion'] = __( 'Ubicación', 'reforestamos-core' );
				$new_columns['capacidad'] = __( 'Capacidad', 'reforestamos-core' );
			}
		}
		
		return $new_columns;
	}

	/**
	 * Render custom columns for Eventos
	 *
	 * @param string $column  Column name.
	 * @param int    $post_id Post ID.
	 */
	public function render_eventos_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'fecha':
				$fecha = get_post_meta( $post_id, '_evento_fecha', true );
				if ( $fecha ) {
					echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $fecha ) ) );
				} else {
					echo '—';
				}
				break;
				
			case 'ubicacion':
				$ubicacion = get_post_meta( $post_id, '_evento_ubicacion', true );
				echo $ubicacion ? esc_html( $ubicacion ) : '—';
				break;
				
			case 'capacidad':
				$capacidad = get_post_meta( $post_id, '_evento_capacidad', true );
				echo $capacidad ? esc_html( $capacidad ) : '—';
				break;
		}
	}

	/**
	 * Add custom columns for Integrantes
	 *
	 * @param array $columns Existing columns.
	 * @return array Modified columns.
	 */
	public function add_integrantes_columns( $columns ) {
		$new_columns = array();
		
		foreach ( $columns as $key => $value ) {
			$new_columns[ $key ] = $value;
			
			if ( 'title' === $key ) {
				$new_columns['foto']  = __( 'Foto', 'reforestamos-core' );
				$new_columns['cargo'] = __( 'Cargo', 'reforestamos-core' );
			}
		}
		
		return $new_columns;
	}

	/**
	 * Render custom columns for Integrantes
	 *
	 * @param string $column  Column name.
	 * @param int    $post_id Post ID.
	 */
	public function render_integrantes_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'foto':
				$foto = get_post_meta( $post_id, '_integrante_foto', true );
				if ( $foto ) {
					echo '<img src="' . esc_url( $foto ) . '" alt="" style="max-width: 50px; height: auto; border-radius: 50%;">';
				} else {
					$thumbnail = get_the_post_thumbnail( $post_id, array( 50, 50 ), array( 'style' => 'border-radius: 50%;' ) );
					echo $thumbnail ? $thumbnail : '—';
				}
				break;
				
			case 'cargo':
				$cargo = get_post_meta( $post_id, '_integrante_cargo', true );
				echo $cargo ? esc_html( $cargo ) : '—';
				break;
		}
	}

	/**
	 * Add taxonomy filters to admin list tables
	 *
	 * @param string $post_type Current post type.
	 */
	public function add_taxonomy_filters( $post_type ) {
		// Only add filters for our CPTs
		if ( ! in_array( $post_type, array( 'empresas', 'eventos', 'integrantes' ), true ) ) {
			return;
		}
		
		// Get taxonomies for this post type
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		
		foreach ( $taxonomies as $taxonomy ) {
			// Skip built-in taxonomies
			if ( in_array( $taxonomy->name, array( 'post_tag', 'post_format' ), true ) ) {
				continue;
			}
			
			$selected = isset( $_GET[ $taxonomy->name ] ) ? sanitize_text_field( wp_unslash( $_GET[ $taxonomy->name ] ) ) : '';
			
			wp_dropdown_categories(
				array(
					'show_option_all' => sprintf( __( 'Todas las %s', 'reforestamos-core' ), $taxonomy->label ),
					'taxonomy'        => $taxonomy->name,
					'name'            => $taxonomy->name,
					'orderby'         => 'name',
					'selected'        => $selected,
					'show_count'      => true,
					'hide_empty'      => true,
					'value_field'     => 'slug',
				)
			);
		}
		
		// Add date range filter for eventos
		if ( 'eventos' === $post_type ) {
			$date_from = isset( $_GET['date_from'] ) ? sanitize_text_field( wp_unslash( $_GET['date_from'] ) ) : '';
			$date_to   = isset( $_GET['date_to'] ) ? sanitize_text_field( wp_unslash( $_GET['date_to'] ) ) : '';
			?>
			<input type="date" name="date_from" value="<?php echo esc_attr( $date_from ); ?>" placeholder="<?php esc_attr_e( 'Desde', 'reforestamos-core' ); ?>">
			<input type="date" name="date_to" value="<?php echo esc_attr( $date_to ); ?>" placeholder="<?php esc_attr_e( 'Hasta', 'reforestamos-core' ); ?>">
			<?php
		}
	}

	/**
	 * Enhance search to include custom fields
	 *
	 * @param string   $search Search SQL.
	 * @param WP_Query $query  Query object.
	 * @return string Modified search SQL.
	 */
	public function enhance_search( $search, $query ) {
		global $wpdb;
		
		// Only modify search for our CPTs in admin
		if ( ! is_admin() || ! $query->is_search() || ! $query->is_main_query() ) {
			return $search;
		}
		
		$post_type = $query->get( 'post_type' );
		if ( ! in_array( $post_type, array( 'empresas', 'eventos', 'integrantes', 'boletin' ), true ) ) {
			return $search;
		}
		
		$search_term = $query->get( 's' );
		if ( empty( $search_term ) ) {
			return $search;
		}
		
		// Add custom field search
		$meta_keys = $this->get_searchable_meta_keys( $post_type );
		if ( empty( $meta_keys ) ) {
			return $search;
		}
		
		$meta_search = array();
		foreach ( $meta_keys as $meta_key ) {
			$meta_search[] = $wpdb->prepare(
				"(pm.meta_key = %s AND pm.meta_value LIKE %s)",
				$meta_key,
				'%' . $wpdb->esc_like( $search_term ) . '%'
			);
		}
		
		if ( ! empty( $meta_search ) ) {
			$search .= " OR {$wpdb->posts}.ID IN (
				SELECT DISTINCT post_id FROM {$wpdb->postmeta} pm
				WHERE " . implode( ' OR ', $meta_search ) . '
			)';
		}
		
		return $search;
	}

	/**
	 * Get searchable meta keys for a post type
	 *
	 * @param string $post_type Post type.
	 * @return array Array of meta keys.
	 */
	private function get_searchable_meta_keys( $post_type ) {
		$meta_keys = array();
		
		switch ( $post_type ) {
			case 'empresas':
				$meta_keys = array( '_empresa_descripcion', '_empresa_url' );
				break;
			case 'eventos':
				$meta_keys = array( '_evento_ubicacion', '_evento_descripcion' );
				break;
			case 'integrantes':
				$meta_keys = array( '_integrante_cargo', '_integrante_biografia' );
				break;
			case 'boletin':
				$meta_keys = array( '_boletin_contenido' );
				break;
		}
		
		return apply_filters( 'reforestamos_searchable_meta_keys', $meta_keys, $post_type );
	}

	/**
	 * Add bulk actions
	 *
	 * @param array $actions Existing bulk actions.
	 * @return array Modified bulk actions.
	 */
	public function add_bulk_actions( $actions ) {
		$actions['mark_featured'] = __( 'Marcar como Destacado', 'reforestamos-core' );
		$actions['unmark_featured'] = __( 'Desmarcar como Destacado', 'reforestamos-core' );
		return $actions;
	}

	/**
	 * Handle bulk actions
	 *
	 * @param string $redirect_to Redirect URL.
	 * @param string $action      Action name.
	 * @param array  $post_ids    Post IDs.
	 * @return string Modified redirect URL.
	 */
	public function handle_bulk_actions( $redirect_to, $action, $post_ids ) {
		if ( 'mark_featured' === $action ) {
			foreach ( $post_ids as $post_id ) {
				update_post_meta( $post_id, '_is_featured', '1' );
			}
			$redirect_to = add_query_arg( 'bulk_featured', count( $post_ids ), $redirect_to );
		}
		
		if ( 'unmark_featured' === $action ) {
			foreach ( $post_ids as $post_id ) {
				delete_post_meta( $post_id, '_is_featured' );
			}
			$redirect_to = add_query_arg( 'bulk_unfeatured', count( $post_ids ), $redirect_to );
		}
		
		return $redirect_to;
	}

	/**
	 * Validate post data before saving
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 */
	public function validate_post_data( $post_id, $post ) {
		// Skip autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		
		// Check user permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		
		// Only validate our CPTs
		if ( ! in_array( $post->post_type, array( 'empresas', 'eventos', 'integrantes', 'boletin' ), true ) ) {
			return;
		}
		
		$errors = array();
		
		// Validate based on post type
		switch ( $post->post_type ) {
			case 'eventos':
				$fecha = get_post_meta( $post_id, '_evento_fecha', true );
				if ( empty( $fecha ) ) {
					$errors[] = __( 'La fecha del evento es obligatoria', 'reforestamos-core' );
				}
				break;
		}
		
		// Store errors in transient if any
		if ( ! empty( $errors ) ) {
			set_transient( 'reforestamos_validation_errors_' . $post_id, $errors, 45 );
		}
	}

	/**
	 * Display admin notices
	 */
	public function display_admin_notices() {
		// Display bulk action notices
		if ( isset( $_GET['bulk_featured'] ) ) {
			$count = intval( $_GET['bulk_featured'] );
			printf(
				'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
				sprintf(
					/* translators: %d: number of items */
					esc_html( _n( '%d elemento marcado como destacado.', '%d elementos marcados como destacados.', $count, 'reforestamos-core' ) ),
					$count
				)
			);
		}
		
		if ( isset( $_GET['bulk_unfeatured'] ) ) {
			$count = intval( $_GET['bulk_unfeatured'] );
			printf(
				'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
				sprintf(
					/* translators: %d: number of items */
					esc_html( _n( '%d elemento desmarcado como destacado.', '%d elementos desmarcados como destacados.', $count, 'reforestamos-core' ) ),
					$count
				)
			);
		}
		
		// Display validation errors
		$screen = get_current_screen();
		if ( $screen && 'post' === $screen->base ) {
			$post_id = isset( $_GET['post'] ) ? intval( $_GET['post'] ) : 0;
			if ( $post_id ) {
				$errors = get_transient( 'reforestamos_validation_errors_' . $post_id );
				if ( $errors ) {
					echo '<div class="notice notice-error is-dismissible">';
					echo '<p><strong>' . esc_html__( 'Por favor corrija los siguientes errores:', 'reforestamos-core' ) . '</strong></p>';
					echo '<ul>';
					foreach ( $errors as $error ) {
						echo '<li>' . esc_html( $error ) . '</li>';
					}
					echo '</ul>';
					echo '</div>';
					delete_transient( 'reforestamos_validation_errors_' . $post_id );
				}
			}
		}
	}
}
