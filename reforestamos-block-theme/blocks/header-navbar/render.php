<?php
/**
 * Header Navbar Block Server-Side Rendering
 * 
 * This file handles the server-side rendering of the WordPress menu
 * within the Header Navbar block.
 * 
 * @package Reforestamos
 */

/**
 * Render callback for header-navbar block
 * 
 * @param array $attributes Block attributes
 * @param string $content Block content
 * @return string Rendered block HTML
 */
function reforestamos_render_header_navbar_block($attributes, $content) {
    $menu_id = isset($attributes['menuId']) ? $attributes['menuId'] : '';
    $show_language_switcher = isset($attributes['showLanguageSwitcher']) ? $attributes['showLanguageSwitcher'] : true;
    
    // Get the menu HTML if menu ID is set
    if (!empty($menu_id)) {
        $menu_html = wp_nav_menu(array(
            'menu' => $menu_id,
            'container' => false,
            'menu_class' => 'navbar-nav',
            'fallback_cb' => false,
            'echo' => false,
            'depth' => 2,
            'walker' => new Reforestamos_Bootstrap_Nav_Walker()
        ));
        
        // Replace the placeholder with actual menu
        if (!empty($menu_html)) {
            $content = preg_replace(
                '/<div class="navbar-menu-container" data-menu-id="' . esc_attr($menu_id) . '">.*?<\/div>/s',
                '<div class="navbar-menu-container" data-menu-id="' . esc_attr($menu_id) . '">' . $menu_html . '</div>',
                $content
            );
        }
    }
    
    // Add language switcher if enabled
    if ($show_language_switcher) {
        $language_switcher_html = reforestamos_get_language_switcher(array(
            'show_flags' => false,
            'show_text' => true,
            'class' => 'navbar-nav ms-auto language-switcher',
            'button_class' => 'btn btn-link nav-link lang-btn',
        ));
        
        // Replace the language switcher placeholder
        $content = preg_replace(
            '/<div class="navbar-nav ms-auto language-switcher">.*?<\/div>/s',
            $language_switcher_html,
            $content
        );
    }
    
    return $content;
}

/**
 * Custom Walker for Bootstrap 5 Navigation
 */
class Reforestamos_Bootstrap_Nav_Walker extends Walker_Nav_Menu {
    
    /**
     * Start Level
     */
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu dropdown-menu\">\n";
    }
    
    /**
     * Start Element
     */
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'nav-item';
        $classes[] = 'menu-item-' . $item->ID;
        
        // Add dropdown class if item has children
        if (in_array('menu-item-has-children', $classes)) {
            $classes[] = 'dropdown';
        }
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names . '>';
        
        // Link attributes
        $atts = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = !empty($item->url) ? $item->url : '';
        $atts['class']  = 'nav-link';
        
        // Add dropdown toggle attributes if item has children
        if (in_array('menu-item-has-children', $classes)) {
            $atts['class'] .= ' dropdown-toggle';
            $atts['data-bs-toggle'] = 'dropdown';
            $atts['aria-expanded'] = 'false';
        }
        
        // Add current/active classes
        if (in_array('current-menu-item', $classes) || in_array('current_page_item', $classes)) {
            $atts['class'] .= ' active';
            $atts['aria-current'] = 'page';
        }
        
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);
        
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        
        $title = apply_filters('the_title', $item->title, $item->ID);
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);
        
        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

/**
 * Register the render callback
 */
add_filter('render_block_reforestamos/header-navbar', 'reforestamos_render_header_navbar_block', 10, 2);

/**
 * Enqueue frontend script for header navbar
 */
function reforestamos_header_navbar_enqueue_scripts() {
    if (has_block('reforestamos/header-navbar')) {
        wp_enqueue_script(
            'reforestamos-header-navbar',
            get_template_directory_uri() . '/blocks/header-navbar/frontend.js',
            array(),
            filemtime(get_template_directory() . '/blocks/header-navbar/frontend.js'),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'reforestamos_header_navbar_enqueue_scripts');
