<?php
// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Enqueue admin scripts and styles
 */
function tm_enqueue_admin_assets($hook) {
    global $post_type;

    // Load only for Theatre Manager CPTs
    $cpts = array('board_member', 'advertiser', 'sponsors', 'testimonials', 'contributors', 'season', 'show', 'cast'); // Add more CPTs here as needed

    if (in_array($post_type, $cpts)) {
        wp_enqueue_media();
        wp_enqueue_script(
            'tm-admin-js',
            TM_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            '1.0.0',
            true
        );
        wp_enqueue_style(
            'tm-admin-css',
            TM_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            '1.0.0'
        );
    }

    if (strpos($hook, 'post.php') !== false || strpos($hook, 'post-new.php') !== false) {
        wp_enqueue_media();
        wp_enqueue_script('tm-admin-media', TM_PLUGIN_URL . 'assets/js/admin-media.js', array('jquery'), '1.0.0' , true);
    }

}
add_action('admin_enqueue_scripts', 'tm_enqueue_admin_assets');

function tm_enqueue_admin_scripts($hook) {
    // Only load on Theatre Manager admin pages
    if (strpos($hook, 'tm-display-options') === false) {
        return;
    }

    // Enqueue WordPress color picker
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');

    // Enqueue custom admin JS
    wp_enqueue_script('tm-admin-js', TM_PLUGIN_URL . 'assets/js/admin.js', array('jquery', 'wp-color-picker'), null, true);
}
add_action('admin_enqueue_scripts', 'tm_enqueue_admin_scripts');

wp_enqueue_style(
    'tm-shortcodes-css',
    TM_PLUGIN_URL . 'assets/css/shortcodes.css',
    array(),
    '1.0.0'
);


wp_enqueue_script(
    'tm-admin-js',
    TM_PLUGIN_URL . 'assets/js/admin.js',
    array('jquery', 'media-upload', 'thickbox'),
    '1.0.0',
    true
);
function tm_enqueue_swiper_assets() {
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), null, true);
    wp_add_inline_script('swiper-js', '
        document.addEventListener("DOMContentLoaded", function () {
            new Swiper(".tm-testimonials-slider", {
                loop: true,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                autoplay: {
                    delay: 5000,
                },
            });
        });
    ');
}
add_action('wp_enqueue_scripts', 'tm_enqueue_swiper_assets');

function tm_enqueue_font_awesome() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'tm_enqueue_font_awesome');

function tm_enqueue_slick_slider_assets() {
    // Slick CSS
    wp_enqueue_style('slick-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');
    wp_enqueue_style('slick-theme-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css');

    // Slick JS
    wp_enqueue_script('slick-js', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', ['jquery'], null, true);

    // Custom init script
    wp_enqueue_script('tm-slick-init', plugin_dir_url(__FILE__) . '../assets/js/tm-slick-init.js', ['jquery', 'slick-js'], null, true);
}
add_action('wp_enqueue_scripts', 'tm_enqueue_slick_slider_assets');


function tm_enqueue_cast_styles() {
    wp_enqueue_style('tm-season-cast-table', plugin_dir_url(__FILE__) . '../assets/css/tm_season_cast_table.css');
}
add_action('wp_enqueue_scripts', 'tm_enqueue_cast_styles');
