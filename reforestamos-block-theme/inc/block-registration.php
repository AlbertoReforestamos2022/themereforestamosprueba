<?php
/**
 * Block Registration
 *
 * Registers all custom Gutenberg blocks from the blocks/ directory.
 * Each block should have its own subdirectory with a block.json file.
 *
 * @package Reforestamos
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register all custom blocks
 *
 * Dynamically registers all custom blocks found in the blocks/ directory.
 * Each block must have a block.json file in its subdirectory.
 *
 * Blocks registered:
 * - hero: Hero section with background image
 * - carousel: Image carousel
 * - contacto: Contact form
 * - documents: Document list
 * - faqs: FAQ accordion
 * - galeria-tabs: Tabbed gallery
 * - logos-aliados: Partner logos grid
 * - timeline: Timeline component
 * - cards-enlaces: Link cards
 * - cards-iniciativas: Initiative cards
 * - texto-imagen: Text with image
 * - list: Custom list
 * - sobre-nosotros: About us section
 * - header-navbar: Header navigation
 * - footer: Footer section
 * - eventos-proximos: Upcoming events
 *
 * @since 1.0.0
 * @return void
 */
function reforestamos_register_blocks() {
    // Get all block.json files from blocks/ directory
    $block_json_files = glob(REFORESTAMOS_THEME_DIR . '/blocks/*/block.json');
    
    if (empty($block_json_files)) {
        return;
    }
    
    // Register each block
    foreach ($block_json_files as $block_json) {
        // Register block type using block.json
        register_block_type($block_json);
    }
}
add_action('init', 'reforestamos_register_blocks');
