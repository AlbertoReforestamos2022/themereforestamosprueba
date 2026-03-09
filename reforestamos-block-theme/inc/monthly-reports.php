<?php
/**
 * Monthly Reports System
 *
 * Generates automated monthly reports with key metrics and exports to CSV.
 *
 * @package Reforestamos
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class Reforestamos_Monthly_Reports {
    
    /**
     * Initialize monthly reports system
     */
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_reports_page']);
        add_action('reforestamos_generate_monthly_report', [__CLASS__, 'generate_monthly_report']);
        
        // Schedule monthly report generation
        if (!wp_next_scheduled('reforestamos_generate_monthly_report')) {
            wp_schedule_event(strtotime('first day of next month 00:00:00'), 'monthly', 'reforestamos_generate_monthly_report');
        }
    }
    
    /**
     * Add reports admin page
     */
    public static function add_reports_page() {
        add_menu_page(
            __('Analytics Reports', 'reforestamos'),
            __('Reports', 'reforestamos'),
            'manage_options',
            'reforestamos-reports',
            [__CLASS__, 'render_reports_page'],
            'dashicons-chart-bar',
            30
        );
    }
    
    /**
     * Render reports page
     */
    public static function render_reports_page() {
        // Handle CSV export
        if (isset($_GET['action']) && $_GET['action'] === 'export' && isset($_GET['month'])) {
            check_admin_referer('export_report_' . $_GET['month']);
            self::export_report_csv($_GET['month']);
            exit;
        }
        
        // Handle manual report generation
        if (isset($_POST['generate_report']) && isset($_POST['report_month'])) {
            check_admin_referer('generate_monthly_report');
            $month = sanitize_text_field($_POST['report_month']);
            self::generate_monthly_report($month);
            echo '<div class="notice notice-success"><p>' . __('Report generated successfully!', 'reforestamos') . '</p></div>';
        }
        
        // Get available reports
        $reports = self::get_available_reports();
        
        ?>
        <div class="wrap">
            <h1><?php _e('Analytics Reports', 'reforestamos'); ?></h1>
            
            <div class="card" style="max-width: 800px;">
                <h2><?php _e('Generate New Report', 'reforestamos'); ?></h2>
                <form method="post" action="">
                    <?php wp_nonce_field('generate_monthly_report'); ?>
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="report_month"><?php _e('Month', 'reforestamos'); ?></label>
                            </th>
                            <td>
                                <input type="month" 
                                       id="report_month" 
                                       name="report_month" 
                                       value="<?php echo date('Y-m', strtotime('last month')); ?>"
                                       max="<?php echo date('Y-m'); ?>"
                                       required>
                                <p class="description">
                                    <?php _e('Select the month for which you want to generate a report.', 'reforestamos'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <p class="submit">
                        <button type="submit" name="generate_report" class="button button-primary">
                            <?php _e('Generate Report', 'reforestamos'); ?>
                        </button>
                    </p>
                </form>
            </div>
            
            <h2><?php _e('Available Reports', 'reforestamos'); ?></h2>
            
            <?php if (empty($reports)) : ?>
                <p><?php _e('No reports available yet. Generate your first report above.', 'reforestamos'); ?></p>
            <?php else : ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php _e('Month', 'reforestamos'); ?></th>
                            <th><?php _e('Generated', 'reforestamos'); ?></th>
                            <th><?php _e('Key Metrics', 'reforestamos'); ?></th>
                            <th><?php _e('Actions', 'reforestamos'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $report) : ?>
                            <tr>
                                <td>
                                    <strong><?php echo date('F Y', strtotime($report['month'] . '-01')); ?></strong>
                                </td>
                                <td>
                                    <?php echo date('Y-m-d H:i', strtotime($report['generated_at'])); ?>
                                </td>
                                <td>
                                    <ul style="margin: 0;">
                                        <?php if (isset($report['data']['companies'])) : ?>
                                            <li><?php printf(__('%s company clicks', 'reforestamos'), number_format($report['data']['companies']['total_clicks'])); ?></li>
                                        <?php endif; ?>
                                        <?php if (isset($report['data']['newsletter'])) : ?>
                                            <li><?php printf(__('%s newsletter subscribers', 'reforestamos'), number_format($report['data']['newsletter']['total_subscribers'])); ?></li>
                                        <?php endif; ?>
                                    </ul>
                                </td>
                                <td>
                                    <a href="<?php echo wp_nonce_url(
                                        admin_url('admin.php?page=reforestamos-reports&action=export&month=' . $report['month']),
                                        'export_report_' . $report['month']
                                    ); ?>" class="button">
                                        <?php _e('Export CSV', 'reforestamos'); ?>
                                    </a>
                                    <a href="#" class="button view-report-details" data-month="<?php echo esc_attr($report['month']); ?>">
                                        <?php _e('View Details', 'reforestamos'); ?>
                                    </a>
                                </td>
                            </tr>
                            <tr class="report-details" id="details-<?php echo esc_attr($report['month']); ?>" style="display: none;">
                                <td colspan="4">
                                    <?php self::render_report_details($report); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelectorAll('.view-report-details').forEach(function(btn) {
                        btn.addEventListener('click', function(e) {
                            e.preventDefault();
                            var month = this.getAttribute('data-month');
                            var details = document.getElementById('details-' + month);
                            if (details.style.display === 'none') {
                                details.style.display = 'table-row';
                                this.textContent = '<?php _e('Hide Details', 'reforestamos'); ?>';
                            } else {
                                details.style.display = 'none';
                                this.textContent = '<?php _e('View Details', 'reforestamos'); ?>';
                            }
                        });
                    });
                });
                </script>
            <?php endif; ?>
        </div>
        <?php
    }
    
    /**
     * Render report details
     */
    private static function render_report_details($report) {
        $data = $report['data'];
        ?>
        <div class="report-details-content" style="padding: 1rem; background: #f9f9f9;">
            <h3><?php echo date('F Y', strtotime($report['month'] . '-01')); ?> - <?php _e('Detailed Report', 'reforestamos'); ?></h3>
            
            <?php if (isset($data['companies'])) : ?>
                <div class="report-section">
                    <h4><?php _e('Company Engagement', 'reforestamos'); ?></h4>
                    <ul>
                        <li><?php printf(__('Total Clicks: %s', 'reforestamos'), number_format($data['companies']['total_clicks'])); ?></li>
                        <li><?php printf(__('Unique Clicks: %s', 'reforestamos'), number_format($data['companies']['unique_clicks'])); ?></li>
                        <li><?php printf(__('Active Companies: %s', 'reforestamos'), number_format($data['companies']['active_companies'])); ?></li>
                    </ul>
                    <?php if (!empty($data['companies']['top_companies'])) : ?>
                        <p><strong><?php _e('Top 5 Companies:', 'reforestamos'); ?></strong></p>
                        <ol>
                            <?php foreach ($data['companies']['top_companies'] as $company) : ?>
                                <li><?php echo esc_html($company['name']) . ' - ' . number_format($company['clicks']) . ' clicks'; ?></li>
                            <?php endforeach; ?>
                        </ol>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($data['newsletter'])) : ?>
                <div class="report-section">
                    <h4><?php _e('Newsletter Performance', 'reforestamos'); ?></h4>
                    <ul>
                        <li><?php printf(__('Total Subscribers: %s', 'reforestamos'), number_format($data['newsletter']['total_subscribers'])); ?></li>
                        <li><?php printf(__('New Subscribers: %s', 'reforestamos'), number_format($data['newsletter']['new_subscribers'])); ?></li>
                        <li><?php printf(__('Campaigns Sent: %s', 'reforestamos'), number_format($data['newsletter']['campaigns_sent'])); ?></li>
                        <li><?php printf(__('Success Rate: %s%%', 'reforestamos'), $data['newsletter']['success_rate']); ?></li>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if (isset($data['content'])) : ?>
                <div class="report-section">
                    <h4><?php _e('Content Activity', 'reforestamos'); ?></h4>
                    <ul>
                        <li><?php printf(__('New Posts: %s', 'reforestamos'), number_format($data['content']['new_posts'])); ?></li>
                        <li><?php printf(__('New Events: %s', 'reforestamos'), number_format($data['content']['new_events'])); ?></li>
                        <li><?php printf(__('New Companies: %s', 'reforestamos'), number_format($data['content']['new_companies'])); ?></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
    
    /**
     * Generate monthly report
     */
    public static function generate_monthly_report($month = null) {
        global $wpdb;
        
        if (!$month) {
            $month = date('Y-m', strtotime('last month'));
        }
        
        $start_date = $month . '-01 00:00:00';
        $end_date = date('Y-m-t 23:59:59', strtotime($start_date));
        
        $report_data = [];
        
        // Companies analytics
        if (class_exists('Reforestamos_Analytics')) {
            $clicks_table = $wpdb->prefix . 'reforestamos_company_clicks';
            
            if ($wpdb->get_var("SHOW TABLES LIKE '$clicks_table'") == $clicks_table) {
                $total_clicks = $wpdb->get_var($wpdb->prepare(
                    "SELECT COUNT(*) FROM $clicks_table WHERE clicked_at BETWEEN %s AND %s",
                    $start_date, $end_date
                ));
                
                $unique_clicks = $wpdb->get_var($wpdb->prepare(
                    "SELECT COUNT(*) FROM $clicks_table WHERE is_unique = 1 AND clicked_at BETWEEN %s AND %s",
                    $start_date, $end_date
                ));
                
                $active_companies = $wpdb->get_var($wpdb->prepare(
                    "SELECT COUNT(DISTINCT company_id) FROM $clicks_table WHERE clicked_at BETWEEN %s AND %s",
                    $start_date, $end_date
                ));
                
                $top_companies = $wpdb->get_results($wpdb->prepare(
                    "SELECT c.company_id, p.post_title as name, COUNT(*) as clicks
                    FROM $clicks_table c
                    LEFT JOIN {$wpdb->posts} p ON c.company_id = p.ID
                    WHERE c.clicked_at BETWEEN %s AND %s
                    GROUP BY c.company_id
                    ORDER BY clicks DESC
                    LIMIT 5",
                    $start_date, $end_date
                ), ARRAY_A);
                
                $report_data['companies'] = [
                    'total_clicks' => $total_clicks,
                    'unique_clicks' => $unique_clicks,
                    'active_companies' => $active_companies,
                    'top_companies' => $top_companies
                ];
            }
        }
        
        // Newsletter metrics
        if (class_exists('Reforestamos_Newsletter')) {
            $subscribers_table = $wpdb->prefix . 'reforestamos_subscribers';
            
            if ($wpdb->get_var("SHOW TABLES LIKE '$subscribers_table'") == $subscribers_table) {
                $total_subscribers = $wpdb->get_var(
                    "SELECT COUNT(*) FROM $subscribers_table WHERE status = 'active'"
                );
                
                $new_subscribers = $wpdb->get_var($wpdb->prepare(
                    "SELECT COUNT(*) FROM $subscribers_table 
                    WHERE status = 'active' AND subscribed_at BETWEEN %s AND %s",
                    $start_date, $end_date
                ));
                
                $logs_table = $wpdb->prefix . 'reforestamos_newsletter_logs';
                $campaigns_sent = 0;
                $success_rate = 0;
                
                if ($wpdb->get_var("SHOW TABLES LIKE '$logs_table'") == $logs_table) {
                    $campaigns_sent = $wpdb->get_var($wpdb->prepare(
                        "SELECT COUNT(DISTINCT newsletter_id) FROM $logs_table WHERE sent_at BETWEEN %s AND %s",
                        $start_date, $end_date
                    ));
                    
                    $total_attempts = $wpdb->get_var($wpdb->prepare(
                        "SELECT COUNT(*) FROM $logs_table WHERE sent_at BETWEEN %s AND %s",
                        $start_date, $end_date
                    ));
                    
                    $successful = $wpdb->get_var($wpdb->prepare(
                        "SELECT COUNT(*) FROM $logs_table WHERE status = 'sent' AND sent_at BETWEEN %s AND %s",
                        $start_date, $end_date
                    ));
                    
                    if ($total_attempts > 0) {
                        $success_rate = round(($successful / $total_attempts) * 100, 1);
                    }
                }
                
                $report_data['newsletter'] = [
                    'total_subscribers' => $total_subscribers,
                    'new_subscribers' => $new_subscribers,
                    'campaigns_sent' => $campaigns_sent,
                    'success_rate' => $success_rate
                ];
            }
        }
        
        // Content activity
        $new_posts = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->posts} 
            WHERE post_type = 'post' AND post_status = 'publish' 
            AND post_date BETWEEN %s AND %s",
            $start_date, $end_date
        ));
        
        $new_events = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->posts} 
            WHERE post_type = 'eventos' AND post_status = 'publish' 
            AND post_date BETWEEN %s AND %s",
            $start_date, $end_date
        ));
        
        $new_companies = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->posts} 
            WHERE post_type = 'empresas' AND post_status = 'publish' 
            AND post_date BETWEEN %s AND %s",
            $start_date, $end_date
        ));
        
        $report_data['content'] = [
            'new_posts' => $new_posts,
            'new_events' => $new_events,
            'new_companies' => $new_companies
        ];
        
        // Save report
        $reports = get_option('reforestamos_monthly_reports', []);
        $reports[$month] = [
            'month' => $month,
            'generated_at' => current_time('mysql'),
            'data' => $report_data
        ];
        update_option('reforestamos_monthly_reports', $reports);
        
        return $report_data;
    }
    
    /**
     * Get available reports
     */
    private static function get_available_reports() {
        $reports = get_option('reforestamos_monthly_reports', []);
        
        // Sort by month descending
        krsort($reports);
        
        return $reports;
    }
    
    /**
     * Export report to CSV
     */
    private static function export_report_csv($month) {
        $reports = get_option('reforestamos_monthly_reports', []);
        
        if (!isset($reports[$month])) {
            wp_die(__('Report not found', 'reforestamos'));
        }
        
        $report = $reports[$month];
        $data = $report['data'];
        
        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=reforestamos-report-' . $month . '.csv');
        
        // Create output stream
        $output = fopen('php://output', 'w');
        
        // Add BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Report header
        fputcsv($output, ['Reforestamos Monthly Report - ' . date('F Y', strtotime($month . '-01'))]);
        fputcsv($output, ['Generated: ' . $report['generated_at']]);
        fputcsv($output, []);
        
        // Companies section
        if (isset($data['companies'])) {
            fputcsv($output, ['COMPANY ENGAGEMENT']);
            fputcsv($output, ['Metric', 'Value']);
            fputcsv($output, ['Total Clicks', $data['companies']['total_clicks']]);
            fputcsv($output, ['Unique Clicks', $data['companies']['unique_clicks']]);
            fputcsv($output, ['Active Companies', $data['companies']['active_companies']]);
            fputcsv($output, []);
            
            if (!empty($data['companies']['top_companies'])) {
                fputcsv($output, ['Top Companies']);
                fputcsv($output, ['Company Name', 'Clicks']);
                foreach ($data['companies']['top_companies'] as $company) {
                    fputcsv($output, [$company['name'], $company['clicks']]);
                }
                fputcsv($output, []);
            }
        }
        
        // Newsletter section
        if (isset($data['newsletter'])) {
            fputcsv($output, ['NEWSLETTER PERFORMANCE']);
            fputcsv($output, ['Metric', 'Value']);
            fputcsv($output, ['Total Subscribers', $data['newsletter']['total_subscribers']]);
            fputcsv($output, ['New Subscribers', $data['newsletter']['new_subscribers']]);
            fputcsv($output, ['Campaigns Sent', $data['newsletter']['campaigns_sent']]);
            fputcsv($output, ['Success Rate', $data['newsletter']['success_rate'] . '%']);
            fputcsv($output, []);
        }
        
        // Content section
        if (isset($data['content'])) {
            fputcsv($output, ['CONTENT ACTIVITY']);
            fputcsv($output, ['Metric', 'Value']);
            fputcsv($output, ['New Posts', $data['content']['new_posts']]);
            fputcsv($output, ['New Events', $data['content']['new_events']]);
            fputcsv($output, ['New Companies', $data['content']['new_companies']]);
        }
        
        fclose($output);
    }
}

