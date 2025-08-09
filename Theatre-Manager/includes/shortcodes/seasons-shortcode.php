<?php
/**
 * Shortcode to display season entries.
 * Usage: [tm_seasons exclude="start_date,end_date"]
 * By default, all fields are shown. Use 'exclude' to hide specific fields.
 */

function tm_shortcode_seasons($atts) {
    $atts = shortcode_atts(array(
        'exclude' => ''
    ), $atts);

    $exclude = array_map('trim', explode(',', $atts['exclude']));
    $query = new WP_Query(array('post_type' => 'season', 'posts_per_page' => -1));

    ob_start();
	$bg_color = esc_attr(get_option("tm_season_bg_color"));
	$text_color = esc_attr(get_option("tm_season_text_color"));
	$border_width = esc_attr(get_option("tm_season_border_width"));
	$rounded = get_option("tm_season_rounded") ? esc_attr(get_option("tm_season_radius")) : '0';
	$shadow = get_option("tm_season_shadow") ? '0 0 10px rgba(0,0,0,0.3)' : 'none';
	$style = "background-color: $bg_color; color: $text_color; border-width: {$border_width}px; border-style: solid; border-radius: {$rounded}px; box-shadow: $shadow;";

	echo '<div class="tm-shortcode-wrapper tm-season-wrapper" style="' . $style . '">';
    while ($query->have_posts()) {
        $query->the_post();
        $id = get_the_ID();
        echo '<div class="tm-entry tm-seasons-entry">';
        echo '<h3>' . get_the_title() . '</h3>';

        if (!in_array('start_date', $exclude)) {
            $value = get_post_meta($id, '_tm_season_start_date', true);
            if ($value) echo '<p><strong>Start Date:</strong> ' . esc_html($value) . '</p>';
        }
        if (!in_array('end_date', $exclude)) {
            $value = get_post_meta($id, '_tm_season_end_date', true);
            if ($value) echo '<p><strong>End Date:</strong> ' . esc_html($value) . '</p>';
        }
        foreach (['image_front', 'image_back', 'social_banner'] as $field) {
            if (!in_array($field, $exclude)) {
                $value = get_post_meta($id, '_tm_season_' . $field, true);
                if ($value) echo '<div><img src="' . esc_url($value) . '" style="max-width:150px;" /></div>';
            }
        }

        echo '</div>';
    }
    echo '</div>';
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('tm_seasons', 'tm_shortcode_seasons');
?>
