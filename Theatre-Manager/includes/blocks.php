<?php
/**
 * Theatre Manager Gutenberg Blocks Registration
 * 
 * Registers and enqueues all custom Gutenberg blocks
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue block editor assets
 * 
 * Registers and enqueues all block scripts and styles
 */
function tm_enqueue_block_assets() {
    // Only enqueue on the editor
    if (!is_admin()) {
        return;
    }

    $block_path = TM_PLUGIN_DIR . 'blocks/tm-landingpage-block';
    
    // Enqueue editor script with dependencies
    wp_enqueue_script(
        'tm-landingpage-block-editor',
        TM_PLUGIN_URL . 'blocks/tm-landingpage-block/index.js',
        ['wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor'],
        THEATRE_MANAGER_VERSION,
        true
    );

    // Enqueue editor styles
    wp_enqueue_style(
        'tm-landingpage-block-editor',
        TM_PLUGIN_URL . 'blocks/tm-landingpage-block/editor.css',
        [],
        THEATRE_MANAGER_VERSION
    );
}
add_action('enqueue_block_editor_assets', 'tm_enqueue_block_assets');
