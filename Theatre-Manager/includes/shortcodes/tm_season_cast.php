<?php
function tm_season_cast_shortcode($atts) {

  //  $bg_color = get_option('tm_show_bg_color', '#ffffff');
    $text_color = get_option('tm_show_text_color', '#000000');
	$border_color = get_option('tm_show_border_color', '#000000');
    $border_width = 0;
    $rounded = get_option('tm_show_rounded') ? 'true' : 'false';
    $border_radius = get_option('tm_show_radius', '20');
    $shadow = get_option('tm_show_shadow') ? 'true' : 'false';
	
	$style = 'color:' . $text_color . '; border-width: ' . $border_width . ';border-color: ' . $border_color . '; border-radius: ' . $border_radius . '; border-style: solid;';
	if ($shadow === true) {
		$style = $style . ' box-shadow: 5px 10px;';
	}
	$style = $style .  '}';

    $atts = shortcode_atts([
        'season_id' => 0,
        'show_cast_images' => 'true',
        'cast_layout' => 'grid'
    ], $atts);
    if (!$atts['season_id']) return '';

    $slot_order = ['Fall', 'Winter', 'Spring'];
    $output = '<div style="'.$style. '" class="tm-season-cast">';

    $shows = get_posts([
        'post_type' => 'show',
        'numberposts' => -1,
        'meta_query' => [
            [
                'key' => '_tm_show_season',
                'value' => $atts['season_id'],
                'compare' => '='
            ]
        ]
    ]);

    $grouped = [];
    foreach ($shows as $show) {
        $slot = get_post_meta($show->ID, '_tm_show_time_slot', true);
        if (!isset($grouped[$slot])) {
            $grouped[$slot] = [];
        }
        $grouped[$slot][] = $show;
    }

    foreach ($slot_order as $slot) {
        if (!isset($grouped[$slot])) continue;

        $output .= '<h2>' . esc_html($slot) . ' Show</h2>';
        foreach ($grouped[$slot] as $show) {
            $img = get_post_meta($show->ID, '_tm_show_sm_image', true);
            $author = get_post_meta($show->ID, '_tm_show_author', true);
            $sub_authors = get_post_meta($show->ID, '_tm_show_sub_authors', true);
            $director = get_post_meta($show->ID, '_tm_show_director', true);
            $associate_director = get_post_meta($show->ID, '_tm_show_associate_director', true);
            $synopsis = get_post_meta($show->ID, '_tm_show_synopsis', true);
            $show_dates = get_post_meta($show->ID, '_tm_show_show_dates', true);

            $output .= '<div class="tm-show-card">';
            if ($img) {
                $output .= '<img src="' . esc_url($img) . '" alt="' . esc_attr($show->post_title) . '" class="tm-show-image">';
            }
            $output .= '<h3>' . esc_html($show->post_title) . '</h3>';

            if ($author) $output .= '<p><strong>Author:</strong> ' . esc_html($author) . '</p>';
            if ($sub_authors) $output .= '<p><strong>Sub-author(s):</strong> ' . esc_html($sub_authors) . '</p>';
            if ($director) $output .= '<p><strong>Director:</strong> ' . esc_html($director) . '</p>';
            if ($associate_director) $output .= '<p><strong>Associate Director:</strong> ' . esc_html($associate_director) . '</p>';
            if ($synopsis) $output .= '<p class=tm_show_synopsis><strong>Synopsis:</strong> ' . esc_html($synopsis) . '</p>';
            if ($show_dates) $output .= '<p><strong>Show Dates:</strong> ' . esc_html($show_dates) . '</p>';

            $cast_members = get_posts([
                'post_type' => 'cast',
                'numberposts' => -1,
                'meta_key' => '_tm_cast_show',
                'meta_value' => $show->ID
            ]);

            if ($cast_members) {
                $layout_class = $atts['cast_layout'] === 'list' ? 'tm-cast-list' : 'tm-cast-grid';
                $output .= '<div class="' . esc_attr($layout_class) . '">';

                if ($atts['cast_layout'] === 'list') {
                    $output .= '<table class="tm-cast-table">';
                    $output .= '<thead><tr>';
                    if ($atts['show_cast_images'] === 'true') {
                        $output .= '<th></th>';
                    }
                    $output .= '<th>Character</th><th>Actor</th>';
                    $output .= '</tr></thead><tbody>';
                }

                foreach ($cast_members as $cast) {
                    $character = get_post_meta($cast->ID, '_tm_cast_character_name', true);
                    $actor = get_post_meta($cast->ID, '_tm_cast_actor_name', true);
                    $picture = get_post_meta($cast->ID, '_tm_cast_picture', true);

                    if ($atts['cast_layout'] === 'list') {
                        $output .= '<tr>';
                        if ($atts['show_cast_images'] === 'true') {
                            $output .= '<td>';
                            if ($picture) {
                                $output .= '<img src="' . esc_url($picture) . '" alt="' . esc_attr($character) . '" style="max-width:50px;">';
                            }
                            $output .= '</td>';
                        }
                        $output .= '<td>' . esc_html($character) . '</td>';
                        $output .= '<td>' . esc_html($actor) . '</td>';
                        $output .= '</tr>';
                    } else {
                        $output .= '<div class="tm-cast-card">';
                        if ($atts['show_cast_images'] === 'true' && $picture) {
                            $output .= '<img src="' . esc_url($picture) . '" alt="' . esc_attr($character) . '" class="tm-cast-image">';
                        }
                        $output .= '<p><strong>Character:</strong> ' . esc_html($character) . '</p>';
                        $output .= '<p><strong>Actor:</strong> ' . esc_html($actor) . '</p>';
                        $output .= '</div>';
                    }
                }

                if ($atts['cast_layout'] === 'list') {
                    $output .= '</tbody></table>';
                }

                $output .= '</div>';
            } else {
                $output .= '<p><em>Cast: TBD</em></p>';
            }

            $output .= '</div>';
        }
    }

    $output .= '</div>';
    return $output;
}
add_shortcode('tm_season_cast', 'tm_season_cast_shortcode');
?>