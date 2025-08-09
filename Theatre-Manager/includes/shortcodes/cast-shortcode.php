<?php
/**
 * Shortcode to display cast entries.
 * Usage: [tm_cast exclude="actor_name"]
 * By default, all fields are shown. Use 'exclude' to hide specific fields.
 */

function tm_shortcode_cast($atts) {
    $atts = shortcode_atts(array(
        'exclude' => ''
    ), $atts);

    $exclude = array_map('trim', explode(',', $atts['exclude']));
    $query = new WP_Query(array('post_type' => 'cast', 'posts_per_page' => -1));

    ob_start();
	$bg_color = esc_attr(get_option("tm_cast_bg_color"));
	$text_color = esc_attr(get_option("tm_cast_text_color"));
	$border_width = esc_attr(get_option("tm_cast_border_width"));
	$rounded = get_option("tm_cast_rounded") ? esc_attr(get_option("tm_cast_radius")) : '0';
	$shadow = get_option("tm_cast_shadow") ? '0 0 10px rgba(0,0,0,0.3)' : 'none';
	$style = "background-color: $bg_color; color: $text_color; border-width: {$border_width}px; border-style: solid; border-radius: {$rounded}px; box-shadow: $shadow;";

	echo '<div class="tm-shortcode-wrapper tm-cast-wrapper" style="' . $style . '">';
    while ($query->have_posts()) {
        $query->the_post();
        $id = get_the_ID();
        echo '<div class="tm-entry tm-cast-entry">';
        echo '<h3>' . get_the_title() . '</h3>';

        if (!in_array('actor_name', $exclude)) {
            $value = get_post_meta($id, '_tm_cast_actor_name', true);
            if ($value) echo '<p><strong>Actor Name:</strong> ' . esc_html($value) . '</p>';
        }

        if (!in_array('picture', $exclude)) {
            $value = get_post_meta($id, '_tm_cast_picture', true);
            if ($value) echo '<div><img src="' . esc_url($value) . '" style="max-width:150px;" /></div>';
        }

        if (!in_array('show', $exclude)) {
            $show_id = get_post_meta($id, '_tm_cast_show', true);
            if ($show_id) echo '<p><strong>Show:</strong> ' . get_the_title($show_id) . '</p>';
        }

        echo '</div>';
    }
    echo '</div>';
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('tm_cast', 'tm_shortcode_cast');
?>
