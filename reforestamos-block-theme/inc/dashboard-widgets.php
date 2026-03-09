<?php
/**
 * Dashboard Widgets for Analytics
 *
 * Provides dashboard widgets for companies, newsletter, and micrositios metrics.
 *
 * @package Reforestamos
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class Reforestamos_Dashboard_Widgets {
    
    /**
     * Initialize dashboard widgets
     */
    public static function init() {
        add_action('wp_dashboard_setup', [__CLASS__, 'register_widgets']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
    }
    
    /**
     * Register dashboard widgets
     */
    public static function register_widgets() {
        // Companies analytics widget (if plugin is active)
        if (class_exists('Reforestamos_Analytics')) {
            wp_add_dashboard_widget(
                'reforestamos_companies_widget',
                __('Company Engagement Metrics', 'reforestamos'),
                [__CLASS__, 'render_companies_widget']
            );
        }
        
        // Newsletter metrics widget (if plugin is active)
        if (class_exists('Reforestamos_Newsletter')) {
            wp_add_dashboard_widget(
                'reforestamos_newsletter_widget',
                __('Newsletter Metrics', 'reforestamos'),
                [__CLASS__, 'render_newsletter_widget']
            );
        }
        
        // Micrositios metrics widget (if plugin is active)
        if (class_exists('Reforestamos_Micrositios')) {
            wp_add_dashboard_widget(
                'reforestamos_micrositios_widget',
                __('Microsites Metrics', 'reforestamos'),
                [__CLASS__, 'render_micrositios_widget']
            );
        }
    }
    
    /**
     * Enqueue dashboard widget assets
     */
    public static function enqueue_assets($hook) {
        if ($hook !== 'index.php') {
            return;
        }
        
        wp_enqueue_style(
            'reforestamos-dashboard-widgets',
            get_template_directory_uri() . '/build/dashboard-widgets.css',
            [],
            REFORESTAMOS_VERSION
        );
    }
    
    /**
     * Render companies analytics widget
     */
    public static function render_companies_widget() {
        global $wpdb;
        
        // Get date range (last 30 days)
        $start_date = date('Y-m-d', strtotime('-30 days'));
        $end_date = date('Y-m-d');
        
        $table_name = $wpdb->prefix . 'reforestamos_company_clicks';
        
        // Check if table exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            echo '<p>' . __('No analytics data available yet.', 'reforestamos') . '</p>';
            return;
        }
        
        // Get total clicks
        $total_clicks = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE clicked_at BETWEEN %s AND %s",
            $start_date . ' 00:00:00',
            $end_date . ' 23:59:59'
        ));
        
        // Get unique clicks
        $unique_clicks = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE is_unique = 1 AND clicked_at BETWEEN %s AND %s",
            $start_date . ' 00:00:00',
            $end_date . ' 23:59:59'
        ));
        
        // Get top 5 companies
        $top_companies = $wpdb->get_results($wpdb->prepare(
            "SELECT c.company_id, p.post_title, COUNT(*) as clicks
            FROM $table_name c
            LEFT JOIN {$wpdb->posts} p ON c.company_id = p.ID
            WHERE c.clicked_at BETWEEN %s AND %s
            GROUP BY c.company_id
            ORDER BY clicks DESC
            LIMIT 5",
            $start_date . ' 00:00:00',
            $end_date . ' 23:59:59'
        ));
        
        // Get clicks trend (last 7 days)
        $trend = $wpdb->get_results($wpdb->prepare(
            "SELECT DATE(clicked_at) as date, COUNT(*) as clicks
            FROM $table_name
            WHERE clicked_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(clicked_at)
            ORDER BY date ASC"
        ));
        
        ?>
        <div class="reforestamos-widget-content">
            <div class="widget-stats">
                <div class="stat-item">
                    <span class="stat-value"><?php echo number_format($total_clicks); ?></span>
                    <span class="stat-label"><?php _e('Total Clicks', 'reforestamos'); ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?php echo number_format($unique_clicks); ?></span>
                    <span class="stat-label"><?php _e('Unique Clicks', 'reforestamos'); ?></span>
                </div>
            </div>
            
            <?php if (!empty($top_companies)) : ?>
                <div class="widget-section">
                    <h4><?php _e('Top Companies (Last 30 Days)', 'reforestamos'); ?></h4>
                    <ul class="widget-list">
                        <?php foreach ($top_companies as $company) : ?>
                            <li>
                                <a href="<?php echo admin_url('post.php?post=' . $company->company_id . '&action=edit'); ?>">
                                    <?php echo esc_html($company->post_title); ?>
                                </a>
                                <span class="badge"><?php echo number_format($company->clicks); ?> clicks</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($trend)) : ?>
                <div class="widget-section">
                    <h4><?php _e('7-Day Trend', 'reforestamos'); ?></h4>
                    <div class="mini-chart">
                        <?php 
                        $max_clicks = max(array_column($trend, 'clicks'));
                        foreach ($trend as $day) : 
                            $height = $max_clicks > 0 ? ($day->clicks / $max_clicks) * 100 : 0;
                        ?>
                            <div class="chart-bar" style="height: <?php echo $height; ?>%;" 
                                 title="<?php echo date('M j', strtotime($day->date)) . ': ' . $day->clicks; ?> clicks">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="widget-footer">
                <a href="<?php echo admin_url('edit.php?post_type=empresas&page=empresas-analytics'); ?>" class="button button-primary">
                    <?php _e('View Full Analytics', 'reforestamos'); ?>
                </a>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render newsletter metrics widget
     */
    public static function render_newsletter_widget() {
        global $wpdb;
        
        // Get subscriber count
        $subscribers_table = $wpdb->prefix . 'reforestamos_subscribers';
        
        // Check if table exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$subscribers_table'") != $subscribers_table) {
            echo '<p>' . __('No newsletter data available yet.', 'reforestamos') . '</p>';
            return;
        }
        
        $total_subscribers = $wpdb->get_var(
            "SELECT COUNT(*) FROM $subscribers_table WHERE status = 'active'"
        );
        
        $pending_subscribers = $wpdb->get_var(
            "SELECT COUNT(*) FROM $subscribers_table WHERE status = 'pending'"
        );
        
        // Get recent subscribers (last 30 days)
        $recent_subscribers = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $subscribers_table 
            WHERE status = 'active' AND subscribed_at >= %s",
            date('Y-m-d H:i:s', strtotime('-30 days'))
        ));
        
        // Get newsletter send logs
        $logs_table = $wpdb->prefix . 'reforestamos_newsletter_logs';
        
        $recent_sends = 0;
        $success_rate = 0;
        
        if ($wpdb->get_var("SHOW TABLES LIKE '$logs_table'") == $logs_table) {
            $recent_sends = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(DISTINCT newsletter_id) FROM $logs_table 
                WHERE sent_at >= %s",
                date('Y-m-d H:i:s', strtotime('-30 days'))
            ));
            
            $total_attempts = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $logs_table WHERE sent_at >= %s",
                date('Y-m-d H:i:s', strtotime('-30 days'))
            ));
            
            $successful_sends = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $logs_table 
                WHERE status = 'sent' AND sent_at >= %s",
                date('Y-m-d H:i:s', strtotime('-30 days'))
            ));
            
            if ($total_attempts > 0) {
                $success_rate = round(($successful_sends / $total_attempts) * 100, 1);
            }
        }
        
        // Get subscriber growth trend (last 7 days)
        $growth_trend = $wpdb->get_results(
            "SELECT DATE(subscribed_at) as date, COUNT(*) as new_subscribers
            FROM $subscribers_table
            WHERE subscribed_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(subscribed_at)
            ORDER BY date ASC"
        );
        
        ?>
        <div class="reforestamos-widget-content">
            <div class="widget-stats">
                <div class="stat-item">
                    <span class="stat-value"><?php echo number_format($total_subscribers); ?></span>
                    <span class="stat-label"><?php _e('Active Subscribers', 'reforestamos'); ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?php echo number_format($recent_subscribers); ?></span>
                    <span class="stat-label"><?php _e('New (30 days)', 'reforestamos'); ?></span>
                </div>
            </div>
            
            <div class="widget-stats">
                <div class="stat-item">
                    <span class="stat-value"><?php echo number_format($recent_sends); ?></span>
                    <span class="stat-label"><?php _e('Campaigns Sent', 'reforestamos'); ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?php echo $success_rate; ?>%</span>
                    <span class="stat-label"><?php _e('Success Rate', 'reforestamos'); ?></span>
                </div>
            </div>
            
            <?php if ($pending_subscribers > 0) : ?>
                <div class="widget-notice">
                    <span class="dashicons dashicons-info"></span>
                    <?php printf(
                        _n('%d subscriber pending verification', '%d subscribers pending verification', $pending_subscribers, 'reforestamos'),
                        $pending_subscribers
                    ); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($growth_trend)) : ?>
                <div class="widget-section">
                    <h4><?php _e('Subscriber Growth (7 Days)', 'reforestamos'); ?></h4>
                    <div class="mini-chart">
                        <?php 
                        $max_subs = max(array_column($growth_trend, 'new_subscribers'));
                        foreach ($growth_trend as $day) : 
                            $height = $max_subs > 0 ? ($day->new_subscribers / $max_subs) * 100 : 0;
                        ?>
                            <div class="chart-bar" style="height: <?php echo $height; ?>%;" 
                                 title="<?php echo date('M j', strtotime($day->date)) . ': ' . $day->new_subscribers; ?> new">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="widget-footer">
                <a href="<?php echo admin_url('admin.php?page=reforestamos-newsletter-subscribers'); ?>" class="button">
                    <?php _e('Manage Subscribers', 'reforestamos'); ?>
                </a>
                <a href="<?php echo admin_url('admin.php?page=reforestamos-newsletter-campaigns'); ?>" class="button button-primary">
                    <?php _e('View Campaigns', 'reforestamos'); ?>
                </a>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render micrositios metrics widget
     */
    public static function render_micrositios_widget() {
        global $wpdb;
        
        // Get microsite page views from search logs (as proxy for visits)
        $search_logs_table = $wpdb->prefix . 'reforestamos_search_logs';
        
        // For now, show basic microsite info
        // In a full implementation, you'd track microsite-specific analytics
        
        ?>
        <div class="reforestamos-widget-content">
            <div class="widget-section">
                <h4><?php _e('Active Microsites', 'reforestamos'); ?></h4>
                <ul class="widget-list">
                    <li>
                        <strong><?php _e('Árboles y Ciudades', 'reforestamos'); ?></strong>
                        <span class="badge"><?php _e('Active', 'reforestamos'); ?></span>
                    </li>
                    <li>
                        <strong><?php _e('Red OJA', 'reforestamos'); ?></strong>
                        <span class="badge"><?php _e('Active', 'reforestamos'); ?></span>
                    </li>
                </ul>
            </div>
            
            <div class="widget-notice">
                <span class="dashicons dashicons-info"></span>
                <?php _e('Detailed microsite analytics coming soon. Enable GA4 tracking for comprehensive metrics.', 'reforestamos'); ?>
            </div>
            
            <div class="widget-footer">
                <a href="<?php echo admin_url('admin.php?page=reforestamos-micrositios'); ?>" class="button button-primary">
                    <?php _e('Manage Microsites', 'reforestamos'); ?>
                </a>
            </div>
        </div>
        <?php
    }
}

