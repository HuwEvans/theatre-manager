<?php
// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Get image HTML or fallback
 */
function tm_get_image_html($url, $alt = '', $class = '', $fallback = '') {
    if ($url) {
        return '<img src="' . esc_url($url) . '" alt="' . esc_attr($alt) . '" class="' . esc_attr($class) . '" />';
    } elseif ($fallback) {
        return '<img src="' . esc_url($fallback) . '" alt="' . esc_attr($alt) . '" class="' . esc_attr($class) . '" />';
    }
    return '';
}
