<?php
function tm_show_cast_shortcode($atts) {
    $atts = shortcode_atts(['show_id' => 0], $atts);
    if (!$atts['show_id']) return '';

    $cast_members = get_posts([
        'post_type' => 'cast',
        'numberposts' => -1,
        'meta_key' => '_tm_cast_show',
        'meta_value' => $atts['show_id']
    ]);

    $output = '<div class="tm-show-cast">';
    foreach ($cast_members as $cast) {
        $actor = get_post_meta($cast->ID, '_tm_cast_actor_name', true);
        $character = get_post_meta($cast->ID, '_tm_cast_character_name', true);
        $picture = get_post_meta($cast->ID, '_tm_cast_picture', true);

        $output .= '<div class="cast-member">';
        if ($picture) $output .= '<img src="' . esc_url($picture) . '" alt="' . esc_attr($character) . '">';
        $output .= '<h3>' . esc_html($character) . '</h3>';
        $output .= '<p>' . esc_html($actor) . '</p>';
        $output .= '</div>';
    }
    $output .= '</div>';

    return $output;
}
add_shortcode('tm_show_cast', 'tm_show_cast_shortcode');
?>