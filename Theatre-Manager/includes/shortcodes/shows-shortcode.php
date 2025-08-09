<?php
/**
 * Shortcode to display show entries.
 * Usage: [tm_shows exclude="genre,director"]
 * By default, all fields are shown. Use 'exclude' to hide specific fields.
 */

function tm_shortcode_shows($atts) {
    $atts = shortcode_atts(array(
        'exclude' => ''
    ), $atts);

    $exclude = array_map('trim', explode(',', $atts['exclude']));
    $query = new WP_Query(array('post_type' => 'show', 'posts_per_page' => -1));

    ob_start();
	$bg_color = esc_attr(get_option("tm_show_bg_color"));
	$text_color = esc_attr(get_option("tm_show_text_color"));
	$border_width = esc_attr(get_option("tm_show_border_width"));
	$rounded = get_option("tm_show_rounded") ? esc_attr(get_option("tm_show_radius")) : '0';
	$shadow = get_option("tm_show_shadow") ? '0 0 10px rgba(0,0,0,0.3)' : 'none';
	$style = "background-color: $bg_color; color: $text_color; border-width: {$border_width}px; border-style: solid; border-radius: {$rounded}px; box-shadow: $shadow;";

	echo '<div class="tm-shortcode-wrapper tm-show-wrapper" style="' . $style . '">';

    while ($query->have_posts()) {
        $query->the_post();
        $id = get_the_ID();
        echo '<div class="tm-entry tm-shows-entry">';
        echo '<h3>' . get_the_title() . '</h3>';

        $fields = ['author', 'sub_authors', 'synopsis', 'genre', 'director', 'associate_director', 'time_slot', 'show_dates'];
        foreach ($fields as $field) {
            if (!in_array($field, $exclude)) {
                $value = get_post_meta($id, '_tm_show_' . $field, true);
                if ($value) echo '<p><strong>' . ucfirst(str_replace('_', ' ', $field)) . ':</strong> ' . esc_html($value) . '</p>';
            }
        }

        if (!in_array('season', $exclude)) {
            $season_id = get_post_meta($id, '_tm_show_season', true);
            if ($season_id) echo '<p><strong>Season:</strong> ' . get_the_title($season_id) . '</p>';
        }

        if (!in_array('sm_image', $exclude)) {
            $value = get_post_meta($id, '_tm_show_sm_image', true);
            if ($value) echo '<div><img src="' . esc_url($value) . '" style="max-width:150px;" /></div>';
        }

        echo '</div>';
    }
    echo '</div>';
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('tm_shows', 'tm_shortcode_shows');
?>
