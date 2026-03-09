<?php
/**
 * Main Plugin Class
 *
 * @package Reforestamos_Comunicacion
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Reforestamos_Comunicacion class
 *
 * Singleton pattern implementation for the main plugin class.
 * Handles plugin initialization, loading of components, and hooks.
 */
class Reforestamos_Comunicacion {

	/**
	 * Single instance of the class
	 *
	 * @var Reforestamos_Comunicacion
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Reforestamos_Comunicacion
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * Private constructor to prevent direct instantiation.
	 */
	private function __construct() {
		$this->init_hooks();
	}

	/**
	 * Initialize WordPress hooks
	 */
	private function init_hooks() {
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'init', array( $this, 'init_components' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
	}

	/**
	 * Load plugin text domain for translations
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'reforestamos-comunicacion',
			false,
			dirname( REFORESTAMOS_COMM_BASENAME ) . '/languages'
		);
	}

	/**
	 * Initialize plugin components
	 *
	 * Loads and initializes all plugin components like mailer,
	 * contact forms, newsletter, chatbot, and DeepL integration.
	 */
	public function init_components() {
		// Load component files
		$this->load_includes();

		// Initialize Mailer component
		Reforestamos_Mailer::get_instance();
		
		// Initialize Newsletter component
		Reforestamos_Newsletter::get_instance();
		
		// Initialize Contact Form component
		Reforestamos_Contact_Form::get_instance();
		
		// Initialize ChatBot component
		Reforestamos_ChatBot::get_instance();
		
		// Initialize DeepL Integration component
		Reforestamos_DeepL_Integration::get_instance();
		
		// Initialize Tree Adoption component
		Reforestamos_Tree_Adoption::get_instance();
		
		// Initialize Payment Gateway component
		Reforestamos_Payment_Gateway::get_instance();
		
		// Initialize Certificate Generator component
		Reforestamos_Certificate_Generator::get_instance();
	}

	/**
	 * Load required files
	 */
	private function load_includes() {
		// Load Mailer class
		require_once REFORESTAMOS_COMM_PATH . 'includes/class-mailer.php';
		
		// Load Newsletter class
		require_once REFORESTAMOS_COMM_PATH . 'includes/class-newsletter.php';
		
		// Load Contact Form class
		require_once REFORESTAMOS_COMM_PATH . 'includes/class-contact-form.php';
		
		// Load ChatBot class
		require_once REFORESTAMOS_COMM_PATH . 'includes/class-chatbot.php';
		
		// Load DeepL Integration class
		require_once REFORESTAMOS_COMM_PATH . 'includes/class-deepl-integration.php';
		
		// Load Tree Adoption class
		require_once REFORESTAMOS_COMM_PATH . 'includes/class-tree-adoption.php';
		
		// Load Payment Gateway class
		require_once REFORESTAMOS_COMM_PATH . 'includes/class-payment-gateway.php';
		
		// Load Certificate Generator class
		require_once REFORESTAMOS_COMM_PATH . 'includes/class-certificate-generator.php';
	}

	/**
	 * Enqueue frontend assets
	 */
	public function enqueue_frontend_assets() {
		// Enqueue frontend CSS
		wp_enqueue_style(
			'reforestamos-comunicacion',
			REFORESTAMOS_COMM_URL . 'assets/css/frontend.css',
			array(),
			REFORESTAMOS_COMM_VERSION
		);

		// Enqueue chatbot CSS
		wp_enqueue_style(
			'reforestamos-chatbot',
			REFORESTAMOS_COMM_URL . 'assets/css/chatbot.css',
			array(),
			REFORESTAMOS_COMM_VERSION
		);

		// Enqueue tree adoption CSS
		wp_enqueue_style(
			'reforestamos-tree-adoption',
			REFORESTAMOS_COMM_URL . 'assets/css/tree-adoption.css',
			array(),
			REFORESTAMOS_COMM_VERSION
		);

		// Enqueue frontend JavaScript
		wp_enqueue_script(
			'reforestamos-comunicacion',
			REFORESTAMOS_COMM_URL . 'assets/js/frontend.js',
			array( 'jquery' ),
			REFORESTAMOS_COMM_VERSION,
			true
		);

		// Enqueue chatbot JavaScript
		wp_enqueue_script(
			'reforestamos-chatbot',
			REFORESTAMOS_COMM_URL . 'assets/js/chatbot.js',
			array( 'jquery' ),
			REFORESTAMOS_COMM_VERSION,
			true
		);

		// Enqueue tree adoption JavaScript
		wp_enqueue_script(
			'reforestamos-tree-adoption',
			REFORESTAMOS_COMM_URL . 'assets/js/tree-adoption.js',
			array( 'jquery' ),
			REFORESTAMOS_COMM_VERSION,
			true
		);

		// Localize script with AJAX URL and nonce
		wp_localize_script(
			'reforestamos-comunicacion',
			'reforestamosCom',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'reforestamos_comm_nonce' ),
				'strings' => array(
					'sending' => __( 'Enviando...', 'reforestamos-comunicacion' ),
					'success' => __( 'Mensaje enviado correctamente', 'reforestamos-comunicacion' ),
					'error'   => __( 'Error al enviar el mensaje', 'reforestamos-comunicacion' ),
				),
			)
		);

