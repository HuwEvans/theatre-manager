<?php
function tm_season_banner_shortcode($atts) {
    $atts = shortcode_atts(['season_id' => 0], $atts);
    if (!$atts['season_id']) return '';

    $output = '<div class="tm-season-banner">';
    $fields = ['_tm_season_social_banner', '_tm_season_image_front', '_tm_season_image_back'];

    foreach ($fields as $field) {
        $img = get_post_meta($atts['season_id'], $field, true);
        if (($img) && ($field == '_tm_season_social_banner')) {
            $output .= '<img src="' . esc_url($img) . '" alt="Season Image">';
        } else {
            //$output .= '<img src="' . esc_url($img) . '" alt="Season Image" style="display: inline-block;width:45%">';		
		}
    }

    $output .= '</div>';
    return $output;
}
add_shortcode('tm_season_banner', 'tm_season_banner_shortcode');
?>