<?php
/**
 * Analytics Dashboard View
 *
 * Displays analytics metrics, charts, and reports for company clicks.
 *
 * @package Reforestamos_Empresas
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap reforestamos-analytics-dashboard">
    <h1><?php _e('Analytics de Empresas', 'reforestamos-empresas'); ?></h1>
    
    <!-- Filters -->
    <div class="analytics-filters">
        <form method="get" class="analytics-filter-form">
            <input type="hidden" name="post_type" value="empresas">
            <input type="hidden" name="page" value="empresas-analytics">
            
            <div class="filter-group">
                <label for="start_date"><?php _e('Desde:', 'reforestamos-empresas'); ?></label>
                <input type="date" 
                       id="start_date" 
                       name="start_date" 
                       value="<?php echo esc_attr($start_date); ?>"
                       max="<?php echo esc_attr(date('Y-m-d')); ?>">
            </div>
            
            <div class="filter-group">
                <label for="end_date"><?php _e('Hasta:', 'reforestamos-empresas'); ?></label>
                <input type="date" 
                       id="end_date" 
                       name="end_date" 
                       value="<?php echo esc_attr($end_date); ?>"
                       max="<?php echo esc_attr(date('Y-m-d')); ?>">
            </div>
            
            <div class="filter-actions">
                <button type="submit" class="button button-primary">
                    <span class="dashicons dashicons-filter"></span>
                    <?php _e('Filtrar', 'reforestamos-empresas'); ?>
                </button>
                <a href="<?php echo esc_url(add_query_arg(['export' => 'csv'], admin_url('edit.php?post_type=empresas&page=empresas-analytics'))); ?>" 
                   class="button button-secondary">
                    <span class="dashicons dashicons-download"></span>
                    <?php _e('Exportar CSV', 'reforestamos-empresas'); ?>
                </a>
            </div>
        </form>
    </div>
    
    <!-- Stats Overview -->
    <div class="analytics-stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <span class="dashicons dashicons-chart-line"></span>
            </div>
            <div class="stat-content">
                <h3 class="stat-value"><?php echo number_format($total_clicks); ?></h3>
                <p class="stat-label"><?php _e('Total de Clics', 'reforestamos-empresas'); ?></p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <span class="dashicons dashicons-groups"></span>
            </div>
            <div class="stat-content">
                <h3 class="stat-value"><?php echo number_format($unique_clicks); ?></h3>
                <p class="stat-label"><?php _e('Clics Únicos', 'reforestamos-empresas'); ?></p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <span class="dashicons dashicons-building"></span>
            </div>
            <div class="stat-content">
                <h3 class="stat-value"><?php echo count($clicks_by_company); ?></h3>
                <p class="stat-label"><?php _e('Empresas con Clics', 'reforestamos-empresas'); ?></p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <span class="dashicons dashicons-chart-bar"></span>
            </div>
            <div class="stat-content">
                <h3 class="stat-value">
                    <?php 
                    $avg = $total_clicks > 0 && count($clicks_by_company) > 0 
                        ? round($total_clicks / count($clicks_by_company), 1) 
                        : 0;
                    echo number_format($avg, 1); 
                    ?>
                </h3>
                <p class="stat-label"><?php _e('Promedio por Empresa', 'reforestamos-empresas'); ?></p>
            </div>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div class="analytics-charts">
        <!-- Top Companies Table -->
        <div class="chart-container">
            <div class="chart-header">
                <h2><?php _e('Top 10 Empresas', 'reforestamos-empresas'); ?></h2>
                <p class="chart-description">
                    <?php _e('Empresas con más clics en el período seleccionado', 'reforestamos-empresas'); ?>
                </p>
            </div>
            
            <?php if (!empty($clicks_by_company)) : ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th class="column-rank"><?php _e('#', 'reforestamos-empresas'); ?></th>
                            <th class="column-company"><?php _e('Empresa', 'reforestamos-empresas'); ?></th>
                            <th class="column-total"><?php _e('Total Clics', 'reforestamos-empresas'); ?></th>
                            <th class="column-unique"><?php _e('Clics Únicos', 'reforestamos-empresas'); ?></th>
                            <th class="column-percentage"><?php _e('% del Total', 'reforestamos-empresas'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $rank = 1;
                        foreach ($clicks_by_company as $row) : 
                            $percentage = $total_clicks > 0 ? ($row->total_clicks / $total_clicks) * 100 : 0;
                        ?>
                        <tr>
                            <td class="column-rank"><?php echo $rank++; ?></td>
                            <td class="column-company">
                                <a href="<?php echo esc_url(get_edit_post_link($row->company_id)); ?>">
                                    <?php echo esc_html($row->post_title); ?>
                                </a>
                            </td>
                            <td class="column-total">
                                <strong><?php echo number_format($row->total_clicks); ?></strong>
                            </td>
                            <td class="column-unique">
                                <?php echo number_format($row->unique_clicks); ?>
                            </td>
                            <td class="column-percentage">
                                <div class="percentage-bar">
                                    <div class="percentage-fill" style="width: <?php echo esc_attr($percentage); ?>%"></div>
                                    <span class="percentage-text"><?php echo number_format($percentage, 1); ?>%</span>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <div class="no-data-message">
                    <span class="dashicons dashicons-info"></span>
                    <p><?php _e('No hay datos de clics en el período seleccionado.', 'reforestamos-empresas'); ?></p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Monthly Clicks Chart -->
        <div class="chart-container">
            <div class="chart-header">
                <h2><?php _e('Clics por Mes', 'reforestamos-empresas'); ?></h2>
                <p class="chart-description">
                    <?php _e('Evolución de clics en los últimos 12 meses', 'reforestamos-empresas'); ?>
                </p>
            </div>
            
            <?php if (!empty($clicks_by_month)) : ?>
                <div class="chart-canvas-wrapper">
                    <canvas id="monthly-clicks-chart"></canvas>
                </div>
                
                <script type="text/javascript">
                    var monthlyClicksData = {
                        labels: [
                            <?php 
                            foreach ($clicks_by_month as $row) {
                                $date = DateTime::createFromFormat('Y-m', $row->month);
                                echo '"' . $date->format('M Y') . '",';
                            }
                            ?>
                        ],
                        datasets: [
                            {
                                label: '<?php _e('Total Clics', 'reforestamos-empresas'); ?>',
                                data: [<?php echo implode(',', array_column($clicks_by_month, 'total_clicks')); ?>],
                                borderColor: '#2E7D32',
                                backgroundColor: 'rgba(46, 125, 50, 0.1)',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: '<?php _e('Clics Únicos', 'reforestamos-empresas'); ?>',
                                data: [<?php echo implode(',', array_column($clicks_by_month, 'unique_clicks')); ?>],
                                borderColor: '#66BB6A',
                                backgroundColor: 'rgba(102, 187, 106, 0.1)',
                                tension: 0.4,
                                fill: true
                            }
                        ]
                    };
                </script>
            <?php else : ?>
                <div class="no-data-message">
                    <span class="dashicons dashicons-info"></span>
                    <p><?php _e('No hay datos suficientes para mostrar el gráfico.', 'reforestamos-empresas'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Additional Info -->
    <div class="analytics-info">
        <div class="info-box">
            <h3><?php _e('Acerca de las Métricas', 'reforestamos-empresas'); ?></h3>
            <ul>
                <li>
                    <strong><?php _e('Total de Clics:', 'reforestamos-empresas'); ?></strong>
                    <?php _e('Número total de clics registrados, incluyendo clics repetidos del mismo usuario.', 'reforestamos-empresas'); ?>
                </li>
                <li>
                    <strong><?php _e('Clics Únicos:', 'reforestamos-empresas'); ?></strong>
                    <?php _e('Primer clic de cada usuario único (identificado por sesión/cookie).', 'reforestamos-empresas'); ?>
                </li>
                <li>
                    <strong><?php _e('Período:', 'reforestamos-empresas'); ?></strong>
                    <?php 
                    printf(
                        __('Mostrando datos desde %s hasta %s', 'reforestamos-empresas'),
                        date_i18n(get_option('date_format'), strtotime($start_date)),
                        date_i18n(get_option('date_format'), strtotime($end_date))
                    );
                    ?>
                </li>
            </ul>
        </div>
    </div>
</div>