		// Localize chatbot script
		wp_localize_script(
			'reforestamos-chatbot',
			'reforestamosChatbot',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'chatbot_nonce' ),
			)
		);
	}

	/**
	 * Enqueue admin assets
	 */
	public function enqueue_admin_assets() {
		// Enqueue admin CSS
		wp_enqueue_style(
			'reforestamos-comunicacion-admin',
			REFORESTAMOS_COMM_URL . 'admin/css/admin.css',
			array(),
			REFORESTAMOS_COMM_VERSION
		);

		// Enqueue admin JavaScript
		wp_enqueue_script(
			'reforestamos-comunicacion-admin',
			REFORESTAMOS_COMM_URL . 'admin/js/admin.js',
			array( 'jquery' ),
			REFORESTAMOS_COMM_VERSION,
			true
		);
	}

	/**
	 * Register admin menu pages
	 *
	 * Registers the admin menu pages for the Communication plugin.
	 * Adds a top-level menu "Comunicación" with submenu pages for:
	 * - Contact Form Submissions
	 * - Newsletter Subscribers (already registered by Newsletter class)
	 * - ChatBot Configuration
	 */
	public function register_admin_menu() {
		// Add top-level menu page for Communication
		add_menu_page(
			__( 'Comunicación', 'reforestamos-comunicacion' ),
			__( 'Comunicación', 'reforestamos-comunicacion' ),
			'manage_options',
			'reforestamos-comunicacion',
			array( $this, 'render_main_page' ),
			'dashicons-email',
			30
		);

		// Add submenu page for Contact Form Submissions
		add_submenu_page(
			'reforestamos-comunicacion',
			__( 'Formularios de Contacto', 'reforestamos-comunicacion' ),
			__( 'Formularios de Contacto', 'reforestamos-comunicacion' ),
			'manage_options',
			'contact-submissions',
			array( $this, 'render_submissions_page' )
		);

		// Add submenu page for ChatBot Configuration
		add_submenu_page(
			'reforestamos-comunicacion',
			__( 'ChatBot', 'reforestamos-comunicacion' ),
			__( 'ChatBot', 'reforestamos-comunicacion' ),
			'manage_options',
			'reforestamos-chatbot',
			array( 'Reforestamos_ChatBot', 'render_config' )
		);

		// Add submenu page for ChatBot Logs
		add_submenu_page(
			'reforestamos-comunicacion',
			__( 'Logs de ChatBot', 'reforestamos-comunicacion' ),
			__( 'Logs de ChatBot', 'reforestamos-comunicacion' ),
			'manage_options',
			'reforestamos-chatbot-logs',
			array( 'Reforestamos_ChatBot', 'render_logs' )
		);

		// Add submenu page for Tree Adoptions
		add_submenu_page(
			'reforestamos-comunicacion',
			__( 'Adopciones de Árboles', 'reforestamos-comunicacion' ),
			__( 'Adopciones', 'reforestamos-comunicacion' ),
			'manage_options',
			'reforestamos-adoptions',
			array( $this, 'render_adoptions_page' )
		);
	}

	/**
	 * Render main Communication page
	 *
	 * This is the main landing page for the Communication plugin admin area.
	 */
	public function render_main_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Comunicación - Reforestamos México', 'reforestamos-comunicacion' ); ?></h1>
			<p><?php esc_html_e( 'Gestiona las comunicaciones del sitio web.', 'reforestamos-comunicacion' ); ?></p>
			
			<div class="reforestamos-comm-dashboard">
				<h2><?php esc_html_e( 'Accesos Rápidos', 'reforestamos-comunicacion' ); ?></h2>
				<div class="card-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
					<div class="card" style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,0.04);">
						<h3><?php esc_html_e( 'Formularios de Contacto', 'reforestamos-comunicacion' ); ?></h3>
						<p><?php esc_html_e( 'Ver y gestionar los mensajes recibidos a través del formulario de contacto.', 'reforestamos-comunicacion' ); ?></p>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=contact-submissions' ) ); ?>" class="button button-primary">
							<?php esc_html_e( 'Ver Mensajes', 'reforestamos-comunicacion' ); ?>
						</a>
					</div>
					
					<div class="card" style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,0.04);">
						<h3><?php esc_html_e( 'Newsletter', 'reforestamos-comunicacion' ); ?></h3>
						<p><?php esc_html_e( 'Gestiona suscriptores y envía boletines de noticias.', 'reforestamos-comunicacion' ); ?></p>
						<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=boletin' ) ); ?>" class="button button-primary">
							<?php esc_html_e( 'Ir a Newsletter', 'reforestamos-comunicacion' ); ?>
						</a>
					</div>

					<div class="card" style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,0.04);">
						<h3><?php esc_html_e( 'ChatBot', 'reforestamos-comunicacion' ); ?></h3>
						<p><?php esc_html_e( 'Configura el chatbot y revisa las conversaciones.', 'reforestamos-comunicacion' ); ?></p>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=reforestamos-chatbot' ) ); ?>" class="button button-primary">
							<?php esc_html_e( 'Configurar ChatBot', 'reforestamos-comunicacion' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Contact Form Submissions page
	 *
	 * Loads the submissions list view.
	 */
	public function render_submissions_page() {
		require_once REFORESTAMOS_COMM_PATH . 'admin/views/submissions-list.php';
	}

	/**
	 * Render Tree Adoptions Dashboard page
	 */
	public function render_adoptions_page() {
		require_once REFORESTAMOS_COMM_PATH . 'admin/views/adoptions-dashboard.php';
	}

	/**
	 * Plugin activation
	 *
	 * Runs on plugin activation. Creates database tables and sets default options.
	 */
	public static function activate() {
		// Create database tables
		self::create_tables();
		
		// Create DeepL translation queue table
		require_once REFORESTAMOS_COMM_PATH . 'includes/class-deepl-integration.php';
		$deepl = Reforestamos_DeepL_Integration::get_instance();
		$deepl->create_queue_table();
		
		// Create tree adoption table
		require_once REFORESTAMOS_COMM_PATH . 'includes/class-tree-adoption.php';
		Reforestamos_Tree_Adoption::create_table();
		
		// Set default options
		self::set_default_options();
		
		// Flush rewrite rules
		flush_rewrite_rules();
	}

	/**
	 * Create database tables
	 *
	 * Creates tables for subscribers, form submissions, and chatbot logs.
	 */
	private static function create_tables() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		// Subscribers table
		$table_name = $wpdb->prefix . 'reforestamos_subscribers';
		$sql        = "CREATE TABLE IF NOT EXISTS $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			email varchar(255) NOT NULL,
			name varchar(255),
			status varchar(20) DEFAULT 'pending',
			verification_token varchar(64),
			subscribed_at datetime DEFAULT CURRENT_TIMESTAMP,
			verified_at datetime,
			unsubscribed_at datetime,
			PRIMARY KEY (id),
			UNIQUE KEY email (email),
			KEY verification_token (verification_token)
		) $charset_collate;";
		dbDelta( $sql );

		// Contact form submissions table
		$table_name = $wpdb->prefix . 'reforestamos_submissions';
		$sql        = "CREATE TABLE IF NOT EXISTS $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			name varchar(255) NOT NULL,
			email varchar(255) NOT NULL,
			subject varchar(255),
			message text NOT NULL,
			form_type varchar(50) DEFAULT 'contact',
			status varchar(20) DEFAULT 'new',
			submitted_at datetime DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id)
		) $charset_collate;";
		dbDelta( $sql );

		// ChatBot conversations table
		$table_name = $wpdb->prefix . 'reforestamos_chatbot_logs';
		$sql        = "CREATE TABLE IF NOT EXISTS $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			session_id varchar(255) NOT NULL,
			user_message text NOT NULL,
			bot_response text NOT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY session_id (session_id)
		) $charset_collate;";
		dbDelta( $sql );

		// Newsletter send logs table
		$table_name = $wpdb->prefix . 'reforestamos_newsletter_logs';
		$sql        = "CREATE TABLE IF NOT EXISTS $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			newsletter_id bigint(20) NOT NULL,
			subscriber_id bigint(20) NOT NULL,
			email varchar(255) NOT NULL,
			status varchar(20) DEFAULT 'pending',
			sent_at datetime DEFAULT CURRENT_TIMESTAMP,
			retry_count int(11) DEFAULT 0,
			error_message text,
			PRIMARY KEY (id),
			KEY newsletter_id (newsletter_id),
			KEY subscriber_id (subscriber_id),
			KEY status (status)
		) $charset_collate;";
		dbDelta( $sql );
	}

	/**
	 * Set default options
	 */
	private static function set_default_options() {
		// Set default SMTP settings if not already set
		if ( ! get_option( 'reforestamos_smtp_host' ) ) {
			add_option( 'reforestamos_smtp_host', '' );
		}
		if ( ! get_option( 'reforestamos_smtp_port' ) ) {
			add_option( 'reforestamos_smtp_port', '587' );
		}
		if ( ! get_option( 'reforestamos_smtp_username' ) ) {
			add_option( 'reforestamos_smtp_username', '' );
		}
		if ( ! get_option( 'reforestamos_smtp_password' ) ) {
			add_option( 'reforestamos_smtp_password', '' );
		}
		if ( ! get_option( 'reforestamos_smtp_from_email' ) ) {
			add_option( 'reforestamos_smtp_from_email', get_option( 'admin_email' ) );
		}
		if ( ! get_option( 'reforestamos_smtp_from_name' ) ) {
			add_option( 'reforestamos_smtp_from_name', get_option( 'blogname' ) );
		}
		
		// Schedule translation queue processing cron job
		if ( ! wp_next_scheduled( 'reforestamos_process_translation_queue' ) ) {
			wp_schedule_event( time(), 'hourly', 'reforestamos_process_translation_queue' );
		}
	}

	/**
	 * Plugin deactivation
	 *
	 * Runs on plugin deactivation.
	 */
	public static function deactivate() {
		// Clear scheduled cron jobs
		$timestamp = wp_next_scheduled( 'reforestamos_process_translation_queue' );
		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, 'reforestamos_process_translation_queue' );
		}
		
		flush_rewrite_rules();
	}
}
