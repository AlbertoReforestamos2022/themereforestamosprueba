<?php
/**
 * Breadcrumbs Navigation
 *
 * Provides breadcrumb navigation with Schema.org markup
 *
 * @package Reforestamos
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue breadcrumbs styles
 */
function reforestamos_breadcrumbs_styles() {
    ?>
    <style>
    .breadcrumbs {
        padding: 1rem 0;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
    }
    
    .breadcrumb-list {
        display: flex;
        flex-wrap: wrap;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 0.5rem;
    }
    
    .breadcrumb-item {
        display: flex;
        align-items: center;
    }
    
    .breadcrumb-item:not(:last-child)::after {
        content: '/';
        margin-left: 0.5rem;
        color: #6c757d;
    }
    
    .breadcrumb-item a {
        color: var(--wp--preset--color--primary, #2E7D32);
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .breadcrumb-item a:hover {
        color: var(--wp--preset--color--secondary, #66BB6A);
        text-decoration: underline;
    }
    
    .breadcrumb-item.active span {
        color: #6c757d;
    }
    
    @media (max-width: 768px) {
        .breadcrumbs {
            font-size: 0.75rem;
        }
    }
    </style>
    <?php
}
add_action('wp_head', 'reforestamos_breadcrumbs_styles');
